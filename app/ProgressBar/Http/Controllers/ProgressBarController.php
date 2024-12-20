<?php

namespace App\ProgressBar\Http\Controllers;

use App\ProgressBar\Cache\BackgroundRequestHandler;
use App\ProgressBar\Data\BackgroundRequest;
use Illuminate\Http\Request;

class ProgressBarController
{
    public function progressBarView() {
        $requestId      = str()->ulid()->toBase32();
        $requestHandler = new BackgroundRequestHandler;
        $request        = new BackgroundRequest(requestId: $requestId, progress: 0, completed: false);

        $requestHandler->storeRequest($request);

        if (function_exists('fastcgi_finish_request')) {
            echo view('progress_bar', compact('requestId'));
            fastcgi_finish_request();
        }

        foreach (range(0, 100) as $progress) {
            usleep(30000); // increment progress by 1% every 30ms
            $request->progress  = $progress;
            $request->completed = $progress === 100;
            $requestHandler->storeRequest($request);
        }
    }

    public function shortPollingProgressBar(BackgroundRequest $request) {
        return ! is_null($request) ? $request->progress : 100;
    }

    public function serverSentEventsProgressBar(BackgroundRequest $request) {
        if (is_null($request)) {
            return response('complete', 204);
        }
        $callback = function () use ($request) {
            while (! is_null($request) && ! $request->completed) {
                $request = new BackgroundRequestHandler()->fetchRequest($request->requestId);
                usleep(30000); // give the browser 30ms to recover between events
                echo 'data: ' . $request->progress . "\n\n";
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
}
