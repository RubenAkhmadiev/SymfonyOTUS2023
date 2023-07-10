<?php

namespace App\Backoffice\View;

class Table
{
    protected array $header = [];
    protected array $items  = [];
    protected int $page;
    protected ?int $prevPage;
    protected ?int $nextPage;
    protected int $limit;

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

    public function setPage(int $page, bool $hasMore, int $limit): self
    {
        $this->page = $page;
        $this->nextPage = $hasMore ? $page + 1 : null;
        $this->prevPage = $page > 0 ? $page - 1 : null;
        $this->limit = $limit;

        return $this;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getPrevPage(): ?int
    {
        return $this->prevPage;
    }

    public function getNextPage(): ?int
    {
        return $this->nextPage;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}
