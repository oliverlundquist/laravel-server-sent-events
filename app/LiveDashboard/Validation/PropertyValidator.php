<?php declare(strict_types=1);

namespace App\LiveDashboard\Validation;

use Illuminate\Support\Carbon;
use InvalidArgumentException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;

class PropertyValidator
{
    /**
     * @template T of string|float|int|bool|null|\Illuminate\Support\Carbon
     * @param class-string $class
     * @param T $value
     * @param array<string, T> $referenceData
     * @throws InvalidArgumentException
     */
    public function validate(string $class, string $key, string|float|int|bool|null|Carbon $value, array $referenceData = []): bool
    {
        $reflection    = new ReflectionClass($class);
        $properties    = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $propertyNames = array_map(fn ($property) => $property->getName(), $properties);
        if (! in_array($key, $propertyNames, true)) {
            return true;
        }
        $attributes = $this->getAttributes($properties, $propertyNames, $key);

        // no attributes was defined for this property, thus skipping validation
        if (count($attributes) === 0) {
            return true;
        }

        $attribute = $attributes[0];
        $rules     = $this->getValidationRules($attribute);
        $validator = validator(array_replace($referenceData, [$key => $value]), [$key => $rules]);
        if ($validator->fails()) {
            throw new InvalidArgumentException($class . '@' . $key . ': with value: ' . $value . ', doesn\'t validate against: ' . implode(', ', $rules));
        }

        // validation passed
        return true;
    }

    /**
     * @param ReflectionProperty[] $properties
     * @param string[] $propertyNames
     * @return ReflectionAttribute[]
     */
    private function getAttributes(array $properties, array $propertyNames, string $key): array
    {
        $propertyIndex = array_flip($propertyNames)[$key];
        $property      = $properties[$propertyIndex];
        return $property->getAttributes(ValidIf::class);
    }

    /**
     * @return array<int, string[]>
     */
    private function getValidationRules(ReflectionAttribute $attribute): array
    {
        $arguments = $attribute->getArguments();

        if (! is_array($arguments) || count($arguments) === 0 || ! isset($arguments[0])) {
            return [];
        }
        return $arguments[0];
    }
}
