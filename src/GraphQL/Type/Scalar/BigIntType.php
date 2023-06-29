<?php


namespace App\GraphQL\Type\Scalar;

use GraphQL\Error\Error;
use GraphQL\Language\AST\IntValueNode;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Utils\Utils;
use function floor;
use function is_bool;
use function is_float;
use function is_int;
use function is_numeric;

class BigIntType extends ScalarType
{
    private const MAX_INT = PHP_INT_MAX;
    private const MIN_INT = PHP_INT_MIN;

    public function __construct(array $config = [])
    {
        $this->description = <<<TEXT
        The `BigInt` scalar type represents non-fractional signed whole numeric
        values. BigInt can represent values between -(2^64) and 2^64 - 1.
        TEXT;

        parent::__construct($config);
    }

    /**
     * @param mixed $value
     *
     * @return int|null
     *
     * @throws Error
     */
    public function serialize($value): ?int
    {
        // Fast path for 90+% of cases:
        if (is_int($value) && $value <= self::MAX_INT && $value >= self::MIN_INT) {
            return $value;
        }

        $float = is_numeric($value) || is_bool($value)
            ? (float) $value
            : null;

        if ($float === null || floor($float) !== $float) {
            throw new Error(
                'Int cannot represent non-integer value: ' .
                Utils::printSafe($value)
            );
        }

        if ($float > self::MAX_INT || $float < self::MIN_INT) {
            throw new Error(
                'Int cannot represent non 32-bit signed integer value: ' .
                Utils::printSafe($value)
            );
        }

        return (int) $float;
    }

    /**
     * @param mixed $value
     *
     * @throws Error
     */
    public function parseValue($value) : int
    {
        $isInt = is_int($value) || (is_float($value) && floor($value) === $value);

        if (! $isInt) {
            throw new Error(
                'Int cannot represent non-integer value: ' .
                Utils::printSafe($value)
            );
        }

        if ($value > self::MAX_INT || $value < self::MIN_INT) {
            throw new Error(
                'Int cannot represent non 32-bit signed integer value: ' .
                Utils::printSafe($value)
            );
        }

        return (int) $value;
    }

    /**
     * @param Node $valueNode
     * @param array|null $variables
     *
     * @return int
     *
     * @throws Error
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): int
    {
        if ($valueNode instanceof IntValueNode) {
            $val = (int) $valueNode->value;
            if ($valueNode->value === (string) $val && self::MIN_INT <= $val && $val <= self::MAX_INT) {
                return $val;
            }
        }

        // Intentionally without message, as all information already in wrapped Exception
        throw new Error();
    }
}
