<?php

namespace Daf3at\Merchants;

use Daf3at\Merchants\Exceptions\ApiExceptions;
use Unirest\Exception;
use Unirest\Request;
use \Unirest\Request\Body;

class Payments
{
    /**
     * API Base URL
     * @var string
     */
    static $BASE_URL = 'https://api.daf3at.com/v1/merchant/payment/';

    /**
     * @var string[]
     */
    private $authorization = [
        'merchant_key' => '',
        'public_key' => '',
        'secret_key' => '',
    ];

    /**
     * @param string $merchantKey
     * @param string $publicKey
     * @param string $secretKey
     */
    public function __construct(string $merchantKey, string $publicKey, string $secretKey = '')
    {
        $this->authorization['merchant_key'] = $merchantKey;
        $this->authorization['public_key'] = $publicKey;
        $this->authorization['secret_key'] = $secretKey;
    }

    /**
     * @param array $payment
     * @param array $customer
     * @return false|mixed|string
     * @throws ApiExceptions
     * @throws Exception
     */
    public function create(array $payment, array $customer)
    {
        $payment = (object)$payment;
        $customer = (object)$customer;

        $body = [
            "payment" => [
                "amount" => $payment->amount ?? "",
                "email" => $payment->email ?? "",
                "reference" => $payment->reference ?? "",
                "title" => $payment->title ?? "",
                "description" => $payment->description ?? "",
                "currency" => "SDG",
                "callback_url" => $payment->callback_url ?? "",
            ],
            "customer" => [
                "full_name" => $customer->name ?? "",
                "phone" => $customer->phone ?? "",
                "email" => ($customer->email ?? "")
            ]
        ];
        $response = Request::post(Payments::$BASE_URL . 'create', ['Accept' => 'application/json', 'Content-Type' => 'application/json'], Body::json($body), $this->authorization['merchant_key'], $this->authorization['public_key']);
        if ($response->code == 200) {
            return $response->body;
        } else {
            throw new ApiExceptions(json_encode($response->body->message));
        }
    }

    /**
     * @param string $reference
     * @return false|mixed|string
     * @throws ApiExceptions
     */
    public function verify(string $reference)
    {
        $response = Request::get(Payments::$BASE_URL . 'verify', ['x-auth-type' => 'private', 'Content-Type' => 'application/json'],[
            "reference" => $reference
        ], $this->authorization['merchant_key'], $this->authorization['secret_key']);
        if ($response->code == 200) {
            return $response->body;
        } else {
            throw new ApiExceptions(json_encode($response->body->message));
        }
    }


}