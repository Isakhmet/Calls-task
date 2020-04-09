<?php

namespace App\Classes;


use App\Request;
use App\Webhook;
use GuzzleHttp\Client;

class WebhookSubscribe
{
    public function subscribe()
    {
        $client = new Client(
            [
                'base_uri' => config('webhook.url'),
            ]
        );
        //dd(config('webhook.url'), config('webhook.my_url'));
        $data['user_name']            = config('webhook.user');
        $data['api_key']              = config('webhook.key');
        $data['action']               = config('webhook.actions.subscribe');
        $data['hooks']['call.start']  = config('webhook.my_url');
        $data['hooks']['call.answer'] = config('webhook.my_url');
        $data['hooks']['call.finish'] = config('webhook.my_url');

        try {
            $response = $client->request(
                'POST',
                '',
                [
                    'body'    => json_encode($data, \JSON_UNESCAPED_UNICODE),
                    'headers' => [
                        'Content-Type'   => 'application/json'
                    ],
                ]

            );

            $responseRaw = $response->getBody()
                                    ->getContents()
            ;

            Request::create(
                [
                    'requests' => json_encode(['results' => $responseRaw]),
                ]
            );

            Webhook::where('service_names', 'subscribe')
                   ->update(
                       [
                           'done' => true,
                       ]
                   )
            ;
        } catch (\Exception $exception) {
            Request::create(
                [
                    'requests' => json_encode(
                        [
                            'message' => $exception->getMessage(),
                            'line'    => $exception->getLine(),
                            'code'    => $exception->getCode(),
                        ]
                    ),
                ]
            );
        }
    }
}
