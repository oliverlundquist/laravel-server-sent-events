<?php declare(strict_types=1);

namespace App\LiveDashboard\Database;

use App\LiveDashboard\Data\TrackingData;
use App\LiveDashboard\Repository\TrackingDataEloquent;
use Illuminate\Support\Carbon;

class TrackingDataHandler
{
    public function storeTrackingData(string $requestPath): string
    {
        $now       = intval(now()->format('U'));
        $sessionId = session()->id();

        // delete old data
        $this->purge();
        TrackingDataEloquent::where('session_id', $sessionId)->whereNull('completed_at')->update(['completed_at' => $now]); // complete old requests

        // create new data
        $requestId        = str()->ulid()->toBase32();
        $sessionId        = session()->id();
        $nowTimestamp     = intval(now()->format('U'));
        $sessionStartTime = $this->getSessionStartTime($sessionId);
        $trackingData     = new TrackingData([
            'request_id'               => $requestId,
            'session_id'               => $sessionId,
            'tenant'                   => 'bike-spareparts',
            'visited_page'             => $requestPath,
            'request_start_time'       => $nowTimestamp,
            'request_last_activity_at' => $nowTimestamp,
            'session_start_time'       => $sessionStartTime ?? $nowTimestamp
        ]);
        TrackingDataEloquent::create((array) $trackingData);

        return $requestId;
    }

    /**
     * @return \App\LiveDashboard\Data\TrackingData[]
     */
    public function fetchEventsForTenantName(string $tenantName, Carbon $newerThan): array
    {
        return TrackingDataEloquent::where('tenant', $tenantName)
                    ->where('updated_at', '>', $newerThan)
                    ->get()
                    ->map(function ($trackingData) {
                        return new TrackingData()->newFromEloquentModel($trackingData)->toArray();
                    })
                    ->toArray();
    }

    public function ping(string $requestId, string $sessionId): void
    {
        $now = intval(now()->format('U'));

        TrackingDataEloquent::where('request_id', $requestId)
                    ->where('session_id', $sessionId)
                    ->update(['request_last_activity_at' => $now]);
    }

    public function purge(): void
    {
        $now           = intval(now()->format('U'));
        $threeDaysAgo  = intval(now()->subDays(3)->format('U'));
        $tenSecondsAgo = intval(now()->subSeconds(10)->format('U'));

        TrackingDataEloquent::where('request_last_activity_at', '<', $threeDaysAgo)->delete();
        TrackingDataEloquent::where('request_last_activity_at', '<', $tenSecondsAgo)->whereNull('completed_at')->update(['completed_at' => $now]); // user left
    }

    private function getSessionStartTime(string $sessionId): int|null
    {
        /**
         * @var \Illuminate\Support\Carbon|null
         */
        $carbonOrNull = TrackingDataEloquent::where('session_id', $sessionId)->orderBy('created_at', 'asc')->pluck('created_at')->first();

        if (is_null($carbonOrNull)) {
            return null;
        }
        return intval($carbonOrNull->format('U'));
    }
}
