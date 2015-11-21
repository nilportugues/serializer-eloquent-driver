<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 3:44 PM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Serializer\Drivers\Eloquent;

use NilPortugues\Serializer\Serializer;
use ErrorException;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class EloquentDriver
 * @package NilPortugues\Laravel5\Serializer
 */
class EloquentDriver
{
    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Extract the data from an object.
     *
     * @param mixed $value
     *
     * @return array
     */
    public function serialize($value)
    {
        if ($value instanceof \Illuminate\Database\Eloquent\Collection) {
            $items = [];
            foreach ($value as &$v) {
                $items[] = $this->serializer->serialize($v);
            }

            return [Serializer::MAP_TYPE => 'array', Serializer::SCALAR_VALUE => $items];
        }

        if ($value instanceof \Illuminate\Contracts\Pagination\Paginator) {
            $items = [];
            foreach ($value->items() as &$v) {
                $items[] = $this->serializer->serialize($v);
            }

            return [Serializer::MAP_TYPE => 'array', Serializer::SCALAR_VALUE => $items];
        }

        if (is_subclass_of($value, Model::class, true)) {
            $stdClass = (object) $value->getAttributes();
            $data = $this->serializer->serialize($stdClass);
            $data[Serializer::CLASS_IDENTIFIER_KEY] = \get_class($value);

            $methods = $this->getRelationshipAsPropertyName($value, get_class($value), new ReflectionClass($value));

            if (!empty($methods)) {
                $data = \array_merge($data, $methods);
            }

            return $data;
        }

        return $this->serializer->serialize($value);
    }

    /**
     * @param                 $value
     * @param string          $className
     * @param ReflectionClass $reflection
     *
     * @return array
     */
    protected function getRelationshipAsPropertyName($value, $className, ReflectionClass $reflection)
    {
        $methods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (\ltrim($method->class, '\\') === \ltrim($className, '\\')) {
                $name = $method->name;
                $reflectionMethod = $reflection->getMethod($name);

                // Eloquent relations do not include parameters, so we'll be filtering based on this criteria.
                if (0 == $reflectionMethod->getNumberOfParameters()) {
                    try {
                        $returned = $reflectionMethod->invoke($value);
                        //All operations (eg: boolean operations) are now filtered out.
                        if (is_object($returned)) {

                            // Only keep those methods as properties if these are returning Eloquent relations.
                            // But do not run the operation as it is an expensive operation.
                            if (false !== strpos(get_class($returned), 'Illuminate\Database\Eloquent\Relations')) {
                                $items = [];
                                foreach ($returned->getResults() as $model) {
                                    if (is_object($model)) {
                                        /** @var Model $model */
                                        $stdClass = (object) $model->getAttributes();
                                        $data = $this->serializer->serialize($stdClass);
                                        $data[Serializer::CLASS_IDENTIFIER_KEY] = \get_class($model);

                                        $items[] = $data;
                                    }
                                }
                                if (!empty($items)) {
                                    $methods[$name] = [Serializer::MAP_TYPE => 'array', Serializer::SCALAR_VALUE => $items];
                                }
                            }
                        }
                    } catch (ErrorException $e) {
                    }
                }
            }
        }

        return $methods;
    }
} 