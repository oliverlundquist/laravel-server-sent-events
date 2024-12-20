<?php

namespace App\LiveDashboard\Http\Controllers;

use App\Data\TrackingData;
use App\LiveDashboard\Database\TrackingDataHandler;
use App\LiveDashboard\Cache\CustomerMessageHandler;
use App\Models\CustomerEvents;
use Illuminate\Http\Request;

class ShopController
{
    public function index(Request $request)
    {
        $requestPath = $request->path();
        $requestId   = new TrackingDataHandler()->storeTrackingData($requestPath);
        $sessionId   = session()->id();
        return view('shop.index', [
            'requestId'   => $requestId,
            'sessionId'   => $sessionId
        ]);
    }

    public function productDetails(Request $request, int $id)
    {
        $requestPath = '/' . $request->path();
        $requestId   = new TrackingDataHandler()->storeTrackingData($requestPath);
        $sessionId   = session()->id();
        return view('shop.product', [
            'productName' => 'Product ' . $id,
            'requestId'   => $requestId,
            'sessionId'   => $sessionId
        ]);
    }

    public function customerPing(Request $request)
    {
        $data = $request->validate([
            'requestId' => ['required', 'size:26'],
            'sessionId' => ['required', 'size:40']
        ]);
        // update last_activity_at timestamp
        new TrackingDataHandler()->ping($data['requestId'], $data['sessionId']);

        // and return customer message
        return new CustomerMessageHandler()->pullMessage($data['sessionId']);
    }
}
