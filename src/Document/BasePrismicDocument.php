<?php
namespace Gwsn\Prismic\Document;

use Gwsn\Transformer\Mapping\MappingInterface;

class BasePrismicDocument extends AbstractPrismicDocument
{

    /**
     * AbstractPrismicDocument constructor.
     *
     * @param MappingInterface $responseItemHandler
     */
    public function __construct(MappingInterface $responseItemHandler = null)
    {
        parent::__construct($responseItemHandler);
    }

}