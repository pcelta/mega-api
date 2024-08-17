<?php

declare(strict_types = 1);

namespace Lib;

class SchemaValidator
{
    public const FIELD_TYPE_STRING = 'string';
    public const FIELD_TYPE_INT = 'int';
    public const FIELD_TYPE_OBJECT = 'object';
    public const FIELD_TYPE_LIST_OF_OBJECTS = 'list-of-objects';

    protected array $errors = [];

    public function validate(array $schema, $data, $prefix = ''): bool
    {
        foreach ($schema as $fieldSchema) {
            if (!isset($data[$fieldSchema['field_name']])) {
                if (($fieldSchema['optional'] ?? false) === true) {
                    continue;
                }

                $this->errors[] = sprintf('Field: [%s] missing', $prefix . $fieldSchema['field_name']);

                continue;
            }

            $value = $data[$fieldSchema['field_name']];

            if ($fieldSchema['validation'] === self::FIELD_TYPE_STRING && !is_string($value)) {
                $this->errors[] = sprintf('Field: [%s] incorrect type', $prefix . $fieldSchema['field_name']);

                continue;
            }

            if ($fieldSchema['validation'] === self::FIELD_TYPE_INT && !is_int($value)) {
                $this->errors[] = sprintf('Field: [%s] incorrect type', $prefix . $fieldSchema['field_name']);

                continue;
            }

            if ($fieldSchema['validation'] === self::FIELD_TYPE_OBJECT) {
                $this->validate($fieldSchema['schema'], $value, $fieldSchema['field_name'] . '.');

                continue;
            }

            if ($fieldSchema['validation'] === self::FIELD_TYPE_LIST_OF_OBJECTS) {
                foreach ($value as $item) {
                    $this->validate($fieldSchema['schema'], $item, $fieldSchema['field_name'] . '.');
                }

                continue;
            }
        }

        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
