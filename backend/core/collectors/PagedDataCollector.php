<?php

namespace core\collectors;

use yii\db\Query;

abstract class PagedDataCollector extends AbstractDataCollector
{
    protected const MAX_COUNT_PER_PAGE = 10;

    protected Query $query;

    public function __construct()
    {
        $this->query = new Query();
    }

    protected function getPageCount(): int
    {
        return intval(ceil($this->query->count() / static::MAX_COUNT_PER_PAGE));
    }

    protected function setPage(int $page)
    {
        $this->query->offset(($page - 1) * static::MAX_COUNT_PER_PAGE);
        $this->query->limit(static::MAX_COUNT_PER_PAGE);
    }
}
