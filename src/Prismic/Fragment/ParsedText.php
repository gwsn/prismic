<?php
/**
 * This file is part of the Prismic PHP SDK
 *
 * Copyright 2013 Zengularity (http://www.zengularity.com).
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gwsn\Prismic\Prismic\Fragment;

/**
 * This class embodies a text that was parsed (this is for internal use).
 */
class ParsedText
{
    /**
     * @var string  the raw text
     */
    private $text;
    /**
     * @var array   the array of \Gwsn\Prismic\Prismic\Fragment\Span\SpanInterface objects
     */
    private $spans;

    /**
     * Constructs an parsed text.
     *
     * @param string   $text    the raw text
     * @param array    $spans   the array of \Gwsn\Prismic\Prismic\Fragment\Span\SpanInterface objects
     */
    public function __construct($text, $spans)
    {
        $this->text = $text;
        $this->spans = $spans;
    }

    /**
     * Returns the raw text
     *
     * 
     *
     * @return string the raw text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Returns the array of \Gwsn\Prismic\Prismic\Fragment\Span\SpanInterface objects
     *
     * 
     *
     * @return array the array of \Gwsn\Prismic\Prismic\Fragment\Span\SpanInterface objects
     */
    public function getSpans()
    {
        return $this->spans;
    }
}
