<?php

// Load composer
require __DIR__ . '/vendor/autoload.php';

$bot_api_key = '6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0';
$url_api = 'https://api.telegram.org/bot6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0/getUpdates';
$bot_username = 'otus_food_deliverybot';
$hook_url = 'https://your-domain/path/to/hook.php';

try {
    // Create Telegram API object
    $telegram = new Longman\TelegramBot\Telegram($bot_api_key, $bot_username);

    // Set webhook
    $result = $telegram->setWebhook($hook_url);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
    // echo $e->getMessage();
}
