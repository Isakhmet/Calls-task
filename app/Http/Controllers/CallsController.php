<?php

namespace App\Http\Controllers;

use App\CallReport;
use App\Classes\WebhookSubscribe;
use App\Webhook;
use Illuminate\Http\Request;

class CallsController extends Controller
{
    public function webhook(Request $request)
    {
        $data = $request->toArray();

        \App\Request::create(
            [
                'requests' => json_encode($data),
            ]
        );

        $config = config('webhook.call_events');
        $event  = $config[$data['webhook']['action']];

        CallReport::create(
            [
                'call_event' => $event,
                'event'      => json_encode($data['event'], JSON_UNESCAPED_UNICODE),
                'request'    => json_encode($data, JSON_UNESCAPED_UNICODE),
            ]
        );
    }

    public function subscribe()
    {
        $subscribe = Webhook::where('service_names', 'subscribe')
                            ->first(['done'])
        ;

        $result = ['success' => true];

        if ($subscribe && !$subscribe->done) {
            (new WebhookSubscribe())->subscribe();
            $result['success'] = true;
            $result['done']    = true;
        } else {
            $result['success'] = false;
        }

        return $result;
    }

    public function getCalls()
    {
        $results = [];
        $columns = config('webhook.reports.columns');
        $headers = array_values($columns);
        $keys    = array_keys($columns);

        $calls = CallReport::where('call_event', 'start')
                           ->get(['event->start_time as start_time', 'event'])
                           ->toArray()
        ;

        foreach ($calls as $callNumber => $call) {
            $startEvent = json_decode($call['event'], true);

            $results[$callNumber]['start_time']    = gmdate("d-m-Y H:i:s", $call['start_time']);
            $results[$callNumber]['client_number'] = $startEvent['client_number'];
            $results[$callNumber]['direction']     = config('webhook.reports.direction')[$startEvent['direction']];

            $rows = CallReport
                ::where('event->start_time', $call['start_time'])
                ->where('call_event', 'finish')
                ->get()
            ;

            if ($rows->count() > 0) {
                $rows = $rows->toArray();

                foreach ($rows as $key => $row) {
                    $event = json_decode($row['event'], true);

                    $answer                           = $event['answer_time'];
                    $upload                           = $event['upload_time'];
                    $results[$callNumber]['duration'] = gmdate("H:i:s", $upload - $answer);
                    $results[$callNumber]['answer']   = 'да';
                    $results[$callNumber]['link']     = $event['recording'];
                }
            } else {
                $results[$callNumber]['duration'] = gmdate("H:i:s", 0);
                $results[$callNumber]['answer']   = 'нет';
                $results[$callNumber]['link']     = '';
            }
        }

        $response['headers'] = $headers;
        $response['data']    = $results;

        return $response;
    }
}
