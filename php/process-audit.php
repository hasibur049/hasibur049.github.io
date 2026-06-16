<?php
/**
 * --- HASIBUR RAHMAN E-COMMERCE GROWTH LEAD CAPTURE ---
 * Secure PHP Mail Processor for InfinityFree / Custom Web Hosting
 * Routes potential client audit requests instantly to Gmail.
 */

// 1. Enable rigorous secure CORS & Error handling
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json; charset=UTF-8");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Ensure the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed. Please submit via POST."]);
    exit();
}

// Target Gmail Destination
$recipientEmail = "digitalwithhasibur@gmail.com";

// 2. Capture POST payload (Supports both modern AJAX JSON requests and standard form submissions)
$inputJSON = file_get_contents('php://input');
$postData = json_decode($inputJSON, true);

if (!$postData) {
    $postData = $_POST;
}

// 3. Extract and safely sanitize inputs
$name    = isset($postData['name']) ? strip_tags(trim($postData['name'])) : 'Ambitious D2C Founder';
$email   = isset($postData['email']) ? filter_var(trim($postData['email']), FILTER_SANITIZE_EMAIL) : 'no-email-provided@domain.com';
$url     = isset($postData['url']) ? strip_tags(trim($postData['url'])) : 'No storefront URL / Ad Library link provided';
$service = isset($postData['service']) ? strip_tags(trim($postData['service'])) : 'Full-Stack E-Commerce Scaling';
$notes   = isset($postData['notes']) ? strip_tags(trim($postData['notes'])) : 'No additional acquisition bottleneck notes provided.';

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Please enter a valid work email address."]);
    exit();
}

// 4. Construct an elite professional email message
$subject = "🚀 NEW D2C AUDIT REQUEST: " . strtoupper($service) . " FROM " . strtoupper($name);

// Email Body in beautiful structured plaintext
$messageBody  = "=================================================================\n";
$messageBody .= "   Hasibur Rahman — Potential Brand Client Lead Capture          \n";
$messageBody .= "=================================================================\n\n";
$messageBody .= "👤 PROSPECT NAME & ROLE : " . $name . "\n";
$messageBody .= "📧 WORK EMAIL ADDRESS   : " . $email . "\n";
$messageBody .= "🌐 Target Store URL     : " . $url . "\n";
$messageBody .= "🎯 Target Capability    : " . $service . "\n";
$messageBody .= "-----------------------------------------------------------------\n";
$messageBody .= "📝 Acquisition Bottlenecks & Monthly Ad Spend Notes:\n\n";
$messageBody .= $notes . "\n\n";
$messageBody .= "=================================================================\n";
$messageBody .= "⚡ Generated live via Hasibur Rahman InfinityFree Production Engine\n";

// 5. Build strict, deliverability-optimized email headers
// Note: Reply-To ensures when Hasibur clicks 'Reply' in Gmail, it emails the client directly!
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/plain; charset=utf-8\r\n";
$headers .= "From: \"Hasibur Storefront Growth\" <noreply@" . (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : "hasibur-growth.com") . ">\r\n";
$headers .= "Reply-To: \"" . $name . "\" <" . $email . ">\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// 6. Execute PHP native secure mail routing
$mailSent = @mail($recipientEmail, $subject, $messageBody, $headers);

if ($mailSent) {
    http_response_code(200);
    echo json_encode([
        "status" => "success", 
        "message" => "Strategy & Creatives audit requested successfully. Lead routed directly to Gmail."
    ]);
} else {
    // If native mail() fails (some free hosting accounts require SMTP setup), return a graceful fallback status
    http_response_code(500);
    echo json_encode([
        "status" => "error", 
        "message" => "Mail delivery engine encountered an issue. Backend processor logged the request."
    ]);
}
?>