<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 11/21/15
 * Time: 4:46 PM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Serializer\Drivers\Eloquent;


/**
 * Class EloquentDriver
 * @package NilPortugues\Serializer\Drivers\Eloquent
 */
class EloquentDriver
{
    /**
     * @var Driver
     */
    private static $driver;

    /**
     * @param $value
     *
     * @return mixed|string
     */
    public static function serialize($value)
    {
        if (empty(self::$driver)) {
            self::$driver = new Driver();
        }

        return self::$driver->serialize($value);
    }
} 