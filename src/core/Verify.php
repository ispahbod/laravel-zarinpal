<?php

namespace Ispahbod\Zarinpal\core;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Verify
{
    private string $merchantId;
    private bool $sandbox = false;
    private array $response = [];
    private Client $client;

    /**
     * @throws Exception
     */
    public function __construct(string $merchantId, int $amount, string $authority)
    {
        $this->merchantId = $merchantId;
        $this->client = new Client();
        $url = ($this->sandbox ? "https://sandbox.zarinpal.com" : "https://api.zarinpal.com") . "/pg/v4/payment/verify.json";
        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'Accept' => 'application/json'
                ],
                'json' => array_filter([
                    'merchant_id' => $this->merchantId,
                    'amount' => $amount,
                    'authority' => $authority,
                ]),
                'verify' => false
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $content = $response->getBody()->getContents();
                $this->response = json_decode($content, true);
            }
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function getFee():int|bool
    {
        if (isset($this->response['fee']))
            return $this->response['fee'];
        return false;
    }

    public function getFeeType():string|bool
    {
        if (isset($this->response['fee_type']))
            return $this->response['fee_type'];
        return false;
    }

    public function getCardHash()
    {
        if (isset($this->response['card_hash']))
            return $this->response['card_hash'];
        return false;
    }

    public function getCardPan()
    {
        if (isset($this->response['card_pan']))
            return $this->response['card_pan'];
        return false;
    }

    public function getRefId():int|bool
    {
        if (isset($this->response['ref_id']))
            return $this->response['ref_id'];
        return false;
    }
    public function isPaid():bool
    {
        if (isset($this->response['data']['code']) && ($this->response['data']['code'] == 100 || $this->response['data']['code'] == 101))
            return true;
        return false;
    }
    public function getCode(): int|null
    {
        if (isset($this->response['code']))
            return $this->response['code'];
        return false;
    }
    public function getData():array
    {
        return $this->response;
    }
}
