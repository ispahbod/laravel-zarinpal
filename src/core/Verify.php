<?php

namespace Ispahbod\Zarinpal\core;

use GuzzleHttp\Client;

class Verify
{
    /**
     * Merchant ID for Zarinpal
     * @var string
     */
    protected string $merchantId;
    /**
     * Sandbox mode flag
     * @var bool
     */
    protected bool $sandbox = false;
    protected array $response = [];
    protected Client $client;

    const SSL = false;

    /**
     * Constructor for Zarinpal class
     * @param string $merchantId Merchant ID for Zarinpal
     * @param array $data Payment data including 'amount' and 'authority'
     */
    public function __construct(string $merchantId = '', array $data)
    {
        $this->merchantId = $merchantId;
        $this->client = new Client();
        $url = ($this->sandbox ? "https://sandbox.zarinpal.com" : "https://api.zarinpal.com") . "/pg/v4/payment/verify.json";
        if (isset($data['amount']) && isset($data['authority'])) {
            $json = array_filter([
                'merchant_id' => $this->merchantId,
                'amount' => $data['amount'],
                'authority' => $data['authority'],
            ]);
            try {
                $response = $this->client->post($url, [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'cache-control' => 'no-cache',
                        'Accept' => 'application/json'
                    ],
                    'json' => $json,
                    'verify' => false
                ]);
                $statusCode = $response->getStatusCode();
                if ($statusCode == 200) {
                    $body = $response->getBody();
                    $content = $body->getContents();
                    $this->response = json_decode($content, true);
                }
            } catch (\Exception $e) {
                // Handle exceptions if needed
            }
        }
    }

    public function getFee()
    {
        if (isset($this->response['fee'])) {
            return $this->response['fee'];
        }
        return null;
    }

    public function getFeeType()
    {
        if (isset($this->response['fee_type'])) {
            return $this->response['fee_type'];
        }
        return null;
    }

    public function getCardHash()
    {
        if (isset($this->response['card_hash'])) {
            return $this->response['card_hash'];
        }
        return null;
    }

    public function getCardPan()
    {
        if (isset($this->response['card_pan'])) {
            return $this->response['card_pan'];
        }
        return null;
    }

    public function getRefId()
    {
        if (isset($this->response['ref_id'])) {
            return $this->response['ref_id'];
        }
        return null;
    }

    /**
     * Check if the payment is paid successfully
     * @return bool
     */
    public function isPaid()
    {
        if (isset($this->response['code']) && ($this->response['code'] == 100 || $this->response['code'] == 101)) {
            return true;
        }
        return false;
    }


    /**
     * Get the response code
     * @return int|null
     */
    public function getCode()
    {
        if (isset($this->response['code'])) {
            return $this->response['code'];
        }
        return null;
    }

    /**
     * Get the response data
     * @return array
     */
    public function getData()
    {
        return $this->response;
    }
}
