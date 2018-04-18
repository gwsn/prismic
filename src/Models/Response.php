<?php

namespace Gwsn\Prismic\Models;

/**
 * Class Response
 * @package Gwsn\Prismic\Models
 */
class Response
{

    /** @var int $page */
    public $page;
    /** @var int $resultsPerPage */
    public $resultsPerPage;
    /** @var int $resultsSize */
    public $resultsSize;
    /** @var int $totalResultsSize */
    public $totalResultsSize;
    /** @var int $totalPages */
    public $totalPages;
    /** @var string $nextPage */
    public $nextPage;
    /** @var string $prevPage */
    public $prevPage;
    /** @var array $results */
    public $results;
    /** @var string $version */
    public $version;
    /** @var string $license */
    public $license;

    /**
     * Constructs a Response object.
     *
     * @param $response
     */
    public function __construct(array $response = [])
    {
        if (!empty($response)) {
            $this->parseResponse($response);
        }
    }

    /**
     * Returns the list of returned documents, which is an array of Document objects.
     *
     * @return array the list of returned documents
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * Returns the page number for this query.
     *
     * @return int the page number for this query
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Returns the requested number of results per page on this query.
     *
     * @return int the requested number of results per page on this query
     */
    public function getResultsPerPage()
    {
        return $this->resultsPerPage;
    }

    /**
     * Returns the size of the current page.
     *
     * @return int the size of the current page
     */
    public function getResultsSize()
    {
        return $this->resultsSize;
    }

    /**
     * Returns the total number of documents, all pages together.
     *
     * @return int the total number of documents, all pages together
     */
    public function getTotalResultsSize()
    {
        return $this->totalResultsSize;
    }

    /**
     * Returns the number of pages for this query.
     *
     * @return int the number of pages for this query
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /**
     * Returns the RESTful URL of the search request for the next page; null otherwise.
     *
     * @return string the RESTful URL of the search request for the next page; null otherwise
     */
    public function getNextPage()
    {
        return $this->nextPage;
    }

    /**
     * Returns the RESTful URL of the search request for the previous page; null otherwise.
     *
     * @return string the RESTful URL of the search request for the previous page; null otherwise
     */
    public function getPrevPage()
    {
        return $this->prevPage;
    }

    /**
     *
     * @param array $response
     * @todo migrate to a transfomer object
     * @return Response
     */
    public function parseResponse(array $response)
    {

        $this->page = (!empty($response['page']) ? intval($response['page']) : 0);
        $this->resultsPerPage = (!empty($response['results_per_page']) ? intval($response['results_per_page']) : 0);
        $this->resultsSize = (!empty($response['results_size']) ? intval($response['results_size']) : 0);
        $this->totalResultsSize = (!empty($response['total_results_size']) ? intval($response['total_results_size']) : 0);
        $this->totalPages = (!empty($response['total_pages']) ? intval($response['total_pages']) : 0);
        $this->nextPage = $response['next_page'];
        $this->prevPage = $response['prev_page'];

        $this->version = $response['version'];
        $this->license = $response['license'];


        $responseItems = new ResponseItem();
        $responseItems->setItems($response['results']);


        $this->results = $responseItems->getItems();

        return $this;
    }


}
