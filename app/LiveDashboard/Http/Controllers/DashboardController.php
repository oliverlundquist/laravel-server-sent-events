<?php

namespace App\LiveDashboard\Http\Controllers;

use App\LiveDashboard\Formatter\EventStreamDataFormatter;
use App\LiveDashboard\Cache\CustomerMessageHandler;
use App\LiveDashboard\Data\CustomerMessageData;
use App\LiveDashboard\Database\TrackingDataHandler;
use Illuminate\Http\Request;

class DashboardController
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function trackingDataStream()
    {
        // if (is_null($request)) {
        //     return response('complete', 204);
        // }
        new TrackingDataHandler()->purge();

        $callback = function () {
            // force client reconnect after 10 seconds
            while ((microtime(true) - LARAVEL_START) < 10) {
                usleep(300000); // give the browser 300ms to recover between events
                $threeSecAgo  = now()->subSeconds(3);
                $trackingData = (new TrackingDataHandler)->fetchEventsForTenantName('bike-spareparts', $threeSecAgo);
                $message      = count($trackingData)
                                ? new EventStreamDataFormatter(eventData: [json_encode($trackingData)])->render()
                                : new EventStreamDataFormatter(eventName: 'ping', eventData: ['pong'])->render();

                echo $message;
                flush();
            }
        };
        $status  = 200;
        $headers = [
            'X-Accel-Buffering' => 'no',
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache'
        ];
        return response()->stream($callback, $status, $headers);
    }

    public function sendMessageToCustomer(Request $request)
    {
        $data = $request->validate([
            'session_id'   => ['required', 'size:40'],
            'message_type' => ['required', 'in:discount_offer,support_message']
        ]);
        extract($data);

        // store message
        new CustomerMessageHandler()->storeMessage($session_id, new CustomerMessageData(compact('message_type')));

        return '200 OK';
    }
}
