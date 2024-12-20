<?php declare(strict_types=1);

namespace App\LiveDashboard\Repository;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class TrackingDataEloquent extends Model
{
    use HasUlids;
    protected $table = 'tracking_data';
    protected $guarded = false;
    protected $primaryKey = 'request_id';
}
