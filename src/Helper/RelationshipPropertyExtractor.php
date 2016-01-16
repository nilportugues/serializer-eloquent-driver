<?php

namespace NilPortugues\Serializer\Drivers\Eloquent\Helper;

use ErrorException;
use Illuminate\Database\Eloquent\Model;
use NilPortugues\Serializer\Drivers\Eloquent\Driver;
use NilPortugues\Serializer\Serializer;
use ReflectionClass;
use ReflectionMethod;

class RelationshipPropertyExtractor
{
    /**
     * @var array
     */
    private static $forbiddenFunction = [
        'forceDelete',
        'forceFill',
        'delete',
        'newQueryWithoutScopes',
        'newQuery',
        'bootIfNotBooted',
        'boot',
        'bootTraits',
        'clearBootedModels',
        'query',
        'onWriteConnection',
        'delete',
        'forceDelete',
        'performDeleteOnModel',
        'flushEventListeners',
        'push',
        'touchOwners',
        'touch',
        'updateTimestamps',
        'freshTimestamp',
        'freshTimestampString',
        'newQuery',
        'newQueryWithoutScopes',
        'newBaseQueryBuilder',
        'usesTimestamps',
        'reguard',
        'isUnguarded',
        'totallyGuarded',
        'syncOriginal',
        'getConnectionResolver',
        'unsetConnectionResolver',
        'getEventDispatcher',
        'unsetEventDispatcher',
        '__toString',
        '__wakeup',
    ];

    /**
     * @param $value
     * @param $className
     * @param ReflectionClass $reflection
     * @param Driver          $serializer
     *
     * @return array
     */
    public static function getRelationshipAsPropertyName(
        $value,
        $className,
        ReflectionClass $reflection,
        Driver $serializer
    ) {
        $methods = [];
        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (\ltrim($method->class, '\\') === \ltrim($className, '\\')) {
                $name = $method->name;
                $reflectionMethod = $reflection->getMethod($name);

                // Eloquent relations do not include parameters, so we'll be filtering based on this criteria.
                if (0 == $reflectionMethod->getNumberOfParameters()) {
                    try {
                        if (self::isAllowedEloquentModelFunction($name)) {
                            $returned = $reflectionMethod->invoke($value);

                            //All operations (eg: boolean operations) are now filtered out.
                            if (\is_object($returned)) {

                                if (self::isAnEloquentRelation($returned)) {
                                    $items = [];
                                    $relationData = $returned->getResults();

                                    if (\is_object($relationData)) {
                                        //Collection with Models
                                        foreach ($relationData as $model) {
                                            if(\is_object($model)) {
                                                $items[] = self::getModelData($serializer, $model);
                                            }
                                        }
                                    } elseif(\is_object($relationData) && $relationData instanceof Model) {
                                        //Single element returned.
                                        $items[] = self::getModelData($serializer, $relationData);
                                    }

                                    if (!empty($items)) {
                                        $methods[$name] = [
                                            Serializer::MAP_TYPE => 'array',
                                            Serializer::SCALAR_VALUE => $items,
                                        ];
                                    }
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

    /**
     * @param $name
     *
     * @return bool
     */
    protected static function isAllowedEloquentModelFunction($name)
    {
         return false === in_array($name, self::$forbiddenFunction, true);
    }

    /**
     * @param $returned
     *
     * @return bool
     */
    protected static function isAnEloquentRelation($returned)
    {
        return false !== strpos(get_class($returned), 'Illuminate\Database\Eloquent\Relations');
    }

    /**
     * @param Driver $serializer
     * @param Model  $model
     *
     * @return array
     */
    protected static function getModelData(Driver $serializer, Model $model)
    {
        $stdClass = (object) $model->attributesToArray();
        $data = $serializer->serialize($stdClass);
        $data[Serializer::CLASS_IDENTIFIER_KEY] = get_class($model);

        return $data;
    }
}
