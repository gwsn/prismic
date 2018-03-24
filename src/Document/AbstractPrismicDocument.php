<?php
namespace Gwsn\Prismic\Document;

use Gwsn\Prismic\Prismic\ApiWrapper;
use Gwsn\Prismic\Prismic\Predicates\Predicates;
use Prismic\Api;


abstract class AbstractPrismicDocument implements PrismicDocumentInterface
{

    /** @var string $endpoint */
    private $token = 'MC5XclRjMkNFQUFMSHNjYlhs.77-977-9Txw0Te-_vQBq77-977-977-9Pinvv707M--_vWDvv708VO-_ve-_vXfvv73vv70d77-9R--_ve-_vQ';

    /** @var string $endpoint */
    private $endpoint = 'https://elect.prismic.io';

    /** @var string $type */
    private $type = 'blog-post';

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
     * @return array
     */
    function getDocumentByID(string $uid):array
    {
        return $this->getDocument('document.id', $uid);
    }

    /**
     * @param string $type
     * @param string $uid
     * @return array
     */
    function getDocumentByUID(string $uid, string $type = null):array
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
     * @return array
     */
    function getDocumentByType(string $type, int $limit = null, int $page = null, array $order = null):array
    {
        return $this->getDocument('document.type', $type, $limit, $page, $order);
    }

    /**
     * @param string $type
     * @param string $slug
     * @return array
     */
    function getDocumentBySlug(string $slug, string $type = null):array
    {
        return $this->getDocumentByUID($slug, $type);
    }

    /**
     * @param string $tag
     * @param int|null $limit
     * @param int|null $page
     * @param array|null $order
     * @return array
     */
    function getDocumentByTag(string $tag, int $limit = null, int $page = null, array $order = null):array
    {
        return $this->getDocument('document.tags', $tag, $limit, $page, $order);
    }

    /**
     * @param string $type
     * @param string $param
     * @param int|null $limit
     * @param int|null $page
     * @param array|null $order
     * @return array
     */
    function getDocument(string $type, string $param, int $limit = null, int $page = null, array $order = null):array
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

            // full url: https://elect.prismic.io/api/v2/documents/search;
            $url =  '/api/v2/documents/search';

            $response = $api->call("GET", $url, ['q' => Predicates::at($type, $param)]);


        } catch( \RuntimeException $exception) {
            return [];
        }

        if(empty($response['results_size']) || $response['results_size'] === 0) {
            return [];
        }

        $documents = $response['results'];

        return $documents;
    }

    /**
     * @param \Prismic\Api $api
     * @return bool
     */
    function validateApi(Api $api): bool
    {
        return true;
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