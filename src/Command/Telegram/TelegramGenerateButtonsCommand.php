<?php

namespace App\Command\Telegram;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TelegramGenerateButtonsCommand extends Command
{
    public const TELEGRAM_GENERATE_BUTTONS_COMMAND_NAME = 'telegram:generate-buttons';

    /** @var string */
    protected $telegramUrl;


    public function __construct(string $telegramToken)
    {
        $this->telegramUrl = "https://api.telegram.org/bot$telegramToken";

        parent::__construct();
    }

    public static function getCommandName(): string
    {
        return 'telegram:generate-buttons';
    }

    protected function configure(): void
    {
        $this->setName(self::TELEGRAM_GENERATE_BUTTONS_COMMAND_NAME);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->write($this->telegramUrl);
        $content = file_get_contents("https://api.telegram.org/bot6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0/getUpdates");
//        $chat_id = -969459596;
//        $chat_id = curl -X POST "https://api.telegram.org/bot6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0/sendMessage" -d "chat_id=-6177117193&text=telegram bot here!";

        $keyboard = json_encode([
        'inline_keyboard' => [
                [
                    [
                        'text' => 'Купить',
                        'url' => 'https://core.telegram.org/bots/webapps',
                        'callback_data' => 'test_2',
                    ],

                    [
                        'text' => 'Заказать',
                        'callback_data' => 'test_2',
                    ],
                ],
                [
                    [
                        'text' => 'Купить',
                        'url' => 'https://core.telegram.org/bots/webapps',
                        'callback_data' => 'test_2',
                    ],
                ],
                [
                    [
                        'text' => 'Купить',
                        'url' => 'https://core.telegram.org/bots/webapps',
                        'callback_data' => 'test_2',
                    ],

                    [
                        'text' => 'Заказать',
                        'callback_data' => 'test_2',
                    ],
                ]
           ]
        ]);

        $query = [
            'chat_id' => 833499252,
            'text' => 'telegram bot writed',
            'parse_mod' => "html",
            'reply_markup' => $keyboard
        ];

        $ch = curl_init("$this->telegramUrl/sendMessage?" .  http_build_query($query));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $content = curl_exec($ch);
        curl_close($ch);

//        $textMessage = "Telegram delivery";
//        $textMessage = urlencode($textMessage);

//        $urlQuery = "https://api.telegram.org/bot". $token ."/sendMessage?chat_id=". $chat_id ."&text=" . $textMessage;

//        $content = file_get_contents($urlQuery);

//        $content = file_get_contents('https://api.telegram.org/bot6177117193:AAFvjXl4Y4e2NAz9lLPpUmgbc2hCJhibWJ0/getUpdates');
        $output->write($content);
        return 0;
    }

    protected function getMessage()
    {
//        $content = file_get_contents("$this->telegramUrl/getUpdates");

    }

    function curl($url, $data = [], $method = 'GET', $options = [])
    {
        $default_options = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
        ];

        if ($method === 'GET') {
            $url .= (strpos($url, '?') === false) ? '?' : '&';
            $url .= http_build_query($data);
        }
        if ($method === 'POST') {
            $options[CURLOPT_POSTFIELDS] = http_build_query($data);
        }
        if ($method === 'JSON') {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
            $options[CURLOPT_HTTPHEADER][] = 'Content-Type:application/json';
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, array_replace($default_options, $options));

        $result = curl_exec($ch);
        if ($result === false) {
            throw new ErrorException("Curl error: ".curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);
        return $result;
    }
}
