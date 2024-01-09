<?php

namespace Ispahbod\Zarinpal\core;

class Response
{
    protected array $response;

    public function __construct($response)
    {
        $this->response = $response;
    }

    public function getValidations()
    {
        if (isset($this->response['errors']['validations'])) {
            return $this->response['errors']['validations'];
        }
        return null;
    }

    public function getPaymentUrl()
    {
        if (isset($this->response['data']['authority'])) {
            return "https://www.zarinpal.com/pg/StartPay/" . $this->response['data']['authority'];
        }
        return null;
    }

    public function getAuthority()
    {
        if (isset($this->response['data']['authority'])) {
            return $this->response['data']['authority'];
        }
        return null;
    }

    public function getFeeType()
    {
        if (isset($this->response['data']['fee_type'])) {
            return $this->response['data']['fee_type'];
        }
        return null;
    }

    public function getFee()
    {
        if (isset($this->response['data']['fee'])) {
            return $this->response['data']['fee'];
        }
        return null;
    }

    public function getData()
    {
        if (isset($this->response['data'])) {
            return $this->response['data'];
        }
        return null;
    }

    public function getCode()
    {
        if (isset($this->response['errors']['code'])) {
            return $this->response['errors']['code'];
        } elseif (isset($this->response['data']['code'])) {
            return $this->response['data']['code'];
        }
        return null;
    }

    public function getMessage()
    {
        if (isset($this->response['errors']['message'])) {
            return $this->response['errors']['message'];
        } elseif (isset($this->response['data']['message'])) {
            return $this->response['data']['message'];
        }
        return null;
    }

    public function isSuccessful()
    {
        if (isset($this->response['errors']['code'])) {
            return false;
        }
        return true;
    }
}
