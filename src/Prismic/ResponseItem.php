<?php

namespace Gwsn\Gwsn\Prismic\Prismic;

use Gwsn\Transformer\Transformer;

class ResponseItem
{
    /**
     * @var array $items
     */
    public $items = null;

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @param array $items
     */
    public function setItems(array $items): void
    {
        $responseItemData = new ResponseItemData();
        $this->items = Transformer::run($items, $responseItemData, $responseItemData);
    }


}
