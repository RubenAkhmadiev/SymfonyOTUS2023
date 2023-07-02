<?php

namespace App\Backoffice\View;

class Table
{
    protected array $header = [];
    protected array $items  = [];

    public function setHeader(string $title): self
    {
        $this->header[] = [
            'title' => $title,
        ];

        return $this;
    }

    public function setData(array $items): self
    {
        $this->items = $items;

        return $this;
    }

    public function getHeader(): array
    {
        return $this->header;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
