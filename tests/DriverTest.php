<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/01/16
 * Time: 23:16.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Serializer\Drivers\Eloquent;

use Illuminate\Database\Capsule\Manager as Capsule;
use NilPortugues\Serializer\Drivers\Eloquent\EloquentDriver;

class DriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $user = new User();
        $user->username = 'Nil';
        $user->password = 'password';
        $user->email = 'test@example.com';
        $user->created_at = '2016-01-13 00:06:16';
        $user->updated_at = '2016-01-13 00:06:16';
        $user->timestamps = false;
        $user->save();

        $order = new Orders();
        $order->user_id = $user->id;
        $order->name = 'Some item';
        $order->ordered_at = '2016-01-14 09:15:20';
        $order->save();
    }

    public function tearDown()
    {
        Capsule::table('users')->delete();
        Capsule::table('orders')->delete();
    }

    public function testSerializePaginator()
    {
        $driver = new EloquentDriver();

        $output = $driver->serialize(Capsule::table('users')->paginate());

        $expected = array(
            '@map' => 'array',
            '@value' => array(
                0 => array(
                    '@type' => 'stdClass',
                    'id' => array(
                        '@scalar' => 'string',
                        '@value' => '1',
                    ),
                    'username' => array(
                        '@scalar' => 'string',
                        '@value' => 'Nil',
                    ),
                    'password' => array(
                        '@scalar' => 'string',
                        '@value' => 'password',
                    ),
                    'email' => array(
                        '@scalar' => 'string',
                        '@value' => 'test@example.com',
                    ),
                    'created_at' => array(
                        '@scalar' => 'string',
                        '@value' => '2016-01-13 00:06:16',
                    ),
                    'updated_at' => array(
                        '@scalar' => 'string',
                        '@value' => '2016-01-13 00:06:16',
                    ),
                    'deleted_at' => array(
                        '@scalar' => 'NULL',
                        '@value' => null,
                    ),
                ),
            ),
        );

        $this->assertEquals($expected, $output);
    }

    public function testSerializeCollection()
    {
        $user = User::all();

        $driver = new EloquentDriver();
        $output = $driver->serialize($user);

        $expected = array(
            '@map' => 'array',
            '@value' => array(
                    0 => array(
                            '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\User',
                            'id' => array(
                                    '@scalar' => 'string',
                                    '@value' => '1',
                                ),
                            'username' => array(
                                    '@scalar' => 'string',
                                    '@value' => 'Nil',
                                ),
                            'password' => array(
                                    '@scalar' => 'string',
                                    '@value' => 'password',
                                ),
                            'email' => array(
                                    '@scalar' => 'string',
                                    '@value' => 'test@example.com',
                                ),
                            'created_at' => array(
                                    '@scalar' => 'string',
                                    '@value' => '2016-01-13 00:06:16',
                                ),
                            'updated_at' => array(
                                    '@scalar' => 'string',
                                    '@value' => '2016-01-13 00:06:16',
                                ),
                            'deleted_at' => array(
                                    '@scalar' => 'NULL',
                                    '@value' => null,
                                ),
                            'latestOrders' => array(
                                    '@map' => 'array',
                                    '@value' => array(
                                            0 => array(
                                                    '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Orders',
                                                    'user_id' => array(
                                                            '@scalar' => 'string',
                                                            '@value' => '1',
                                                        ),
                                                    'name' => array(
                                                            '@scalar' => 'string',
                                                            '@value' => 'Some item',
                                                        ),
                                                    'ordered_at' => array(
                                                            '@scalar' => 'string',
                                                            '@value' => '2016-01-14 09:15:20',
                                                        ),
                                                ),
                                        ),
                                ),
                        ),
                ),
        );

        $this->assertEquals($expected, $output);
    }

    public function testSerializeModel()
    {
        $user = User::find(1);

        $driver = new EloquentDriver();
        $output = $driver->serialize($user);

        $expected = array(
            '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\User',
            'id' => array(
                    '@scalar' => 'string',
                    '@value' => '1',
                ),
            'username' => array(
                    '@scalar' => 'string',
                    '@value' => 'Nil',
                ),
            'password' => array(
                    '@scalar' => 'string',
                    '@value' => 'password',
                ),
            'email' => array(
                    '@scalar' => 'string',
                    '@value' => 'test@example.com',
                ),
            'created_at' => array(
                    '@scalar' => 'string',
                    '@value' => '2016-01-13 00:06:16',
                ),
            'updated_at' => array(
                    '@scalar' => 'string',
                    '@value' => '2016-01-13 00:06:16',
                ),
            'deleted_at' => array(
                    '@scalar' => 'NULL',
                    '@value' => null,
                ),
            'latestOrders' => array(
                    '@map' => 'array',
                    '@value' => array(
                            0 => array(
                                    '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Orders',
                                    'user_id' => array(
                                            '@scalar' => 'string',
                                            '@value' => '1',
                                        ),
                                    'name' => array(
                                            '@scalar' => 'string',
                                            '@value' => 'Some item',
                                        ),
                                    'ordered_at' => array(
                                            '@scalar' => 'string',
                                            '@value' => '2016-01-14 09:15:20',
                                        ),
                                ),
                        ),
                ),
        );

        $this->assertEquals($expected, $output);
    }
}
