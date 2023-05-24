<?php

namespace App\Services;

use GuzzleHttp\Client;

class TescoAPI
{
    protected $apiKey;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env('TESCO_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://dev.tescolabs.com/',
        ]);
    }

    public function getProducts($query, $offset = 0, $limit = 50)
    {
        $url = "/grocery/products/?query={$query}&offset={$offset}&limit={$limit}";
        $headers = [
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ];

        try {
            $response = $this->client->get($url, ['headers' => $headers]);
            $data = json_decode($response->getBody(), true);

            $products = [];
            foreach ($data['uk']['ghs']['products']['results'] as $result) {
                $products[] = [
                    'id' => $result['id'],
                    'text' => $result['name'],
                    'price' => $result['price'],
                ];
            }

            return $products;
        } catch (\Exception $e) {
            dd($e);
        }
    }
}

