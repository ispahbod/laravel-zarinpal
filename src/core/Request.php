<?php

namespace Ispahbod\Zarinpal\core;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use InvalidArgumentException;

class Request
{
    protected string $merchantId;
    protected bool $sandbox = false;
    protected string $lang;
    protected Client $client;
    protected int $amountValue;
    protected string $currencyValue = 'IRR';
    protected string $descriptionValue;
    protected string $callbackUrl;
    protected int $orderId;
    protected array $metadata;
    protected string $mobileValue;
    protected string $emailValue;

    /**
     * Constructor for Zarinpal class
     * @param string $merchantId Merchant ID for Zarinpal
     */
    public function __construct(string $merchantId, bool $sandbox = false)
    {
        $this->merchantId = $merchantId;
        $this->sandbox = $sandbox;
        $this->client = new Client(); // Assuming GuzzleHttp client is used
    }

    /**
     * Enable or disable sandbox mode
     * @param bool $sandbox Sandbox mode flag
     * @return $this
     */
    public function sandbox(bool $sandbox = true): Request
    {
        $this->sandbox = $sandbox;
        return $this;
    }

    /**
     * Set the currency for the payment
     * @param string $currency Currency code (Accepted values: 'IRR' or 'IRT')
     * @return $this
     * @throws InvalidArgumentException If an invalid currency code is provided
     */
    public function currency(string $currency): Request
    {
        if ($currency !== 'IRR' && $currency !== 'IRT') {
            throw new InvalidArgumentException("Invalid currency code. Accepted values are 'IRR' or 'IRT'.");
        }
        $this->currencyValue = $currency;
        return $this;
    }

    /**
     * Set the mobile number for the payment
     * @param string $mobile Mobile number
     * @return $this
     */
    public function mobile(string $mobile): Request
    {
        $this->mobileValue = $mobile;
        return $this;
    }

    /**
     * Set the email address for the payment
     * @param string $email Email address
     * @return $this
     */
    public function email(string $email): Request
    {
        $this->emailValue = $email;
        return $this;
    }

    /**
     * Set the order ID for the payment
     * @param int $id Order ID
     * @return $this
     */
    public function order(int $id): Request
    {
        $this->orderId = $id;
        return $this;
    }

    /**
     * Set the description for the payment
     * @param string $description Payment description
     * @return $this
     */
    public function description(string $description): Request
    {
        $this->descriptionValue = $description;
        return $this;
    }

    /**
     * Set metadata for the payment
     * @param array $array Metadata array
     * @return $this
     */
    public function metadata(array $array): Request
    {
        $this->metadata = $array;
        return $this;
    }

    /**
     * Set the callback URL for the payment
     * @param string $url Callback URL
     * @return $this
     */
    public function callback(string $url): Request
    {
        $this->callbackUrl = $url;
        return $this;
    }

    /**
     * Set the amount for the payment
     * @param mixed $amount Payment amount
     * @return $this
     */
    public function amount(int $amount): Request
    {
        $this->amountValue = $amount;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function get(): Response
    {
        $url = ($this->sandbox ? "https://sandbox.zarinpal.com" : "https://api.zarinpal.com") . "/pg/v4/payment/request.json";
        $data = [];
        try {
            $response = $this->client->post($url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'cache-control' => 'no-cache',
                    'Accept' => 'application/json'
                ],
                'json' => array_filter([
                    'merchant_id' => $this->merchantId,
                    'amount' => $this->amountValue,
                    'currency' => $this->currencyValue,
                    'description' => $this->descriptionValue ?? 'empty',
                    'callback_url' => $this->callbackUrl ?? null,
                    'metadata' => $this->metadata ?? null,
                    'mobile' => $this->mobileValue ?? null,
                    'email' => $this->emailValue ?? null,
                    'order_id' => $this->orderId ?? null,
                ]),
                'verify' => false
            ]);
            $statusCode = $response->getStatusCode();
            if ($statusCode == 200) {
                $content = $response->getBody()->getContents();
                $data = json_decode($content, true);
            }
            return new Response($data);
        }catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }
}
