<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/01/16
 * Time: 23:49.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Serializer\Drivers\Eloquent;

use Illuminate\Database\Capsule\Manager as Capsule;
use NilPortugues\Serializer\Drivers\Eloquent\EloquentDriver;

/**
 * Class EloquentDriverTest.
 */
class EloquentDriverTest extends \PHPUnit_Framework_TestCase
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
    }

    public function tearDown()
    {
        Capsule::table('users')->delete();
    }

    public function testSerialize()
    {
        $eloquentDriver = new EloquentDriver();
        $output = $eloquentDriver->serialize(Capsule::table('users')->find(1));

        $expected = array(
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
        );

        $this->assertEquals($expected, $output);
    }
}
