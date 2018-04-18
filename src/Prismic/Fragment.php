<?php

namespace Gwsn\Gwsn\Prismic\Prismic;

use Gwsn\Prismic\Prismic\Fragment\Block\ImageBlock;
use Gwsn\Prismic\Prismic\Fragment\Block\TextBlock;
use Gwsn\Prismic\Prismic\Fragment\Color;
use Gwsn\Prismic\Prismic\Fragment\Date;
use Gwsn\Prismic\Prismic\Fragment\Embed;
use Gwsn\Prismic\Prismic\Fragment\FragmentInterface;
use Gwsn\Prismic\Prismic\Fragment\GeoPoint;
use Gwsn\Prismic\Prismic\Fragment\Group;
use Gwsn\Prismic\Prismic\Fragment\GroupDoc;
use Gwsn\Prismic\Prismic\Fragment\SliceZone;
use Gwsn\Prismic\Prismic\Fragment\Image;
use Gwsn\Prismic\Prismic\Fragment\ImageView;
use Gwsn\Prismic\Prismic\Fragment\Link\DocumentLink;
use Gwsn\Prismic\Prismic\Fragment\Link\FileLink;
use Gwsn\Prismic\Prismic\Fragment\Link\ImageLink;
use Gwsn\Prismic\Prismic\Fragment\Link\LinkInterface;
use Gwsn\Prismic\Prismic\Fragment\Link\WebLink;
use Gwsn\Prismic\Prismic\Fragment\Number;
use Gwsn\Prismic\Prismic\Fragment\Span\HyperlinkSpan;
use Gwsn\Prismic\Prismic\Fragment\StructuredText;
use Gwsn\Prismic\Prismic\Fragment\Text;
use Gwsn\Prismic\Prismic\Fragment\Timestamp;

/**
 * A parent class for all classes having fragments: Document, DocumentLink, GroupDoc
 * @package Gwsn\Prismic\Prismic
 */
class Fragment {

    //! array all the fragments in the document (please use instance methods to get information that is in there)
    private $fragments;

    //! @param array $fragments
    function __construct(array $fragments) {
        $this->fragments = $fragments;
    }

    /**
     * Returns the linked documents, from this document
     *
     * @return string the linked documents, from this document
     */
    public function getLinkedDocuments()
    {
        $result = array();
        foreach ($this->fragments as $key => $fragment) {
            if ($fragment instanceof DocumentLink) {
                array_push($result, $fragment);
            }
            if ($fragment instanceof Group) {
                /** @var GroupDoc $groupDoc */
                foreach ($fragment->getArray() as $groupDoc) {
                    $result = array_merge($result, $groupDoc->getLinkedDocuments());
                }
            }
            if ($fragment instanceof StructuredText) {
                foreach ($fragment->getBlocks() as $block) {
                    if ($block instanceof TextBlock) {
                        foreach ($block->getSpans() as $span) {
                            if ($span instanceof HyperlinkSpan) {
                                if ($span->getLink() instanceof DocumentLink) {
                                    array_push($result, $span->getLink());
                                }
                            }
                        }
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Returns the list of fragments of the document. You shouldn't have a reason to use this,
     * as you can access fragments with get functions.
     *
     * @return array the list of fragments in the document.
     */
    public function getFragments()
    {
        return $this->fragments;
    }

    /**
     * Accesses a fragment of the document. For instance, if the document is of the type "product"
     * and the name of the fragment is "description", then you can access the fragment like this:
     * document->get('product.description').
     *
     * If you prefer a more type-safe access, that only works if the fragment is of the right type,
     * you can use the getStructuredText, getColor, getDate, etc. methods.
     *
     * @param string $field name of the fragment, with the document's type, like "product.description"
     *
     * @return LinkInterface
     */
    public function get($field)
    {
        $single = null;
        if (array_key_exists($field, $this->fragments)) {
            return $this->fragments[$field];
        }
        $multi = $this->getAll($field);
        if (!empty($multi)) {
            $single = $multi[0];
        }
        return $single;
    }

    /**
     * Checks if a given fragment exists in the document.
     *
     * @param  string  $field name of the fragment, with the document's type, like "product.description"
     *
     * @return boolean true if the fragment exists, false otherwise
     */
     public function has($field)
     {
         return array_key_exists($field, $this->fragments);
     }

    /**
     * Returns all fragments of the name given
     *
     * \deprecated deprecated as it was meant to be used with an old concept called "multiples"; prefer to use Group fragments now, that are more powerful.
     *
     * @param  string $field name of the fragments
     *
     * @return array  the list of fragments that exist
     */
    public function getAll($field)
    {
        $result = array();
        foreach ($this->fragments as $key => $value) {
            $groups = array();
            if (preg_match('/^([^\[]+)(\[\d+\])?$/', $key, $groups) == 1) {
                if ($groups[1] == $field) {
                    array_push($result, $value);
                }
            }
        }

        return $result;
    }

    /**
     * For any fragment type that makes sense to return as a text value, returns the text value.
     *
     * Works with: StructuredText, Number, Color, Text and Date fragments. If fragment is of the wrong type
     * or doesn't exist, returns an empty string.
     *
     * @param  string $field name of the fragment, with the document's type, like "product.description"
     *
     * @return string the directly usable string
     */
    public function getText($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof StructuredText) {
            $text = "";
            foreach ($fragment->getBlocks() as $block) {
                if ($block instanceof TextBlock) {
                    $text = $text . $block->getText();
                    $text = $text . "\n";
                }
            }

            return trim($text);
        } elseif (isset($fragment) && $fragment instanceof Number) {
            return $fragment->getValue();
        } elseif (isset($fragment) && $fragment instanceof Color) {
            return $fragment->getHexValue();
        } elseif (isset($fragment) && $fragment instanceof Text) {
            return $fragment->getValue();
        } elseif (isset($fragment) && $fragment instanceof Date) {
            return $fragment->getValue();
        }

        return "";
    }

    /**
     * Returns the string for a Number fragment, potentially matching a given pattern,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param  string $field   name of the fragment, with the document's type, like "product.price"
     * @param  string $pattern with the syntax expected by sprintf; null if not used
     *
     * @return string|null the Number fragment, the string representation if $pattern was set, or null if the fragment is of the wrong type or unset
     */
    public function getNumber($field, $pattern = null)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Number) {
            if (isset($pattern) && isset($fragment)) {
                return $fragment->asText($pattern);
            }
            return $fragment;
        }

        return null;
    }

    /**
     * Returns a GeoPoint fragment or null if the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param  string $field name of the fragment
     *
     * @return GeoPoint Fragment
     */
    public function getGeoPoint($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof GeoPoint) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns a boolean for any type of fragment that extends Gwsn\Prismic\Prismic::Fragment::Text, if the text is
     * either 'yes' or 'true', or null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * This works well with Select fragment for instance, where you set your values to "true" or "false".
     *
     * @param  string  $field name of the fragment, with the document's type, like "product.withchocolate"
     *
     * @return boolean|null the directly usable boolean, or null if the fragment is of the wrong type or unset
     */
    public function getBoolean($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Text) {
            return in_array(strtolower($fragment->getValue()), array(
                'yes',
                'true',
            ));
        }

        return null;
    }

    /**
     * Returns the string for a Date fragment, potentially matching a given pattern,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param  string $field   name of the fragment, with the document's type, like "product.publishedAt"
     * @param  string $pattern with the syntax expected by the date function; null if not used
     *
     * @return string|null the directly usable string, or null if the fragment is of the wrong type or unset
     */
    public function getDate($field, $pattern = null)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Date) {
            if (isset($pattern)) {
                return $fragment->formatted($pattern);
            }
            return $fragment;
        }

        return null;
    }

    /**
     * Returns the usable Timestamp fragment as an object, ready to be manipulated.
     *
     * @param  string $field  name of the fragment, with the document's type, like "product.publishedAt"
     *
     * @return FragmentInterface
     */
    public function getTimestamp($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Timestamp) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns an HTML serialization for any kind of fragment. This is simply a faster way to write
     * $doc->get($field)->asHtml($linkResolver).
     *
     * @param  string                $field        name of the fragment, with the document's type, like "product.description"
     * @param  \Gwsn\Prismic\Prismic\LinkResolver $linkResolver an extension of the Gwsn\Prismic\Prismic::LinkResolver class that you taught how to turn a prismic.io document in to a URL in your application
     *
     * @return string the directly usable HTML code, or null if the fragment is unset
     */
    public function getHtml($field, $linkResolver = null)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && method_exists($fragment, 'asHtml')) {
            return $fragment->asHtml($linkResolver);
        }

        return "";
    }

    /**
     * Returns the usable Image fragment as an object, ready to be manipulated.
     * This function also works on StructuredText fragment, and returns the first image in the fragment.
     *
     * @param  string $field name of the fragment, with the document's type, like "product.picture"
     *
     * @return Image
     */
    public function getImage($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Image) {
            return $fragment;
        } elseif (isset($fragment) && $fragment instanceof StructuredText) {
            foreach ($fragment->getBlocks() as $block) {
                if ($block instanceof ImageBlock) {
                    return new Image($block->getView());
                }
            }
        }

        return null;
    }

    /**
     * Returns all the usable Image fragments in the given StructuredText fragment, ready to be manipulated.
     * This function also works on Image fragments, but only was useful in that case before Group fragments existed.
     *
     * @param string $field name of the fragment, with the document's type, like "product.picture"
     *
     * @return array an array of all the Gwsn\Prismic\Prismic::Fragment::Image objects found
     */
    public function getAllImages($field)
    {
        $fragments = $this->getAll($field);
        $images = array();
        foreach ($fragments as $fragment) {
            if (isset($fragment) && $fragment instanceof Image) {
                array_push($images, $fragment);
            } elseif (isset($fragment) && $fragment instanceof StructuredText) {
                foreach ($fragment->getBlocks() as $block) {
                    if ($block instanceof ImageBlock) {
                        array_push($images, new Image($block->getView()));
                    }
                }
            }
        }

        return $images;
    }

    /**
     * Returns the proper view of the given Image fragment, ready to be manipulated.
     * This function also works on StructuredText fragments, to return the first Image, if the view is set to "main".
     *
     * @param string $field name of the fragment, with the document's type, like "product.picture"
     * @param string $view  name of the view, like "small"
     *
     * @return ImageView
     */
    public function getImageView($field, $view = null)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Image) {
            return $fragment->getView($view);
        } elseif (isset($fragment) && $fragment instanceof StructuredText && $view == 'main') {
            $maybeImage = $this->getImage($field);
            if (isset($maybeImage)) {
                return $maybeImage->getMain();
            }
        }

        return null;
    }

    /**
     * Returns the StructuredText fragment as a manipulable object,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param string $field name of the fragment, with the document's type, like "product.description"
     *
     * @return FragmentInterface
     */
    public function getStructuredText($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof StructuredText) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns the Link fragment as a manipulable object,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param string  $field name of the fragment, with the document's type, like "product.gallery"
     *
     * @return FragmentInterface
     */
    public function getLink($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && (
                $fragment instanceof LinkInterface ||
                $fragment instanceof DocumentLink ||
                $fragment instanceof WebLink ||
                $fragment instanceof ImageLink
            )) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns the Group fragment as a manipulable object,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     * This is the point of entry to then loop or search through the elements inside the Group fragment.
     *
     * @param  string $field name of the fragment, with the document's type, like "product.gallery"
     *
     * @return FragmentInterface
     */
    public function getGroup($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Group) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns the SliceZone fragment as a manipulable object,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     * This is the point of entry to then loop or search through the elements inside the Group fragment.
     *
     * @param  string $field name of the fragment, with the document's type, like "product.gallery"
     *
     * @return FragmentInterface
     */
    public function getSliceZone($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof SliceZone) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns the Embed fragment as a manipulable object,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param  string $field name of the fragment, with the document's type, like "product.video"
     *
     * @return FragmentInterface
     */
    public function getEmbed($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Embed) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns the Color fragment as a manipulable object,
     * and null of the fragment is of the wrong type, or if it doesn't exist.
     *
     * @param string $field name of the fragment, with the document's type, like "product.video"
     *
     * @return FragmentInterface
     */
    public function getColor($field)
    {
        $fragment = $this->get($field);
        if (isset($fragment) && $fragment instanceof Color) {
            return $fragment;
        }

        return null;
    }

    /**
     * Returns an HTML serialization for the whole document at once, by serializing all fragments in order.
     *
     * This is a basic serialization, if you want to template your document better, you will need to serialize
     * at the fragment level; for instance: $doc->get('product.description')->asHtml($linkResolver);
     *
     * @param  LinkInterface
     *
     * @return string the directly usable HTML code
     */
    public function asHtml($linkResolver = null)
    {
        $html = null;
        foreach ($this->fragments as $field => $v) {
            $html = $html . '<section data-field="' . $field . '">' .
                    $this->getHtml($field, $linkResolver) . '</section>';
        };

        return $html;
    }

    /**
     * Parses a given fragment. For internal usage.
     *
     * @param   $json the json bit retrieved from the API that represents any fragment.
     *
     * @return FragmentInterface
     */
    public static function parseFragment(Response $json)
    {
        if (is_object($json) && property_exists($json, "type")) {
            if ($json->type === "Image") {
                $data = $json->value;
                $views = array();
                foreach ($json->value->views as $key => $jsonView) {
                    $views[$key] = ImageView::parse($jsonView);
                }
                $mainView = ImageView::parse($data->main, $views);

                return new Image($mainView, $views);
            }

            if ($json->type === "Color") {
                return new Color($json->value);
            }

            if ($json->type === "GeoPoint") {
                return new GeoPoint($json->value->latitude, $json->value->longitude);
            }

            if ($json->type === "Number") {
                return new Number($json->value);
            }

            if ($json->type === "Date") {
                return new Date($json->value);
            }

            if ($json->type === "Timestamp") {
                return new Timestamp($json->value);
            }

            if ($json->type === "Text") {
                return new Text($json->value);
            }

            if ($json->type === "Select") {
                return new Text($json->value);
            }

            if ($json->type === "Embed") {
                return Embed::parse($json->value);
            }

            if ($json->type === "Link.web") {
                return WebLink::parse($json->value);
            }

            if ($json->type === "Link.document") {
                return DocumentLink::parse($json->value);
            }

            if ($json->type === "Link.file") {
                return FileLink::parse($json->value);
            }

            if ($json->type === "Link.image") {
                return ImageLink::parse($json->value);
            }

            if ($json->type === "StructuredText") {
                return StructuredText::parse($json->value);
            }

            if ($json->type === "Group") {
                return Group::parse($json->value);
            }

            if ($json->type === "SliceZone") {
                return SliceZone::parse($json->value);
            }

            return null;
        }
    }

    /**
     * Parse fragments from a json document. For internal usage.
     *
     * @param array $json
     *
     * @return array
     */
    public static function parseFragments($json)
    {
        $fragments = array();
        foreach ($json as $type => $fields) {
            foreach ($fields as $key => $value) {
                if (is_array($value)) {
                    for ($i = 0; $i < count($value); $i++) {
                        $f = self::parseFragment($value[$i]);
                        if (isset($f)) {
                            $fragments[$type . '.' . $key . '[' . $i . ']'] = $f;
                        }
                    }
                }
                $fragment = self::parseFragment($value);

                if (isset($fragment)) {
                    $fragments[$type . "." . $key] = $fragment;
                }
            }
        }
        return $fragments;
    }

}
