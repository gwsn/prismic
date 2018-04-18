<?php

namespace Gwsn\Prismic\Models;

use Gwsn\Prismic\Models\Dom\RichText;

/**
 * A parent class for all classes having fragments: Document, DocumentLink, GroupDoc
 * @package Gwsn\Prismic\Models
 */
class Fragment {

    /** @var array $fragments */
    private $fragments;

    /**
     * Fragment constructor.
     *
     * @param array $fragments
     */
    function __construct(array $fragments) {
        $this->fragments = $fragments;
    }

    /**
     * Parse fragments from a json document. For internal usage.
     *
     * @param array $rawData
     *
     * @return array
     */
    public static function parseFragments(array $rawData = [])
    {
        $fragments = [];
        foreach ($rawData as $type => $value) {
            // Skipp Custom values
            if(!is_array($value)) continue;

            $fragments[] = RichText::asHtml($value);
        }

        return $fragments;
    }

}
