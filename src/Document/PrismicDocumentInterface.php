<?php
namespace Gwsn\Prismic\Document;


interface PrismicDocumentInterface
{

    function getEndpoint():string;

    function setEndpoint(string $apiEndpoint): void;

    function getToken():string;

    function setToken(string $apiToken): void;

    function getType():string;

    function setType(string $type): void;



    function getDocumentByID(string $id):array;

    function getDocumentByUID(string $uid, string $type):array;

    function getDocumentByType(string $type, int $limit, int $page, array $order):array;

    function getDocumentBySlug(string $slug, string $type):array;

    function getDocumentByTag(string $tag, int $limit, int $page, array $order):array;

    function getDocument(string $type, string $param, int $limit, int $page, array $order):array;


    function validateToken(string $token):bool;

    function validateEndpoint(string $endpoint):bool;

    function validateType(string $type): bool;

    function validateOrder(array $order):bool;

    function validatePage(int $page):bool;

    function validateLimit(int $limit):bool;

}