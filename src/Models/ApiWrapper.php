<?php
namespace Gwsn\Prismic\Models;

use function GuzzleHttp\Psr7\build_query;
use Gwsn\HttpRequest\BaseConnector;
use Gwsn\HttpRequest\ResponseInterface;

class ApiWrapper
{

    /** @var string $endpoint */
    private $endpoint;

    /** @var string $token */
    private $token = '';

    /** @var string $masterRef */
    private $masterRef = '';

    /** @var BaseConnector $client */
    private $client;

    public function __construct() {}

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
        $this->endpoint = $endpoint;
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
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getMasterRef(): string
    {
        return $this->masterRef;
    }

    /**
     * @param string $masterRef
     */
    public function setMasterRef(string $masterRef): void
    {
        $this->masterRef = $masterRef;
    }

    /**
     * @return BaseConnector
     */
    public function getClient(): BaseConnector
    {
        return $this->client;
    }

    /**
     * @param BaseConnector $client
     */
    public function setClient(BaseConnector $client): void
    {
        $this->client = $client;
    }


    /**
     * @param $endpoint
     * @param $token
     * @throws \RuntimeException
     */
    public function prepare($endpoint, $token) {

        $this->setEndpoint($endpoint);
        $this->setToken($token);

        if(empty($this->getEndpoint()) || empty($this->getToken())) {
            throw new \RuntimeException('Please provide the correct credentials to connect to the API');
        }

        // Prepare the Call
        $this->setClient(new BaseConnector());

        // Set the Base API URL
        $this->client->setBaseUri($this->getEndpoint());

        // Prepare call
        $this->client->prepareCall(null);

        // Get the master ref
        $response = $this->call("GET", '/api/v2');

        if(!empty($response) && is_array($response)) {
            if(!key_exists('refs', $response) || !key_exists(0, $response['refs'])) {
                throw new \RuntimeException('Cannot fetch the correct master ref form the Prismic API, bad response:'.json_encode($response));
            }
            $ref = $response['refs'][0];

            if(!empty($ref['id']) && !empty($ref['isMasterRef']) && !empty($ref['ref'])) {
                $this->setMasterRef($ref['ref']);
            }

        } else {
            throw new \RuntimeException('Cannot fetch the correct master ref form the Prismic API, bad response:'.json_encode($response));
        }
    }

    public function call(string $method, string $endpoint, array $data = [], array $headers = []) {
        try {


            // Add the master ref and access token to the request
            $endpoint .= (strpos($endpoint, '?') !== false ? '&' : '?');

            if($this->getMasterRef() !== '') {
                $endpoint .= 'ref='.$this->getMasterRef().'&';
            }

            if($this->getToken() !== '') {
                $endpoint .= 'access_token='.$this->getToken();
            }

            if(strtolower($method) === "get" && !empty($data)) {
                $endpoint .= '&'.build_query($data);
            }

            $this->client->execute($method, $endpoint, [], []);

            return $this->client->getResponse();

        } catch(\Exception $e) {
            return $e;
        }
    }

}