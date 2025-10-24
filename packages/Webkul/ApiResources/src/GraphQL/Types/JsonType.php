<?php

namespace Webkul\ApiResources\GraphQL\Types;

use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class JsonType extends ScalarType
{
    public string $name = 'JSON';

    public ?string $description = 'The `JSON` scalar type represents JSON values as specified by ECMA-404';

    public function serialize($value): mixed
    {
        if (is_string($value)) {
            return json_decode($value, true);
        }

        return $value;
    }

    public function parseValue($value): mixed
    {
        return $value;
    }

    public function parseLiteral(Node $valueNode, ?array $variables = null): mixed
    {
        throw new Error('JSON scalar literals are not supported');
    }
}
