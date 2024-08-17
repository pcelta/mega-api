<?php

declare(strict_types = 1);

namespace MegaTests\Unit\Lib;

use Lib\SchemaValidator;
use PHPUnit\Framework\TestCase;
use stdClass;

class SchemaValidatorTest extends TestCase
{
    public function testValidate()
    {
        $schema = [
            [
                'field_name' => 'slug',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ],
            [
                'field_name' => 'number',
                'validation' => SchemaValidator::FIELD_TYPE_INT,
            ],
            [
                'field_name' => 'role',
                'validation' => SchemaValidator::FIELD_TYPE_OBJECT,
                'schema' => [
                    [
                        'field_name' => 'uid',
                        'validation' => SchemaValidator::FIELD_TYPE_STRING,
                    ]
                ],
            ],
            [
                'field_name' => 'roles',
                'validation' => SchemaValidator::FIELD_TYPE_LIST_OF_OBJECTS,
                'schema' => [
                    [
                        'field_name' => 'uid',
                        'validation' => SchemaValidator::FIELD_TYPE_STRING,
                    ]
                ],
            ],
        ];

        $data = [
            'slug' => 'role-user',
            'number' => 10,
            'role' => [
                'uid' => '550e8400-e29b-41d4-a716-446655440000',
            ],
            'roles' => [[
                    'uid' => '550e8400-e29b-41d4-a716-446655440000',
                ]
            ]
        ];

        $schemaValidator = new SchemaValidator();
        $result = $schemaValidator->validate($schema, $data);
        $resultErrors = $schemaValidator->getErrors();

        $this->assertTrue($result);
        $this->assertEmpty($resultErrors);
    }

    public function testValidateShouldReturnErrorWhenFieldIsMissing()
    {
        $schema = [
            [
                'field_name' => 'slug',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ],
        ];

        $data = [];

        $expectedErrors = [
            'Field: [slug] missing'
        ];

        $schemaValidator = new SchemaValidator();

        $result = $schemaValidator->validate($schema, $data);
        $resultErrors = $schemaValidator->getErrors();

        $this->assertFalse($result);
        $this->assertEquals($expectedErrors, $resultErrors);
    }

    /**
     * @dataProvider nonStringValueProvider
     */
    public function testValidateShouldReturnErrorWhenFieldStringTypeIsIncorrect($nonStringValue)
    {
        $schema = [
            [
                'field_name' => 'slug',
                'validation' => SchemaValidator::FIELD_TYPE_STRING,
            ],
        ];

        $data = [
            'slug' => $nonStringValue,
        ];

        $expectedErrors = [
            'Field: [slug] incorrect type'
        ];

        $schemaValidator = new SchemaValidator();

        $result = $schemaValidator->validate($schema, $data);
        $resultErrors = $schemaValidator->getErrors();

        $this->assertFalse($result);
        $this->assertEquals($expectedErrors, $resultErrors);
    }

    public static function nonStringValueProvider(): array
    {
        return [
            'Array value' => [[]],
            'Int value' => [10],
            'Zero value' => [0],
            'Object value' => [new stdClass()],
        ];
    }

        /**
     * @dataProvider nonIntegerValueProvider
     */
    public function testValidateShouldReturnErrorWhenFieldIntTypeIsIncorrect($nonIntegerValue)
    {
        $schema = [
            [
                'field_name' => 'number',
                'validation' => SchemaValidator::FIELD_TYPE_INT,
            ],
        ];

        $data = [
            'number' => $nonIntegerValue,
        ];

        $expectedErrors = [
            'Field: [number] incorrect type'
        ];

        $schemaValidator = new SchemaValidator();

        $result = $schemaValidator->validate($schema, $data);
        $resultErrors = $schemaValidator->getErrors();

        $this->assertFalse($result);
        $this->assertEquals($expectedErrors, $resultErrors);
    }

    public static function nonIntegerValueProvider(): array
    {
        return [
            'Array value' => [[]],
            'Empty string value' => [''],
            'Non empty string value' => ['non empty'],
            'Object value' => [new stdClass()],
        ];
    }

    public function testValidateShouldReturnErrorWhenFieldObjectTypeIsInvalid()
    {
        $schema = [
            [
                'field_name' => 'roles',
                'validation' => SchemaValidator::FIELD_TYPE_OBJECT,
                'schema' => [
                    [
                        'field_name' => 'uid',
                        'validation' => SchemaValidator::FIELD_TYPE_STRING,
                    ]
                ],
            ],
        ];

        $data = [
            'roles' => [],

        ];

        $expectedErrors = [
            'Field: [roles.uid] missing'
        ];

        $schemaValidator = new SchemaValidator();

        $result = $schemaValidator->validate($schema, $data);
        $resultErrors = $schemaValidator->getErrors();

        $this->assertFalse($result);
        $this->assertEquals($expectedErrors, $resultErrors);
    }
}
