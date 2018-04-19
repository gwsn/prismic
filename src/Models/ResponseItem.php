<?php
namespace Gwsn\Prismic\Models;

use Gwsn\Transformer\Mapping\MappingInterface;
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
     * @var MappingInterface $items
     */
    public $responseItemHandler = null;

    public function __construct(MappingInterface $responseItemHandler)
    {
        $this->responseItemHandler = $responseItemHandler;
    }

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
        $this->items = Transformer::run($items, $this->responseItemHandler, $this->responseItemHandler);
    }


}
