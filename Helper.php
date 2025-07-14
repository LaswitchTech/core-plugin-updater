<?php

// Import additionnal class into the global namespace
use \LaswitchTech\Core\Abstracts\Helper;

class UpdaterHelper extends Helper {

    /**
     * Retrieve the list of releases from the repository
     *
     * @return array
     */
    public function releases(): array
    {
        // Retrieve the repository
        $repository = $this->Config->get('installer','git')['repository'];

        // Retrieve the token
        $token = $this->Config->get('installer','git')['token'];

        // Retrieve the name
        $name = $this->Config->get('installer','name');

        // Initialize the url
        $url = "https://api.github.com/repos/{$repository}/releases";

        // Initialize curl
        $cURL = curl_init($url);

        // Set Headers
        $headers = [
            'User-Agent: ' . $name,
            'Accept: application/vnd.github.v3+json'
        ];

        // Check if a token is set
        if (!is_null($token) && !empty($token)) {
            $headers[] = 'Authorization: token ' . $token;
        }

        // Set cURL options
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cURL, CURLOPT_HTTPHEADER, $headers);

        // Execute the request
        $response = curl_exec($cURL);
        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);

        // Close the cURL session
        curl_close($cURL);

        // Check if the response is valid
        if($status == 200){

            // Decode the response
            return json_decode($response, true);
        }

        return [];
    }

    /**
     * Download a file
     *
     * @param string $url
     * @param string $destination
     * @return bool
     */
    public function download(string $url, string $destination): bool
    {
        // Check if the URL is valid
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }

        // Retrieve the token
        $token = $this->Config->get('installer','git')['token'];

        // Retrieve the name
        $name = $this->Config->get('installer','name');

        // Check if the destination directory exists
        if(!is_dir(dirname($destination))){
            mkdir(dirname($destination), 0755, true);
        }

        // Check if the destination file exists
        if(file_exists($destination)){
            unlink($destination);
        }

        // Initialize curl
        $cURL = curl_init($url);

        // Set Headers
        $headers = [
            'User-Agent: ' . $name,
            'Accept: application/octet-stream',
        ];
        if (!is_null($token) && !empty($token)) {
            $headers[] = 'Authorization: token ' . $token;
        }

        // Set options for the cURL request
        $cURLOptions = [
            // Provide metadata
            CURLOPT_USERAGENT => $name,
            // Insert Headers
            CURLOPT_HEADER => 0,
            CURLOPT_HTTPHEADER => $headers,
            // Return the transfer as a string
            CURLOPT_RETURNTRANSFER => true,
            // Handle Redirections
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            // Handle Connection Timeout
            CURLOPT_TIMEOUT => 30,
            // Disable SSL Verification
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ];

        // Set the cURL options
        curl_setopt_array($cURL, $cURLOptions);

        // Execute the request
        $stream = curl_exec($cURL);
        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE);
        $error = curl_error($cURL);

        // Close cURL session
        curl_close($cURL);

        // Check if the request was successful
        if ($status !== 200) {
            return false;
        }

        // Create the file using file_put_contents
        $result = file_put_contents($destination, $stream);
        if ($result === false) {
            return false;
        }

        return true;
    }

    /**
     * Validate the checksum of a file
     *
     * @param string $path
     * @param string $checksum
     * @return bool
     */
    public function validate(string $path, string $checksum): bool
    {
        // Check if the file exists
        if (!file_exists($path)) {
            return false;
        }

        // Calculate the checksum of the file
        $fileChecksum = hash_file('sha256', $path);

        // Compare the checksums
        return hash_equals($fileChecksum, $checksum);
    }
}
