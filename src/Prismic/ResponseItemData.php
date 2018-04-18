<?php

namespace Gwsn\Gwsn\Prismic\Prismic;


use Gwsn\Transformer\Mapping\BaseMapping;

class ResponseItemData extends BaseMapping
{
    /** @var string $id */
    public $guid;

    /** @var string $type */
    public $type;

    /** @var mixed $value */
    public $value;

    /** @var string $href */
    public $href;

    /** @var array $tags */
    public $tags = [];

    /** @var \DateTime $first_publication_date */
    public $firstPublicationDate;

    /** @var \DateTime $last_publication_date */
    public $lastPublicationDate;

    /** @var array $slugs */
    public $slugs = [];

    /** @var array $linked_documents */
    public $linkedDocuments = [];

    /** @var string $lang */
    public $lang;

    /** @var array $alternate_languages */
    public $alternateLanguages = [];

    /** @var array $data */
    public $data;


    public function getMapping():array {
        return [
            'guid' => 'id',
            'slug' => ['slugs', 'callbackSlug'],
            'type' => 'type',
            'href' => 'href',
            'tags' => 'tags',
            'slugs' => 'slugs',
            'lang' => 'lang',
            'firstPublicationDate' => 'first_publication_date',
            'lastPublicationDate' => 'last_publication_date',
            'data' => 'data',
        ];

    }

    public function callbackSlug($value, $raw):array  {
        if(is_array($value) && !empty($value)) {
            array_values($value)[0];
        }
        return [];
    }

}