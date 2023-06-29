<?php

namespace App\Http\Response;

final class JsonResponse extends \Symfony\Component\HttpFoundation\JsonResponse
{
    public function __construct(mixed $data = null, int $status = 200, array $headers = [], bool $json = false)
    {
        // Unicode не кодируется
        $this->encodingOptions = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
        $this->charset = 'utf-8';

        parent::__construct($data, $status, $headers, $json);
    }
}