<?php
namespace Gwsn\Prismic\Models;

use Gwsn\Transformer\Transformer;

/**
 * Class ResponseItem
 * @package Gwsn\Prismic\Models
 */
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
        $this->items = Transformer::run($items, new ResponseItemData(), new ResponseItemData());
    }


}
