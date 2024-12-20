<?php declare(strict_types=1);

namespace App\ProgressBar\Data;

use Illuminate\Contracts\Routing\UrlRoutable;
use InvalidArgumentException;

abstract class DataObject implements UrlRoutable
{
    /**
     * @throws InvalidArgumentException
     */
    public function __get($key)
    {
        throw new InvalidArgumentException('Property: ' . $key . ' not available on class: ' . static::class);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __set($key, $value)
    {
        throw new InvalidArgumentException('Can\'t set property: ' . $key . ', since it\'s not defined on class: ' . static::class);
    }

    protected function newFromRouteArgument($argument): ?static
    {
        return null;
    }

    // UrlRoutable Interface
    public function getRouteKey() {}
    public function getRouteKeyName() {}
    public function resolveChildRouteBinding($childType, $value, $field) {}
    public function resolveRouteBinding($value, $field = null) { return $this->newFromRouteArgument($value); }
}
