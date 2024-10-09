<?php
// Function to get the user's IP address
function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Check if the IP is from shared internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Check if the IP is passed from a proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // Default fallback
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

// Display a list of all the queryable headers
echo "<h2>Client-Side and Server-Side Information</h2>";

// 1. User's IP address
echo "<strong>IP Address:</strong> " . getUserIP() . "<br>";

// 2. Browser User-Agent
echo "<strong>Browser/User-Agent:</strong> " . $_SERVER['HTTP_USER_AGENT'] . "<br>";

// 3. Request Method
echo "<strong>Request Method:</strong> " . $_SERVER['REQUEST_METHOD'] . "<br>";

// 4. HTTP Referer (previous page)
echo "<strong>HTTP Referer:</strong> " . (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'None') . "<br>";

// 5. Host Address
echo "<strong>Host:</strong> " . $_SERVER['HTTP_HOST'] . "<br>";

// 6. Server Protocol
echo "<strong>Server Protocol:</strong> " . $_SERVER['SERVER_PROTOCOL'] . "<br>";

// 7. Remote Port
echo "<strong>Remote Port:</strong> " . $_SERVER['REMOTE_PORT'] . "<br>";

// 8. Accept Language
echo "<strong>Accept Language:</strong> " . (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'None') . "<br>";

// 9. Accept Encoding
echo "<strong>Accept Encoding:</strong> " . (isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : 'None') . "<br>";

// 10. Server Software
echo "<strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "<br>";

// 11. Connection Status
echo "<strong>Connection:</strong> " . (isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : 'None') . "<br>";

// 12. Query String
echo "<strong>Query String:</strong> " . $_SERVER['QUERY_STRING'] . "<br>";

// 13. Current Script Filename
echo "<strong>Script Filename:</strong> " . $_SERVER['SCRIPT_FILENAME'] . "<br>";

// 14. Server Name
echo "<strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "<br>";

// 15. Remote Host
echo "<strong>Remote Host:</strong> " . (isset($_SERVER['REMOTE_HOST']) ? $_SERVER['REMOTE_HOST'] : 'None') . "<br>";

// 16. Server IP Address
echo "<strong>Server IP Address:</strong> " . $_SERVER['SERVER_ADDR'] . "<br>";

// 17. Server Port
echo "<strong>Server Port:</strong> " . $_SERVER['SERVER_PORT'] . "<br>";

// 18. Request URI
echo "<strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "<br>";

// 19. Script Name
echo "<strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "<br>";

// 20. Current File Path
echo "<strong>File Path:</strong> " . __FILE__ . "<br>";

// 21. Document Root
echo "<strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "<br>";

// 22. Gateway Interface
echo "<strong>Gateway Interface:</strong> " . $_SERVER['GATEWAY_INTERFACE'] . "<br>";

// 23. Client Connection Type
echo "<strong>Client Connection Type:</strong> " . (isset($_SERVER['HTTP_CONNECTION']) ? $_SERVER['HTTP_CONNECTION'] : 'None') . "<br>";

// 24. HTTPS (Secure Connection)
echo "<strong>HTTPS Enabled:</strong> " . (isset($_SERVER['HTTPS']) ? $_SERVER['HTTPS'] : 'Off') . "<br>";

echo "<h3>All HTTP Headers</h3>";
// Display all HTTP headers sent by the client
foreach (getallheaders() as $name => $value) {
    echo "<strong>$name:</strong> $value<br>";
}
?>
