<?php

namespace App\Libraries;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class TapPayment
{
    public const KEY = 'sk_test_cGy6DRaObxNfE1KzCnkvStTZ';
    public const URL = 'https://api.tap.company/v2/';

    public const SOURCE_ALL = 'src_all';
    public const SOURCE_CARD = 'src_card';
    public const SOURCE_BENEFIT = 'src_bh.benefit';
    public const SOURCE_KNET = 'src_kw.knet';

    private static function GET_PAYMENT_SOURCES()
    {
        return [
            static::SOURCE_ALL,
            static::SOURCE_CARD,
            static::SOURCE_BENEFIT,
            static::SOURCE_KNET,
        ];
    }

    public static function createCharge($currency, $customerName, $customerPhone, $redirectUrl, $amount, $paymentSource = null)
    {
        if (!in_array($paymentSource, static::GET_PAYMENT_SOURCES())) {
            $paymentSource = static::SOURCE_ALL;
        }

        //create the request data
        $requestData = [
            'currency' => $currency,
            'customer' => [
              'first_name' => $customerName,
              'last_name' => null,
              'phone' => [
                'country_code' => substr($customerPhone, 0, 4),
                'number' => substr($customerPhone, 4),
              ],
            ],
            'source' => [
              'id' => $paymentSource,
            ],
            'redirect' => [
              'url' => $redirectUrl,
            ],
            'amount' => $amount,
        ];

        $client = new Client([
            'base_uri' => static::getUrl(),
        ]);
        try {

            $response = $client->request('POST', 'charges', [
            'headers' => [
                'Authorization' => 'Bearer '.static::getKey(),
            ],
            'json' => $requestData,
        ]);
        }
        catch (RequestException $ex) {
            $response = $ex->getResponse();

            //log the error
            Log::error($ex->getMessage());
        }

        $result = json_decode($response->getBody());

        return static::formatResult($result);
    }

    public static function getKey()
    {
        return config('app.tap_key') ?? static::KEY;
    }

    public static function getUrl()
    {
        return config('app.tap_url');
    }

    public static function getCharge($chargeId)
    {
        $client = new Client([
            'base_uri' => static::URL,
        ]);

        try {
            $response = $client->request('GET', static::getUrl().'charges/'.$chargeId, [
                'headers' => [
                    'Authorization' => 'Bearer '.static::getKey(),
                ],
            ]);
        } catch (ClientException $ex) {
            $response = $ex->getResponse();

            //log the error
            Log::error($ex->getMessage());
        }

        $result = json_decode($response->getBody());

        return static::formatResult($result);
    }

    private static function formatResult($result)
    {
        $code = $message = $data = null;
        $success = false;

        if ($result) {
            if (isset($result->errors)) {
                $code = $result->errors[0]->code;
                $message = $result->errors[0]->description;
            } else {
                $code = $result->response->code;
                $message = $result->response->message;
                $data = $result;
                $success = true;
            }
        } else {
            $message = 'Unable to get response';
        }

        return compact('code', 'success', 'message', 'data');
    }
}
