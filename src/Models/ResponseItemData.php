<?php

namespace Gwsn\Prismic\Models;


use Gwsn\Transformer\Mapping\BaseMapping;

/**
 * Class ResponseItemData
 * @package Gwsn\Prismic\Models
 */
class ResponseItemData extends BaseMapping
{
    /** @var string $id */
    public $guid;

    /** @var string $type */
    public $type;

    /** @var string $slug */
    public $slug;

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

    public function getMapping(): array
    {
        return [
            'guid' => 'id',
            'slug' => ['slugs', 'callbackSlug'],
            'type' => 'type',
            'href' => 'href',
            'tags' => 'tags',
            'slugs' => 'slugs',
            'lang' => 'lang',
            'firstPublicationDate' => ['first_publication_date', 'callbackDateTime'],
            'lastPublicationDate' => ['last_publication_date', 'callbackDateTime'],
            'data' => ['data', 'callbackParsePrismicData'],
        ];
    }

    public function callbackSlug($value, $raw)
    {
        if (is_array($value) && !empty($value)) {
            return array_values($value)[0];
        }
        return '';
    }

    public function callbackDateTime($value, $raw)
    {

        if (!empty($value)) {
            return $value;
        }
        return null;
    }

    public function callbackParsePrismicData($value, $raw)
    {

        if (!empty($value)) {
            return Fragment::parseFragments($value);
        }
        return null;
    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     */
    public function setGuid(string $guid)
    {
        $this->guid = $guid;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getSlugs(): array
    {
        return $this->slugs;
    }

    /**
     * @param array $slugs
     */
    public function setSlugs(array $slugs)
    {
        $this->slugs = $slugs;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getHref(): string
    {
        return $this->href;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    /**
     * @param string $href
     */
    public function setHref(string $href)
    {
        $this->href = $href;
    }

    /**
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return \DateTime
     */
    public function getFirstPublicationDate()
    {
        return $this->firstPublicationDate;
    }

    /**
     * @param \DateTime $firstPublicationDate
     */
    public function setFirstPublicationDate($firstPublicationDate = null)
    {
        $this->firstPublicationDate = $firstPublicationDate;
    }

    /**
     * @return \DateTime
     */
    public function getLastPublicationDate()
    {
        return $this->lastPublicationDate;
    }

    /**
     * @param \DateTime $lastPublicationDate
     */
    public function setLastPublicationDate($lastPublicationDate = null)
    {
        $this->lastPublicationDate = $lastPublicationDate;
    }

    /**
     * @return array
     */
    public function getLinkedDocuments(): array
    {
        return $this->linkedDocuments;
    }

    /**
     * @param array $linkedDocuments
     */
    public function setLinkedDocuments(array $linkedDocuments)
    {
        $this->linkedDocuments = $linkedDocuments;
    }

    /**
     * @return string
     */
    public function getLang(): string
    {
        return $this->lang;
    }

    /**
     * @param string $lang
     */
    public function setLang(string $lang)
    {
        $this->lang = $lang;
    }

    /**
     * @return array
     */
    public function getAlternateLanguages(): array
    {
        return $this->alternateLanguages;
    }

    /**
     * @param array $alternateLanguages
     */
    public function setAlternateLanguages(array $alternateLanguages)
    {
        $this->alternateLanguages = $alternateLanguages;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }


}