<?php declare(strict_types=1);

namespace App\LiveDashboard\Formatter;

class EventStreamDataFormatter
{
    public function __construct
    (
        public array $eventData {
            set {
                if ($value === [] || $value !== array_filter($value, 'is_string')) {
                    throw new \InvalidArgumentException('eventData must be an array of strings');
                }
                $this->eventData = $value;
            }
        },
        public string $eventName = 'message',
        public ?int $retryAfter = null, // 1000 = 1 sec
        public ?string $eventId = null
    ) {}

    public function render(): string
    {
        $data = [];

        $data[] = 'event: ' . $this->eventName;
        foreach ($this->eventData as $eventData) {
            $data[] = 'data: ' . $eventData;
        }
        if (! is_null($this->retryAfter)) {
            $data[] = 'retry: ' . $this->retryAfter;
        }
        if (! is_null($this->eventId)) {
            $data[] = 'id: ' . $this->eventId;
        }
        return implode("\n", $data) . "\n\n";
    }
}
