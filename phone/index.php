<?php
/**
 * CTM Phone Embed PHP Example
 *
 * This file serves as the main entry point for the application
 * and handles routing requests to the appropriate handlers.
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load configuration
$config = require_once __DIR__ . '/config.php';

// Extract config values
$ctmSecret = $config['ctm_secret'];
$ctmToken = $config['ctm_token'];
$accountId = $config['ctm_account_id'];
$ctmHost = $config['ctm_host'];

// Debug info - Comment out in production
if (PHP_SAPI === 'cli') {
    echo "CTM_HOST: $ctmHost\n";
    echo "CTM_TOKEN: " . ($ctmToken ? "Set" : "Not Set") . "\n";
    echo "CTM_SECRET: " . ($ctmSecret ? "Set" : "Not Set") . "\n";
    echo "CTM_ACCOUNT_ID: $accountId\n";
}

// Get the request URI
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Simple router based on the request URI
switch ($requestUri) {
    case '/ctm-phone-access':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            handleCtmPhoneAccess($ctmHost, $ctmToken, $ctmSecret, $accountId);
        } else {
            // Method not allowed
            http_response_code(405);
            header('Allow: POST');
            echo "Method Not Allowed";
        }
        break;

    case '/ctm-device':
        // Use PHP view file instead of HTML template
        renderPhpView('device.view.php', ['ctmHost' => $ctmHost]);
        break;

    case '/app.js':
        serveStaticFile('app.js', 'application/javascript');
        break;

    case '/favicon.ico':
        serveStaticFile('favicon.ico', 'image/x-icon');
        break;

    default:
        // Default: serve index.php view
        renderPhpView('index.view.php', ['ctmHost' => $ctmHost]);
        break;
}

/**
 * Handle the CTM phone access API call
 */
function handleCtmPhoneAccess($ctmHost, $ctmToken, $ctmSecret, $accountId) {
    // Set content type to JSON
    header('Content-Type: application/json');

    // Verify required configuration
    if (empty($ctmToken) || empty($ctmSecret) || empty($accountId)) {
        http_response_code(500);
        echo json_encode(['error' => 'Missing CTM configuration. Please check environment variables.']);
        return;
    }

    // API endpoint URL
    $requestUrl = "https://$ctmHost/api/v1/accounts/$accountId/phone_access";

    // Dummy data for the request - in a real application, this would come from the user session
    $email = "demo@example.com";
    $requestData = [
        'email' => $email,
        'first_name' => "John",
        'last_name' => "Doe",
        'session_id' => session_id() ?: "dummy_session_id"
    ];

    // Convert request data to JSON
    $jsonContent = json_encode($requestData);

    // Initialize cURL session
    $ch = curl_init($requestUrl);

    // Set cURL options
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonContent);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonContent)
    ]);

    // Set Basic Authentication
    curl_setopt($ch, CURLOPT_USERPWD, "$ctmToken:$ctmSecret");

    // Execute cURL request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    // Close cURL session
    curl_close($ch);

    if ($httpCode >= 200 && $httpCode < 300 && !$error) {
        // Parse the response
        $responseData = json_decode($response, true);

        // Create enhanced response data
        $enhancedResponseData = [
            'status' => $responseData['status'] ?? null,
            'token' => $responseData['token'] ?? null,
            'valid_until' => $responseData['valid_until'] ?? 0,
            'sessionId' => $requestData['session_id'],
            'email' => $requestData['email'],
            'last_name' => $requestData['last_name'],
            'first_name' => $requestData['first_name']
        ];

        // Return the enhanced response
        echo json_encode($enhancedResponseData);
    } else {
        // Return error
        http_response_code(500);
        $errorMessage = $error ?: 'Error accessing the phone access service';
        echo json_encode(['error' => $errorMessage]);
    }
}

/**
 * Render a PHP view file
 */
function renderPhpView($viewFile, $variables = []) {
    // Extract variables to make them accessible in the view
    extract($variables);

    // Path to view file
    $filePath = __DIR__ . '/' . $viewFile;

    if (file_exists($filePath)) {
        // Include the view file
        include $filePath;
    } else {
        // Return 404 if file not found
        http_response_code(404);
        echo "View not found: $viewFile";
    }
}

/**
 * Serve a static file
 */
function serveStaticFile($filename, $contentType) {
    // Path to file
    $filePath = __DIR__ . '/' . $filename;

    if (file_exists($filePath)) {
        // Set content type
        header("Content-Type: $contentType");

        // Set caching headers
        $lastModified = filemtime($filePath);
        $etag = md5_file($filePath);

        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        header("Etag: $etag");
        header("Cache-Control: max-age=3600");

        // Check if the file has been modified
        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $lastModified ||
            isset($_SERVER['HTTP_IF_NONE_MATCH']) && trim($_SERVER['HTTP_IF_NONE_MATCH']) === $etag) {
            // File not modified
            http_response_code(304);
        } else {
            // Read and output the file content
            readfile($filePath);
        }
    } else {
        // Return 404 if file not found
        http_response_code(404);
        echo "$filename not found";
    }
}
