<?php
namespace Gwsn\Prismic\Document;

use Gwsn\Prismic\Models\Response;

interface PrismicDocumentInterface
{

    function getEndpoint():string;

    function setEndpoint(string $apiEndpoint): void;

    function getToken():string;

    function setToken(string $apiToken): void;

    function getType():string;

    function setType(string $type): void;



    function getDocumentByID(string $id):Response;

    function getDocumentByUID(string $uid, string $type):Response;

    function getDocumentByType(string $type, int $limit, int $page, array $order):Response;

    function getDocumentBySlug(string $slug, string $type):Response;

    function getDocumentByTag(string $tag, int $limit, int $page, array $order):Response;

    function getDocument(string $type, string $param, int $limit, int $page, array $order):Response;

    function queryDocuments(array $query, int $limit, int $page, array $order):Response;


    function parseResponse(array $response):Response;

    function validateToken(string $token):bool;

    function validateEndpoint(string $endpoint):bool;

    function validateType(string $type): bool;

    function validateOrder(array $order):bool;

    function validatePage(int $page):bool;

    function validateLimit(int $limit):bool;

}