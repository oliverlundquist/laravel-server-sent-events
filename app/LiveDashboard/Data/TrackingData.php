<?php declare(strict_types=1);

namespace App\LiveDashboard\Data;

use App\LiveDashboard\Repository\TrackingDataEloquent;
use App\LiveDashboard\Validation\ValidIf;
use Illuminate\Support\Carbon;

class TrackingData extends DataObject
{
    #[ValidIf(['string', 'size:26'])]
    public string $request_id {
        set => $this->validateProperty('request_id', $value);
    }

    #[ValidIf(['string', 'size:40'])]
    public string $session_id {
        set => $this->validateProperty('session_id', $value);
    }

    // no validation
    public string $tenant {
        set => $this->validateProperty('tenant', $value);
    }

    // no validation
    public string $visited_page {
        set => $this->validateProperty('visited_page', $value);
    }

    #[ValidIf(['date_format:U', 'gt:0'])]
    public int $request_start_time {
        set => $this->validateProperty('request_start_time', $value);
    }

    #[ValidIf(['gte:request_start_time'])]
    public int $request_last_activity_at {
        set => $this->validateProperty('request_last_activity_at', $value, ['request_start_time' => $this->request_start_time]);
    }

    // virtual property
    public int $request_duration {
        get => max(intval($this->request_last_activity_at ?? 0) - intval($this->request_start_time ?? 0), 0);
    }

    #[ValidIf(['integer', 'date_format:U'])]
    public int $session_start_time {
        set => $this->validateProperty('session_start_time', $value);
    }

    // virtual property
    public int $session_duration {
        get => max(intval(now()->format('U')) - intval($this->session_start_time ?? 0), 0);
    }

    #[ValidIf(['nullable', 'integer', 'date_format:U'])]
    public ?int $completed_at {
        set => $this->validateProperty('completed_at', $value);
    }

    #[ValidIf(['nullable', 'date'])]
    public ?Carbon $created_at {
        set => $this->validateProperty('created_at', $value);
    }

    #[ValidIf(['nullable', 'date'])]
    public ?Carbon $updated_at {
        set => $this->validateProperty('updated_at', $value);
    }

    /**
     * @return class-string
     */
    protected function getEloquentClassName(): string
    {
        return TrackingDataEloquent::class;
    }
}
