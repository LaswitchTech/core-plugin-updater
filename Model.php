<?php

// Import additionnal class into the global namespace
use \LaswitchTech\Core\Abstracts\Model;

class UpdaterModel extends Model {

    private $Config;
    private $Log;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Call Parent Constructor
        parent::__construct();

        // Import Global Variables
        global $CONFIG,$LOG;

        // Set Properties
        $this->Config = $CONFIG;
        $this->Log = $LOG;

        // Set Logger
        $this->Log->add('updater');
    }

    /**
     * Recursively delete a directory (including its contents).
     *
     * @param string $directory Path to the directory you want to remove
     * @return bool true on success, false on failure
     */
    public function delete(string $directory): bool
    {
        // Set the log channel to 'backup'
        $this->Log->set('backup');

        // If it doesn't exist, treat it as an error or success depending on your preference
        if (!file_exists($directory)) {
            // Option 1: Treat as an error
            $this->Log->error("Directory does not exist: $directory");
            return false;

            // Option 2: Treat as success since there's nothing to delete
            // $this->Log->info("Directory does not exist, nothing to delete: $directory");
            // return true;
        }

        // If it's a file or symlink, just unlink it
        if (!is_dir($directory)) {
            if (!@unlink($directory)) {
                $this->Log->error("Failed to delete file or symlink: $directory");
                return false;
            }
            $this->Log->success("Deleted file or symlink: $directory");
            return true;
        }

        // Otherwise, recursively remove contents
        $items = scandir($directory);
        if ($items === false) {
            $this->Log->error("Failed to scan directory: $directory");
            return false;
        }

        foreach ($items as $item) {
            // Skip pointers
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $directory . DIRECTORY_SEPARATOR . $item;

            // Recursively call delete on each item
            if (!$this->delete($path)) {
                // If any item fails to be deleted, return false
                return false;
            }
        }

        // Finally, remove the now-empty directory
        if (!@rmdir($directory)) {
            $this->Log->error("Failed to delete directory (it might not be empty or permission denied): $directory");
            return false;
        }

        $this->Log->success("Deleted directory: $directory");
        return true;
    }

    /**
     * Upgrade the database
     *
     * @return bool
     */
    public function upgrade(): bool
    {
        // Set the path of the Install directory
        $installPath = $this->Config->root() . DIRECTORY_SEPARATOR . "Install";

        // Check if the directory exists
        if(is_dir($installPath)){

            // Set the path of the Definition directory
            $definitionPath = $this->Config->root() . DIRECTORY_SEPARATOR . "Definition";

            // Clear the local Definition directory
            if (is_dir($definitionPath)) {
                $this->delete($definitionPath);
            }
            if(!is_dir($definitionPath)){
                mkdir($definitionPath, 0755, true);
            }

            // Retrieve the list of definition files
            $definitions = array_diff(scandir($installPath . DIRECTORY_SEPARATOR . "Definition"), ['..', '.']);

            try {
                // Loop through the definition files
                foreach($definitions as $definition) {

                    // Remove the .map extension
                    $table = str_replace('.map', '', $definition);

                    // Check if the definition file already exists and delete it
                    if(is_file($definitionPath . DIRECTORY_SEPARATOR . $table)){
                        unlink($definitionPath . DIRECTORY_SEPARATOR . $definition);
                    }

                    // Copy the definition file to the Definition directory
                    copy($installPath . DIRECTORY_SEPARATOR . "Definition" . DIRECTORY_SEPARATOR . $definition, $definitionPath . DIRECTORY_SEPARATOR . $definition);

                    // Create the Schema
                    $Schema = $this->Database->schema()->define($table);

                    // Check if the Schema is already exists
                    if($Schema->exists()){

                        // Drop the Schema
                        $Schema->drop();
                    }

                    // Import the Schema in the Database
                    $Schema->create();
                }

                // Return true
                return true;
            } catch (\Exception $e) {

                // Return false
                return false;
            }
        } else {

            // Return false
            return false;
        }
    }

    /**
     * Migrate the database
     *
     * @param string $current
     * @param string $uuid
     * @return bool
     */
    public function migration(string $current, string $uuid): bool
    {
        // Set Logger
        $this->Log->set('updater');

        // Set the path of the Install directory
        $installPath = $this->Config->root() . DIRECTORY_SEPARATOR . "Install";

        // Retrieve the list of definition files
        $definitions = array_diff(scandir($installPath . DIRECTORY_SEPARATOR . "Definition"), ['..', '.']);

        // Create a Tables array
        $tables = [];

        // Loop through the definition files
        foreach($definitions as $definition) {

            // Remove the .map extension
            $tables[] = str_replace('.map', '', $definition);
        }

        try {

            // Initialize a dictionary
            $dictionary = [];

            // Set the path of the Backup directory
            $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . $uuid . DIRECTORY_SEPARATOR . 'Data';

            // Check if the Backup directory exists
            if(!is_dir($path)){
                throw new \Exception("Backup directory [$path] does not exist");
            }

            // Retrieve the list of dump files
            $dumps = array_diff(scandir($path), ['..', '.','.DS_Store']);

            // Loop through the dump files
            foreach($dumps as $dump) {

                // Retrieve the table name
                $table = str_replace('.dump', '', $dump);

                // Check if the dump file is a .sql file
                if(pathinfo($dump, PATHINFO_EXTENSION) === 'dump'){

                    // Save the path of the dump file
                    $dictionary[$table] = $path . DIRECTORY_SEPARATOR . $dump;
                }
            }

            // Retrieve the new version
            $latest = $this->Config->version();

            // Retrieve the migrations
            $migrations = $this->Config->get('migration');

            // Loop through the migrations
            foreach($migrations as $version => $migration){

                // Check if version is greater than the current version and if version is lower or equal to the latest version
                if(version_compare($current, $version, '<') && version_compare($version, $latest, '<=')){

                    // Loop through the migration steps
                    foreach($migration as $step){

                        // Run the migration step
                        $dictionary = $this->migrate($dictionary, $step['type'], $step['table'], $step['object'], $step['column'], $step['value']);
                    }
                }
            }

            // Check if the dictionary is empty
            if(empty($dictionary)){
                throw new \Exception("No data has been migrated");
            }

            // Loop through the dictionary
            foreach($dictionary as $table => $path) {

                // Add debuging information
                $this->Log->debug("Re-Creating table [$table] from [$path]");

                // Check if the table exists
                if(is_file($path)){

                    // Load the table data
                    $data = $this->load($path);

                    // Add debuging information
                    $this->Log->debug("Inserting [".count($data)."] records to table [$table]");

                    // Loop through the data
                    foreach($data as $record){

                        // Check if the table is in the list of tables that should be migrated
                        if(in_array($table,$tables)){

                            // Create the Query
                            $Query = $this->Database->query()
                                ->table($table)
                                ->insert($record);

                            // Execute the Query
                            if($Query->execute() <= 0){

                                // Log the error
                                throw new \Exception("Failed to insert record [".json_encode($record)."] to table [$table]");
                            }
                        }
                    }
                }
            }

            // Return true
            return true;
        } catch (\Exception $e) {

            // Log the error
            $this->Log->error($e->getMessage());
            $this->Log->error($e->getTraceAsString());
            $this->Log->error($e->getFile() . ":" . $e->getLine());

            // Return false
            return false;
        }
    }

    /**
     * Migrate the database
     *
     * @param array $dictionary
     * @param string $type
     * @param string $table
     * @param string $object
     * @param string|null $column
     * @param string|null $value
     * @return array
     */
    protected function migrate(array $dictionary, string $type, string $table, string $object, ?string $column, ?string $value): array
    {
        // Check if the table exists
        if(isset($dictionary[$table])){

            // Check if the step is a table rename
            if($type == "rename"){

                // Check the object to apply on
                if($object == "table"){

                    // Load the table data
                    $data = $this->load($dictionary[$table]);

                    // Add the table to the dictionary
                    $dictionary[$value] = $path . DIRECTORY_SEPARATOR . $value . ".dump";

                    // Save the table data
                    $this->save($dictionary[$value], $data);

                    // Delete the old table
                    if(is_file($dictionary[$table])){

                        // Delete the old table
                        unlink($dictionary[$table]);
                    }

                    // Remove the old table from the dictionary
                    unset($dictionary[$table]);
                } elseif ($object == "column") {

                    // Load the table data
                    $data = $this->load($dictionary[$table]);

                    // Loop through the data
                    foreach($data as $key => $value){

                        // Check if the column exists
                        if(array_key_exists($column, $value)){

                            // Rename the column
                            $data[$key][$value] = $data[$key][$column];

                            // Unset the old column
                            unset($data[$key][$column]);
                        }
                    }

                    // Save the table data
                    $this->save($dictionary[$table], $data);
                }
            } elseif ($type == "add") {

                // Check the object to apply on
                if($object == "table"){
                } elseif ($object == "column") {
                } elseif ($object == "data") {
                }
            } elseif ($type == "delete") {

                // Check the object to apply on
                if($object == "table"){

                    // Log the table to be deleted
                    $this->Log->debug("Deleting table [$table]");

                    // Check if the table exists
                    if(is_file($dictionary[$table])){

                        // Delete the table
                        unlink($dictionary[$table]);
                    }

                    // Remove the table from the dictionary
                    unset($dictionary[$table]);
                } elseif ($object == "column") {

                    // Load the table data
                    $data = $this->load($dictionary[$table]);

                    // Loop through the data
                    foreach($data as $key => $value){

                        // Log the column to be deleted
                        $this->Log->debug("Deleting column [$column] from table [$table]");

                        // Check if the column exists
                        if(array_key_exists($column, $value)){

                            // Unset the column
                            unset($data[$key][$column]);
                        }
                    }

                    // Save the table data
                    $this->save($dictionary[$table], $data);
                } elseif ($object == "data") {

                    // Load the table data
                    $data = $this->load($dictionary[$table]);

                    // Loop through the data
                    foreach($data as $key => $value){

                        // Log the data to be deleted
                        $this->Log->debug("Deleting data column [$column] from table [$table]");

                        // Check if the column is a wildcard *
                        if($column == "*"){

                            // Unset the column
                            unset($data[$key]);
                        }
                    }

                    // Save the table data
                    $this->save($dictionary[$table], $data);
                }
            } elseif ($type == "update") {

                // Check the object to apply on
                if($object == "table"){
                } elseif ($object == "column") {
                } elseif ($object == "data") {
                }
            } elseif ($type == "convert") {

                // Check the object to apply on
                if($object == "table"){
                } elseif ($object == "column") {
                } elseif ($object == "data") {
                }
            } elseif ($type == "filter") {

                // Check the object to apply on
                if($object == "table"){
                } elseif ($object == "column") {
                } elseif ($object == "data") {
                }
            }
        }

        return $dictionary;
    }

    /**
     * Load the file
     *
     * @param string $path
     * @return array
     */
    protected function load(string $path): array
    {
        // Check if the file exists
        if(is_file($path)){

            // Load the file
            return json_decode(file_get_contents($path), true);
        }

        return [];
    }

    /**
     * Save the file
     *
     * @param string $path
     * @param array $data
     * @return bool
     */
    protected function save(string $path, array $data): bool
    {
        // Check if the file exists
        if(is_file($path)){

            // Save the file
            return file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        }

        return false;
    }

    /**
     * Insert required data
     *
     * @return bool
     */
    public function insertRequired(): bool
    {

        // Set Logger
        $this->Log->set('updater');

        // Attempt the update of required data
        try{

            // Set the path of the Backup directory
            $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'Install' . DIRECTORY_SEPARATOR . 'Data';

            // Retrieve the list of files
            $files = array_diff(scandir($path), ['..', '.']);

            // Loop through the data files
            foreach($files as $key => $file){

                // Retrieve the table name
                $table = str_replace('.required', '', $file);

                // Check if the file is a .sql file
                if(pathinfo($file, PATHINFO_EXTENSION) === 'required'){

                    // Check if the file exists
                    if(is_file($path . DIRECTORY_SEPARATOR . $file)){

                        // Load the file
                        $data = $this->load($path . DIRECTORY_SEPARATOR . $file);

                        // Check if the data is empty
                        if(!empty($data)){

                            // Initialize the lastId
                            $lastId = 0;

                            // Find the last record id of required data from the table
                            $Query = $this->Database->query()
                                ->table($table)
                                ->select('*')
                                ->order('id', 'DESC')
                                ->limit(1)
                                ->where('id', 5000, '<');

                            // Fetch the last record
                            $lastRequired = $Query->fetch();

                            // Check if the last record id is empty
                            if(!empty($lastRequired)){

                                // Set the lastId
                                $lastId = $lastRequired[0]['id'];
                            }

                            // Loop through the data to import the missing records
                            foreach($data as $record){

                                // Check if our record is a placeholder
                                if($record['id'] == 9999){

                                    // Create the Query
                                    $Query = $this->Database->query()
                                        ->table($table)
                                        ->select('*')
                                        ->limit(1)
                                        ->where('id', $record['id']);

                                    // Fetch the record
                                    $placeholder = $Query->fetch();

                                    // Check if the placeholder already exists
                                    if(empty($placeholder)){

                                        // Create the Query
                                        $Query = $this->Database->query()
                                            ->table($table)
                                            ->insert($record);

                                        // Execute the Query
                                        $Query->execute();
                                    }
                                } else {

                                    // Check if the record id is greater than the lastId
                                    if($record['id'] > $lastId){

                                        // Create the Query
                                        $Query = $this->Database->query()
                                            ->table($table)
                                            ->insert($record);

                                        // Execute the Query
                                        $Query->execute();
                                    } else {

                                        // Create an exclusion array
                                        $exclusions = ['users','groups'];

                                        // Loop through the exclusions
                                        foreach($exclusions as $exclusion){

                                            // Check if the record is a placeholder
                                            if(array_key_exists($exclusion, $record)){

                                                // Unset the record
                                                unset($record[$exclusion]);
                                            }
                                        }

                                        // Create the Query
                                        $Query = $this->Database->query()
                                            ->table($table)
                                            ->update($record)
                                            ->where('id', $record['id']);

                                        // Execute the Query
                                        $Query->execute();
                                    }
                                }
                            }
                        } else {

                            // Unset the file
                            unset($files[$key]);
                        }
                    } else {

                        // Unset the file
                        unset($files[$key]);
                    }
                } else {

                    // Unset the file
                    unset($files[$key]);
                }
            }

            // Return true
            return true;
        } catch (\Exception $e) {

            // Log the error
            $this->Log->error($e->getMessage());

            // Return false
            return false;
        }
    }
}
