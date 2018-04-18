<?php
namespace Gwsn\Prismic\Document;

use Gwsn\Prismic\Models\ApiWrapper;
use Gwsn\Prismic\Models\Response;
use Gwsn\Prismic\Models\Predicates\Predicates;



abstract class AbstractPrismicDocument implements PrismicDocumentInterface
{

    /** @var string $endpoint */
    private $token = '';

    /** @var string $endpoint */
    private $endpoint = '';

    /** @var string $type */
    private $type = 'blog-post';

    /**
     * AbstractPrismicDocument constructor.
     */
    public function __construct()
    {
        // Void
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
    function getDocumentByID(string $uid):Response
    {
        return $this->getDocument('document.id', $uid);
    }

    /**
     * @param string $type
     * @param string $uid
     * @return Response
     */
    function getDocumentByUID(string $uid, string $type = null):Response
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
    function getDocumentByType(string $type, int $limit = null, int $page = null, array $order = null):Response
    {
        return $this->getDocument('document.type', $type, $limit, $page, $order);
    }

    /**
     * @param string $type
     * @param string $slug
     * @return Response
     */
    function getDocumentBySlug(string $slug, string $type = null):Response
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
    function getDocumentByTag(string $tag, int $limit = null, int $page = null, array $order = null):Response
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
    function getDocument(string $type, string $param, int $limit = null, int $page = null, array $order = null):Response
    {

        $filtering = [];

        if($limit > 0) {
            $filtering['pageSize'] = $limit;
        }

        if($page >= 0) {
            $filtering['page'] = $page;
        }

        if(is_array($order) && !empty($order)) {
            $filtering['ordering'] = $order;
        }

        try {
            $api = new ApiWrapper();
            $api->prepare($this->getEndpoint(), $this->getToken());

            // full url: https://{repo}.prismic.io/api/v2/documents/search;
            $url =  '/api/v2/documents/search';

            $response = $api->call("GET", $url, ['q' => Predicates::at($type, $param)]);

            return new Response((array) $response);


        } catch( \RuntimeException $exception) {
            return new Response();
        }
    }


    /**
     * @param string $token
     * @return bool
     */
    function validateToken(string $token): bool
    {
        return true;
    }

    /**
     * @param string $endpoint
     * @return bool
     */
    function validateEndpoint(string $endpoint): bool
    {
        return true;
    }

    /**
     * @param string $type
     * @return bool
     */
    function validateType(string $type): bool
    {
        return true;
    }

    /**
     * @param array $order
     * @return bool
     */
    function validateOrder(array $order): bool
    {
        return true;
    }

    /**
     * @param int $page
     * @return bool
     */
    function validatePage(int $page): bool
    {
        return true;
    }

    /**
     * @param int $limit
     * @return bool
     */
    function validateLimit(int $limit): bool
    {
        return true;
    }
}