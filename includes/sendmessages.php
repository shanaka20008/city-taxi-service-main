<?php
// Include the Infobip dependencies and configurations
use Infobip\Configuration;
use Infobip\Api\SmsApi;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;
use Infobip\Model\SmsAdvancedTextualRequest;

require __DIR__ . '../../vendor/autoload.php'; // Adjust the path if necessary

// Replace these with real API Key and Base URL from Infobip account
$infobipApiKey = '7d1b2823142d530c4e30c529b3948806-1edfd557-60f6-469d-abb0-e01c959ce245'; // Infobip API Key
$infobipBaseUrl = 'https://kqqxwe.api.infobip.com'; // Correct Infobip Base URL (include the region if necessary)

// Function to send SMS using Infobip
function sendSMS($phoneNumber, $messageContent, $infobipBaseUrl, $infobipApiKey) {
    // Create Infobip API configuration
    $configuration = new Configuration(host: $infobipBaseUrl, apiKey: $infobipApiKey);
    $api = new SmsApi(config: $configuration);

    // Prepare the SMS destination and message
    $destination = new SmsDestination(to: $phoneNumber);
    $message = new SmsTextualMessage(destinations: [$destination], text: $messageContent);
    $request = new SmsAdvancedTextualRequest(messages: [$message]);

    try {
        // Send message and get the response
        $response = $api->sendSmsMessage($request);
        echo "<p>Message sent successfully! Response: " . json_encode($response) . "</p>";
    } catch (Exception $e) {
        // Handle errors during SMS sending
        echo "<p>SMS sending failed: " . $e->getMessage() . "</p>";
    }
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phoneNumber = $_POST['phone_number'];
    $messageContent = $_POST['message_content'];

    // Validate phone number and message content
    if (!empty($phoneNumber) && !empty($messageContent)) {
        // Call the function to send SMS
        sendSMS($phoneNumber, $messageContent, $infobipBaseUrl, $infobipApiKey);
    } else {
        echo "<p>Please fill in both the phone number and message fields.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send SMS using Infobip</title>
</head>
<body>
    <h2>Send SMS using Infobip</h2>

    <!-- Form to enter phone number and message -->
    <form action="sendmessages.php" method="POST">
        <label for="phone_number">Phone Number:</label><br>
        <input type="text" id="phone_number" name="phone_number" placeholder="Enter phone number" required><br><br>

        <label for="message_content">Message:</label><br>
        <textarea id="message_content" name="message_content" placeholder="Enter your message" required></textarea><br><br>

        <button type="submit">Send</button>
    </form>
</body>
</html>
