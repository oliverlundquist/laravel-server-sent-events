<?php declare(strict_types=1);

namespace App\ProgressBar\Data;

use App\ProgressBar\Cache\BackgroundRequestHandler;
use InvalidArgumentException;

class BackgroundRequest extends DataObject
{
    private const DUMMY_ULID = '12345678901234567890123456';

    public function __construct
    (
        public string $requestId = self::DUMMY_ULID { // need dummy value here for ReflectionClass to work
            set {
                if (strlen($value) !== 26) {
                    throw new InvalidArgumentException('ULID must be 26 characters long');
                }
                $this->requestId = $value;
            }
        },
        public int $progress = 0 {
            set {
                if ($value < 0 || $value > 100) {
                    throw new InvalidArgumentException('Progress need to be between 0 and 100');
                }
                $this->progress = $value;
            }
        },
        public bool $completed = false
    ) {}

    protected function generateNewRequestId(): string
    {
        return str()->ulid()->toBase32();
    }

    protected function newFromRouteArgument($requestId): ?static
    {
        $request = new BackgroundRequestHandler()->fetchRequest($requestId);
        if (is_null($request)) {
            redirect('/')->send();
        }
        return $request;
    }
}
