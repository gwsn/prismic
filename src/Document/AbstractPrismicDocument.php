<?php
namespace Gwsn\Prismic\Document;

use Gwsn\Prismic\Models\ApiWrapper;
use Gwsn\Prismic\Models\Response;
use Gwsn\Prismic\Models\Predicates\Predicates;
use Gwsn\Prismic\Models\ResponseItemData;
use Gwsn\Transformer\Mapping\MappingInterface;


abstract class AbstractPrismicDocument implements PrismicDocumentInterface
{

    /** @var string $endpoint */
    private $token = '';

    /** @var string $endpoint */
    private $endpoint = '';

    /** @var string $type */
    private $type = 'blog-post';

    /** @var MappingInterface  responseItemHandler */
    private $responseItemHandler;

    /**
     * AbstractPrismicDocument constructor.
     *
     * @param MappingInterface $responseItemHandler
     */
    public function __construct(MappingInterface $responseItemHandler = null)
    {
        $this->responseItemHandler = ($responseItemHandler !== null ? $responseItemHandler : new ResponseItemData);
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        if(!$this->validateToken($token)) {
            throw new \InvalidArgumentException("The token provide is not valid token");
        }
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint(string $endpoint): void
    {
        if(!$this->validateEndpoint($endpoint)) {
            throw new \InvalidArgumentException("The endpoint provide is not valid endpoint");
        }
        $this->endpoint = $endpoint;
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
    public function setType(string $type): void
    {
        if(!$this->validateType($type)) {
            throw new \InvalidArgumentException("The type provide is not valid type");
        }
        $this->type = $type;
    }


    /**
     * @param string $uid
     * @return Response
     */
    public function getDocumentByID(string $uid):Response
    {
        return $this->getDocument('document.id', $uid);
    }

    /**
     * @param string $type
     * @param string $uid
     * @return Response
     */
    public function getDocumentByUID(string $uid, string $type = null):Response
    {
        if($type === null && $this->getType() === null) {
            throw new \InvalidArgumentException('The type provide is not valid type, also the default type is not set or not valid');
        }

        $type = ($this->getType() !== null ? $this->getType() : '');

        return $this->getDocument("my.{$type}.uid", $uid);
    }

    /**
     * @param string $type
     * @param int|null $limit
     * @param int|null $page
     * @param array|null $order
     * @return Response
     */
    public function getDocumentByType(string $type, int $limit = 25, int $page = 1, array $order = []):Response
    {
        return $this->getDocument('document.type', $type, $limit, $page, $order);
    }

    /**
     * @param string $type
     * @param string $slug
     * @return Response
     */
    public function getDocumentBySlug(string $slug, string $type = null):Response
    {
        return $this->getDocumentByUID($slug, $type);
    }

    /**
     * @param string $tag
     * @param int|null $limit
     * @param int|null $page
     * @param array|null $order
     * @return Response
     */
    public function getDocumentByTag(string $tag, int $limit = 25, int $page = 1, array $order = []):Response
    {
        return $this->getDocument('document.tags', $tag, $limit, $page, $order);
    }

    /**
     * @param string $type
     * @param string $param
     * @param int|null $limit
     * @param int|null $page
     * @param array|null $order
     * @return Response
     */
    public function getDocument(string $type, string $param, int $limit = 25, int $page = 1, array $order = []):Response
    {

        $filtering = $this->buildFilters($limit, $page, $order);

        try {
            $api = new ApiWrapper();

            $api->prepare($this->getEndpoint(), $this->getToken());

            // full url: https://{repo}.prismic.io/api/v2/documents/search;
            $url =  '/api/v2/documents/search';
            $filtering['q'] = Predicates::at($type, $param);

            $response = $api->call("GET", $url, $filtering);

            return $this->parseResponse($response);


        } catch( \RuntimeException $exception) {
            return new Response();
        }
    }

    /**
     *
     * @param array $query
     * @param int $limit
     * @param int $page
     * @param array $order
     *
     * @return Response
     */
    public function queryDocuments(array $query, int $limit = 25, int $page = 1, array $order = []):Response
    {
        if(empty($query)) {
            throw new \InvalidArgumentException('Query is not valid');
        }
        $filtering = $this->buildFilters($limit, $page, $order);
        $queryData = [];

        try {
            $api = new ApiWrapper();
            $api->prepare($this->getEndpoint(), $this->getToken());

            // full url: https://{repo}.prismic.io/api/v2/documents/search;
            $url =  '/api/v2/documents/search';
            foreach($query as $k => $q) {
                $queryData[] = Predicates::at($q['type'], $q['value']);
            }

            $filtering['q'] = $queryData;

            $response = $api->call("GET", $url, $filtering);

            return $this->parseResponse($response);


        } catch( \RuntimeException $exception) {
            return new Response();
        }
    }

    /**
     * @param int $limit
     * @param int $page
     * @param array $order
     * @return array
     */
    private function buildFilters(int $limit = 25, int $page = 1, array $order = []):array {
        $filtering = [];

        if($limit !== null && intval($limit) > 0) {
            $filtering['pageSize'] = $limit;
        }

        if($page !== null && intval($page) >= 0) {
            $filtering['page'] = $page;
        }

        if(is_array($order) && !empty($order)) {
            $filtering['ordering'] = $this->buildOrdering($order);
        }

        return $filtering;
    }

    /**
     * @param array $ordering
     *
     * @return string
     */
    private function buildOrdering(array $ordering = []):string {
        return '['.implode(',', $ordering).']';
    }

    /**
     * @param $response
     * @return Response
     */
    public function parseResponse(array $response = []):Response
    {
        return new Response((array) $response, $this->responseItemHandler);
    }


    /**
     * @param string $token
     * @return bool
     */
    public function validateToken(string $token): bool
    {
        return true;
    }

    /**
     * @param string $endpoint
     * @return bool
     */
    public function validateEndpoint(string $endpoint): bool
    {
        return true;
    }

    /**
     * @param string $type
     * @return bool
     */
    public function validateType(string $type): bool
    {
        return true;
    }

    /**
     * @param array $order
     * @return bool
     */
    public function validateOrder(array $order): bool
    {
        return true;
    }

    /**
     * @param int $page
     * @return bool
     */
    public function validatePage(int $page): bool
    {
        return true;
    }

    /**
     * @param int $limit
     * @return bool
     */
    public function validateLimit(int $limit): bool
    {
        return true;
    }
}