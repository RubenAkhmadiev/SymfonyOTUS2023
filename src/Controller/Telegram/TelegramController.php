<?php

namespace App\Controller\Telegram;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

#[AsController]
class TelegramController
{

    public function __construct(
        protected string $telegramToken
    )
    {
    }

    #[Route(path: '/telegram', name: 'telegram', methods: ['GET'])]
    public function index(): Response
    {
//        $token = "6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0";
//        $chat_id = -969459596;
//        $chat_id = curl -X POST "https://api.telegram.org/bot6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0/sendMessage" -d "chat_id=-6177117193&text=telegram bot here!";

//        $textMessage = "Telegram delivery";
//        $textMessage = urlencode($textMessage);

//        $urlQuery = "https://api.telegram.org/bot". $token ."/sendMessage?chat_id=". $chat_id ."&text=" . $textMessage;

//        $content = file_get_contents($urlQuery);

//        $content = file_get_contents('https://api.telegram.org/bot6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0/getUpdates');
        return new JsonResponse(['data' => $this->telegramToken], Response::HTTP_OK);
    }
}
