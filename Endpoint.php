<?php

// Import additionnal class into the global namespace
use \LaswitchTech\Core\Objects;
use \LaswitchTech\Core\Abstracts\Endpoint;

class UpdaterEndpoint extends Endpoint {

    /**
     * Constructor
     */
    public function __construct()
    {

        // Call Parent Constructor
        parent::__construct();

        // Retrieve the namespace
        $namespace = $this->Request->getNamespace();

        // Set Global access
        $this->Public = false;

        // Set Properties
        switch($namespace){
            case "/updater/fetch":
                $this->Level = 1;
                break;
            case "/updater/writable":
            case "/updater/download":
            case "/updater/validate":
            case "/updater/extract":
            case "/updater/copy":
            case "/updater/upgrade":
            case "/updater/dependencies":
            case "/updater/replace":
            case "/updater/cleanup":
                $this->Level = $this->Config->get('application', 'maintenance') ? 1 : 5;
                break;
        }
    }

    /**
     * Fetch Application Information
     */
    public function fetchAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve data for the request
                $version = $this->Config->version();
                $releases = $this->Helper->Updater->releases();

                // Check if the releases are available
                if(!empty($releases)){
                    $latest = $releases[array_key_first($releases)];
                    $assets = $latest['assets'];
                    $url = $latest['zipball_url'];
                    $checksum = null;

                    // Loop through the assets
                    foreach($assets as $asset){
                        if($asset['name'] == $latest['tag_name'].".zip"){
                            $url = $asset['url'];
                        }
                        if($asset['name'] == $latest['tag_name'].".sha256"){
                            $checksum = $asset['url'];
                        }
                    }

                    // Set the data
                    $message["data"]["maintenance"] = $this->Config->get('application', 'maintenance');
                    $message["data"]["current"] = $version;
                    $message["data"]["latest"] = $latest['tag_name'];
                    $message["data"]["url"] = $url;
                    $message["data"]["checksum"] = $checksum;
                    $message["data"]["available"] = version_compare($version, $latest['tag_name'], '<');
                    $message["data"]["message"] = '';

                    // Create a message for the user
                    if($message["data"]["available"]){
                        $message["data"]["message"] .= $this->Locale->get("Current Version") . ": " . $version . PHP_EOL;
                        $message["data"]["message"] .= $this->Locale->get("Latest Version") . ": " . $latest['tag_name'] . PHP_EOL;
                    } else {
                        $message["data"]["message"] .= $this->Locale->get("No Update Available") . PHP_EOL;
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to retrieve the releases"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Verify that the root directory is writable
     */
    public function writableAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Set the default message
                $message["data"]["writable"] = is_writable($this->Config->root());
                $message["data"]["message"] = '';
                if($message["data"]["writable"]){
                    $message["data"]["message"] .= $this->Locale->get("Root Directory Writable") . PHP_EOL;
                } else {
                    $message["data"]["message"] .= $this->Locale->get("Root Directory Not Writable") . PHP_EOL;
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Download the latest release
     */
    public function downloadAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve the URL
                $url = $this->Request->getParams('REQUEST')['url'] ?? null;
                $checksum = $this->Request->getParams('REQUEST')['checksum'] ?? null;
                $version = $this->Request->getParams('REQUEST')['version'] ?? null;

                // Check if the URL is set
                if($url && $checksum && $version){

                    // Set the path
                    $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $version . '.zip';

                    // Download the file
                    $this->Helper->Updater->download($url, $path);

                    // Check if the file was downloaded
                    if(file_exists($path)){

                        // Set the archive path
                        $message["data"]["archive"] = $path;

                        // Set the path
                        $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $version . '.sha256';

                        // Download the file
                        $this->Helper->Updater->download($checksum, $path);

                        // Check if the file was downloaded
                        if(file_exists($path)){

                            // Set the archive path
                            $message["data"]["checksum"] = [
                                "path" => $path,
                                "content" => file_get_contents($path),
                            ];
                            $message["data"]["message"] = $this->Locale->get("File(s) Downloaded");
                        } else {

                            // Set an error message
                            $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to download the file"];
                        }
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to download the file"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 400, "message" => "Bad Request", "data" => "Missing URL"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Validate the integrity of the downloaded files
     */
    public function validateAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve the URL
                $version = $this->Request->getParams('REQUEST')['version'] ?? null;

                // Check if the URL is set
                if($version){

                    // Set the path
                    $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $version . '.sha256';

                    // Retrieve the content of the checksum
                    $checksum = file_get_contents($path);

                    // Retrieve the checksum
                    $checksum = explode(" ", $checksum)[0];
                    $checksum = trim($checksum);

                    // Set the path
                    $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $version . '.zip';

                    // Validate the checksum
                    $valid = $this->Helper->Updater->validate($path, $checksum);

                    // Check if the checksum is valid
                    if($valid){

                        // Set the message
                        $message["data"]["valid"] = $valid;
                        $message["data"]["checksum"] = $checksum;
                        $message["data"]["message"] = $this->Locale->get("File integrity verified");
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Checksum is invalid"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 400, "message" => "Bad Request", "data" => "Missing URL"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Extract the downloaded files
     */
    public function extractAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve the Version
                $version = $this->Request->getParams('REQUEST')['version'] ?? null;

                // Check if the Version is set
                if($version){

                    // Set the path
                    $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $version;

                    // Unpack the archive
                    if($this->Model->Backups->unpack($path . '.zip', $path)){

                        // Set the message
                        $message["data"]["path"] = $path;
                        $message["data"]["archive"] = $path . '.zip';
                        $message["data"]["message"] = $this->Locale->get("Files extracted");
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to extract the files"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 400, "message" => "Bad Request", "data" => "Missing Version"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Copy the extracted files to the root directory
     */
    public function copyAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve the Version
                $version = $this->Request->getParams('REQUEST')['version'] ?? null;

                // Check if the Version is set
                if($version){

                    // Set the path
                    $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $version;

                    // Copy the files to the root directory
                    if($this->Model->Backups->copy($path, $this->Config->root())){

                        // Set the message
                        $message["data"]["path"] = $path;
                        $message["data"]["archive"] = $path . '.zip';
                        $message["data"]["message"] = $this->Locale->get("Files copied");
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to copy the files"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 400, "message" => "Bad Request", "data" => "Missing Version"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Upgrade the database
     */
    public function upgradeAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve the Version
                $version = $this->Request->getParams('REQUEST')['version'] ?? null;

                // Retrieve the UUID
                $uuid = $this->Request->getParams('REQUEST')['uuid'] ?? null;

                // Check if the Version and UUID are set
                if($version && $uuid){

                    // Upgrade the database
                    if($this->Model->Updater->upgrade()){

                        // Set the message
                        $message["data"]["message"] = $this->Locale->get("Database upgraded");

                        // Set the path of the Backup directory
                        $path = $this->Config->root() . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . $uuid;

                        // Unpack the backup archive
                        if($this->Model->Backups->unpack($path . '.zip', $path)){

                            // Migrate the database
                            if($this->Model->Updater->migration($version, $uuid)){

                                // Set the message
                                $message["data"]["message"] .= PHP_EOL . $this->Locale->get("Database migrated");

                                // Import any new required data
                                if($this->Model->Updater->insertRequired()){

                                    // Set the message
                                    $message["data"]["message"] .= PHP_EOL . $this->Locale->get("New required data imported");
                                } else {

                                    // Set an error message
                                    $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to import new required data"];
                                }
                            } else {

                                // Set an error message
                                $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to migrate the database"];
                            }
                        } else {

                            // Set an error message
                            $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to extract the backup files"];
                        }
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to upgrade the database"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 400, "message" => "Bad Request", "data" => "Missing Version and/or UUID"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Update dependencies
     */
    public function dependenciesAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Download composer
                if($this->Helper->Composer->download()){

                    // Update dependencies
                    if($this->Helper->Composer->install()){

                        // Set the message
                        $message["data"]["message"] = $this->Locale->get("Dependencies updated");
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to update dependencies"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to download composer"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Replace the entry points
     */
    public function replaceAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Download composer
                if($this->Helper->Core->init(true)){

                    // Set the message
                    $message["data"]["message"] = $this->Locale->get("Entry points replaced");
                } else {

                    // Set an error message
                    $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to replace entry points"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }

    /**
     * Clean up the temporary files
     */
    public function cleanupAction(): array
    {
        // Import Global Variables
        global $CSRF;

        // Set the default message
        $message = ["status" => 200, "message" => "OK", "data" => []];

        // Check the request method
        if($this->Request->getMethod() == "POST"){
            $message["data"]["CSRF"] = [
                "token" => $CSRF->token(),
                "key" => $CSRF->key()
            ];
        }

        // Check if the status is still OK
        if($message['status'] == 200){

            // Check the request method
            if($this->Request->getMethod() == "POST"){

                // Retrieve the UUID
                $uuid = $this->Request->getParams('REQUEST')['uuid'] ?? null;

                // Check if the required data are set
                if($uuid){

                    // Clean-up Composer
                    if($this->Helper->Composer->clean()){

                        // Clean-up Backup
                        if($this->Model->Backups->delete($this->Config->root() . DIRECTORY_SEPARATOR . 'backup' . DIRECTORY_SEPARATOR . $uuid)){

                            // Clean-up Backup
                            if($this->Model->Backups->delete($this->Config->root() . DIRECTORY_SEPARATOR . 'tmp')){

                                // Set the message
                                $message["data"]["message"] = $this->Locale->get("Clean-up completed");
                            } else {

                                // Set an error message
                                $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to clean temporary files"];
                            }
                        } else {

                            // Set an error message
                            $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to clean backup"];
                        }
                    } else {

                        // Set an error message
                        $message = ["status" => 500, "message" => "Internal Server Error", "data" => "Unable to clean composer"];
                    }
                } else {

                    // Set an error message
                    $message = ["status" => 400, "message" => "Bad Request", "data" => "Missing required data"];
                }
            } else {

                // Set an error message
                $message = ["status" => 405, "message" => "Method Not Allowed", "data" => "Invalid Request"];
            }
        }

        // Return the message
        return $message;
    }
}
