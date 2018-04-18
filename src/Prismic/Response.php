<?php

namespace Gwsn\Gwsn\Prismic\Prismic;


/**
 * This class embodies a search request response from the prismic.io API,
 * containing a list of documents, as well as meta-information about this
 * list (mostly about pagination).
 *
 * This is what is returned when you call submit() on a search request, which
 * is the only kind of request except for the API endpoint. So, let's say that
 * when you call prismic.io's API, you get that object a lot.
 *
 * Do remember that requests in prismic.io are paginated by 20 by default.
 */
class Response
{
    /**
     * array the list of returned documents
     * @var array $results
     */
    private $results;
    /**
     * int the page number for this query
     *
     * @var int $page
     */
    private $page;
    /**
     * int the requested number of results per page on this query
     * @var int $resultsPerPage
     */
    private $resultsPerPage;
    /**
     * int the size of the current page
     * @var int $resultsSize
     */
    private $resultsSize;
    /**
     * int the total number of documents, all pages together
     * @var int $totalResultsSize
     */
    private $totalResultsSize;
    /**
     * int the number of pages for this query
     * @var int $totalPages
     */
    private $totalPages;
    /**
     * string the RESTful URL of the search request for the next page; null otherwise
     * @var string $nextPage
     */
    private $nextPage;
    /**
     * string the RESTful URL of the search request for the previous page; null otherwise
     * @var string $prevPage
     */
    private $prevPage;


    /**
     * Constructs a Response object.
     *
     * @param \stdClass $response
     */
    public function __construct(\stdClass $response)
    {
        if(!empty($response)) {
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
     * @param \stdClass $response
     */
    public function parseResponse( \stdClass $response)
    {
        $responseItems = new ResponseItem();
        $responseItems->setItems($response->results);

        $this->results = $responseItems->getItems();
        $this->page = $response->page;
        $this->resultsPerPage = $response->resultsPerPage;
        $this->resultsSize = $response->resultsSize;
        $this->totalResultsSize = $response->totalResultsSize;
        $this->totalPages = $response->totalPages;
        $this->nextPage = $response->nextPage;
        $this->prevPage = $response->prevPage;
    }


}
