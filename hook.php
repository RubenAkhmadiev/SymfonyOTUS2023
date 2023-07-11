<?php
// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key = '6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0';
$bot_username = 'otus_food_deliverybot';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Handle telegram webhook request
    $telegram->handle();
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // Silence is golden!
    // log telegram errors
    // echo $e->getMessage();
}
