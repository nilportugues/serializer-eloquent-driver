<?php

namespace NilPortugues\Tests\Serializer\Drivers\Eloquent;

use Illuminate\Database\Capsule\Manager as Capsule;
use NilPortugues\Serializer\Drivers\Eloquent\EloquentDriver;
use NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\AccountManager;
use NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group;
use NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Like;
use NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Orders;
use NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Profile;
use NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\User;

class DriverTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $manager = new AccountManager();
        $manager->username = 'Joe';
        $manager->password = 'password';
        $manager->email = 'joe@example.com';
        $manager->created_at = '2016-01-13 00:06:16';
        $manager->updated_at = '2016-01-13 00:06:16';
        $manager->save();

        $user = new User();
        $user->account_manager_id = $manager->id;
        $user->username = 'Nil';
        $user->password = 'password';
        $user->email = 'test@example.com';
        $user->created_at = '2016-01-13 00:06:16';
        $user->updated_at = '2016-01-13 00:06:16';
        $user->save();

        $user2 = new User();
        $user2->account_manager_id = $manager->id;
        $user2->username = 'Oskar';
        $user2->password = 'password';
        $user2->email = 'test2@example.com';
        $user2->created_at = '2016-06-16 11:09:33';
        $user2->updated_at = '2016-06-16 11:09:33';
        $user2->save();

        $user->friends()->attach($user2, ['relationship' => 'Github Buddies']);

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->gravatar = 'ThisIsAVeryRandomHash';
        $profile->created_at = '2016-01-13 00:06:16';
        $profile->updated_at = '2016-01-13 00:06:16';
        $profile->save();

        $user->groups()->saveMany([
            new Group(['name' => 'customers']),
            new Group(['name' => 'platin-customers']),
            new Group(['name' => 'users']),
        ]);

        $order = new Orders();
        $order->user_id = $user->id;
        $order->name = 'Some item';
        $order->ordered_at = '2016-01-14 09:15:20';
        $order->save();

        $like = new Like();
        $like->user_id = $user->id;
        $like->likeable_id = $order->id;
        $like->likeable_type = Orders::class;
        $like->created_at = '2016-01-22 11:33:41';
        $like->updated_at = '2016-01-22 11:33:41';
        $like->save();

        $like = new Like();
        $like->user_id = $user->id;
        $like->likeable_id = $manager->id;
        $like->likeable_type = AccountManager::class;
        $like->created_at = '2016-01-22 11:33:41';
        $like->updated_at = '2016-01-22 11:33:41';
        $like->save();
    }

    public function tearDown()
    {
        Capsule::table('accountmanagers')->delete();
        Capsule::table('users')->delete();
        Capsule::table('user_user')->delete();
        Capsule::table('orders')->delete();
        Capsule::table('groups')->delete();
        Capsule::table('group_user')->delete();
        Capsule::table('likes')->delete();
        Capsule::table('profiles')->delete();
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
                    'account_manager_id' => array(
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
                1 => array(
                    '@type' => 'stdClass',
                    'id' => array(
                        '@scalar' => 'string',
                        '@value' => '2',
                    ),
                    'account_manager_id' => array(
                        '@scalar' => 'string',
                        '@value' => '1',
                    ),
                    'username' => array(
                        '@scalar' => 'string',
                        '@value' => 'Oskar',
                    ),
                    'password' => array(
                        '@scalar' => 'string',
                        '@value' => 'password',
                    ),
                    'email' => array(
                        '@scalar' => 'string',
                        '@value' => 'test2@example.com',
                    ),
                    'created_at' => array(
                        '@scalar' => 'string',
                        '@value' => '2016-06-16 11:09:33',
                    ),
                    'updated_at' => array(
                        '@scalar' => 'string',
                        '@value' => '2016-06-16 11:09:33',
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
                            '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Models\\User',
                            'id' => array(
                                    '@scalar' => 'integer',
                                    '@value' => '1',
                                ),
                            'account_manager_id' => array(
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
                                                    '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Models\\Orders',
                                                    'id' => array(
                                                            '@scalar' => 'integer',
                                                            '@value' => '1',
                                                        ),
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
                            'groups' => array(
                                    '@map' => 'array',
                                    '@value' => array(
                                            0 => array(
                                                    '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group',
                                                    'id' => array(
                                                        '@scalar' => 'integer',
                                                        '@value' => '1',
                                                    ),
                                                    'name' => array(
                                                        '@scalar' => 'string',
                                                        '@value' => 'customers',
                                                    ),
                                            ),
                                            1 => array(
                                                    '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group',
                                                    'id' => array(
                                                        '@scalar' => 'integer',
                                                        '@value' => '2',
                                                    ),
                                                    'name' => array(
                                                        '@scalar' => 'string',
                                                        '@value' => 'platin-customers',
                                                    ),
                                            ),
                                            2 => array(
                                                    '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group',
                                                    'id' => array(
                                                        '@scalar' => 'integer',
                                                        '@value' => '3',
                                                    ),
                                                    'name' => array(
                                                        '@scalar' => 'string',
                                                        '@value' => 'users',
                                                    ),
                                            ),
                                    ),
                            ),
                            'profile' => array(
                                '@map' => 'array',
                                '@value' => array(
                                    0 => array(
                                        '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Profile',
                                        'id' => array(
                                            '@scalar' => 'integer',
                                            '@value' => '1',
                                        ),
                                        'user_id' => array(
                                            '@scalar' => 'string',
                                            '@value' => '1',
                                        ),
                                        'gravatar' => array(
                                            '@scalar' => 'string',
                                            '@value' => 'ThisIsAVeryRandomHash',
                                        ),
                                        'created_at' => array(
                                            '@scalar' => 'string',
                                            '@value' => '2016-01-13 00:06:16',
                                        ),
                                        'updated_at' => array(
                                            '@scalar' => 'string',
                                            '@value' => '2016-01-13 00:06:16',
                                        ),
                                    ),
                                ),
                            ),
                            'friends' => array(
                                '@map' => 'array',
                                '@value' => array(
                                    0 => array(
                                        '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\User',
                                        'id' => array(
                                            '@scalar' => 'integer',
                                            '@value' => '2',
                                        ),
                                        'account_manager_id' => array(
                                            '@scalar' => 'string',
                                            '@value' => '1',
                                        ),
                                        'username' => array(
                                            '@scalar' => 'string',
                                            '@value' => 'Oskar',
                                        ),
                                        'password' => array(
                                            '@scalar' => 'string',
                                            '@value' => 'password',
                                        ),
                                        'email' => array(
                                            '@scalar' => 'string',
                                            '@value' => 'test2@example.com',
                                        ),
                                        'created_at' => array(
                                            '@scalar' => 'string',
                                            '@value' => '2016-06-16 11:09:33',
                                        ),
                                        'updated_at' => array(
                                            '@scalar' => 'string',
                                            '@value' => '2016-06-16 11:09:33',
                                        ),
                                        'deleted_at' => array(
                                            '@scalar' => 'NULL',
                                            '@value' => null,
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    1 => array(
                        '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Models\\User',
                        'id' => array(
                            '@scalar' => 'integer',
                            '@value' => '2',
                        ),
                        'account_manager_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                        'username' => array(
                            '@scalar' => 'string',
                            '@value' => 'Oskar',
                        ),
                        'password' => array(
                            '@scalar' => 'string',
                            '@value' => 'password',
                        ),
                        'email' => array(
                            '@scalar' => 'string',
                            '@value' => 'test2@example.com',
                        ),
                        'created_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-06-16 11:09:33',
                        ),
                        'updated_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-06-16 11:09:33',
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

    public function testSerializeCollectionWithHasManyThroughAndMorp()
    {
        /* get a serialized collection by using a "HasManyThrough" relation */
        $user_orders = AccountManager::with('orders')->find(1);

        $driver = new EloquentDriver();
        $output = $driver->serialize($user_orders);

        $expected = array(
            '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\AccountManager',
            'id' => array(
                '@scalar' => 'integer',
                '@value' => '1',
            ),
            'username' => array(
                '@scalar' => 'string',
                '@value' => 'Joe',
            ),
            'password' => array(
                '@scalar' => 'string',
                '@value' => 'password',
            ),
            'email' => array(
                '@scalar' => 'string',
                '@value' => 'joe@example.com',
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
            'orders' => array(
                '@map' => 'array',
                '@value' => array(
                    0 => array(
                        '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Orders',
                        'user_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                        'id' => array(
                            '@scalar' => 'integer',
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
                        'account_manager_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                    ),
                ),
            ),
            'likes' => array(
                '@map' => 'array',
                '@value' => array(
                    0 => array(
                        '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Like',
                        'id' => array(
                            '@scalar' => 'integer',
                            '@value' => 2,
                        ),
                        'user_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                        'likeable_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                        'likeable_type' => array(
                            '@scalar' => 'string',
                            '@value' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\AccountManager',
                        ),
                        'created_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-01-22 11:33:41',
                        ),
                        'updated_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-01-22 11:33:41',
                        ),
                    ),
                ),
            ),
        );

        $this->assertEquals($expected, $output);
    }

    public function testSerializeModelWithOneToOneRelation()
    {
        $user = User::with('profile')->find(1);

        $driver = new EloquentDriver();
        $output = $driver->serialize($user);

        $expected = array(
            '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Models\\User',
            'id' => array(
                    '@scalar' => 'integer',
                    '@value' => '1',
                ),
            'account_manager_id' => array(
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
                                    '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Models\\Orders',
                                    'id' => array(
                                            '@scalar' => 'integer',
                                            '@value' => '1',
                                        ),
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
            'groups' => array(
                    '@map' => 'array',
                    '@value' => array(
                            0 => array(
                                    '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group',
                                    'id' => array(
                                        '@scalar' => 'integer',
                                        '@value' => '1',
                                    ),
                                    'name' => array(
                                        '@scalar' => 'string',
                                        '@value' => 'customers',
                                    ),
                            ),
                            1 => array(
                                    '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group',
                                    'id' => array(
                                        '@scalar' => 'integer',
                                        '@value' => '2',
                                    ),
                                    'name' => array(
                                        '@scalar' => 'string',
                                        '@value' => 'platin-customers',
                                    ),
                            ),
                            2 => array(
                                    '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Group',
                                    'id' => array(
                                        '@scalar' => 'integer',
                                        '@value' => '3',
                                    ),
                                    'name' => array(
                                        '@scalar' => 'string',
                                        '@value' => 'users',
                                    ),
                            ),
                    ),
            ),
            'profile' => array(
                '@map' => 'array',
                '@value' => array(
                    0 => array(
                        '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Profile',
                        'id' => array(
                            '@scalar' => 'integer',
                            '@value' => '1',
                        ),
                        'user_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                        'gravatar' => array(
                            '@scalar' => 'string',
                            '@value' => 'ThisIsAVeryRandomHash',
                        ),
                        'created_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-01-13 00:06:16',
                        ),
                        'updated_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-01-13 00:06:16',
                        ),
                    ),
                ),
            ),
            'friends' => array(
                '@map' => 'array',
                '@value' => array(
                    0 => array(
                        '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\User',
                        'id' => array(
                            '@scalar' => 'integer',
                            '@value' => '2',
                        ),
                        'account_manager_id' => array(
                            '@scalar' => 'string',
                            '@value' => '1',
                        ),
                        'username' => array(
                            '@scalar' => 'string',
                            '@value' => 'Oskar',
                        ),
                        'password' => array(
                            '@scalar' => 'string',
                            '@value' => 'password',
                        ),
                        'email' => array(
                            '@scalar' => 'string',
                            '@value' => 'test2@example.com',
                        ),
                        'created_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-06-16 11:09:33',
                        ),
                        'updated_at' => array(
                            '@scalar' => 'string',
                            '@value' => '2016-06-16 11:09:33',
                        ),
                        'deleted_at' => array(
                            '@scalar' => 'NULL',
                            '@value' => null,
                        ),
                    ),
                ),
            ),
        );

        $this->assertEquals($expected, $output);
    }

    public function testSerializeModelWithInvertedOneToOneRelation()
    {
        $profile = Profile::with('user')->find(1);

        $driver = new EloquentDriver();
        $output = $driver->serialize($profile);

        $expected = array(
            '@type' => 'NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Profile',
            'id' => array(
                '@scalar' => 'integer',
                '@value' => '1',
            ),
            'user_id' => array(
                '@scalar' => 'string',
                '@value' => '1',
            ),
            'gravatar' => array(
                '@scalar' => 'string',
                '@value' => 'ThisIsAVeryRandomHash',
            ),
            'created_at' => array(
                '@scalar' => 'string',
                '@value' => '2016-01-13 00:06:16',
            ),
            'updated_at' => array(
                '@scalar' => 'string',
                '@value' => '2016-01-13 00:06:16',
            ),
            'user' => array(
                '@map' => 'array',
                '@value' => array(
                    0 => array(
                        '@type' => 'NilPortugues\\Tests\\Serializer\\Drivers\\Eloquent\\Models\\User',
                        'id' => array(
                                '@scalar' => 'integer',
                                '@value' => '1',
                            ),
                        'account_manager_id' => array(
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
            ),
        );

        $this->assertEquals($expected, $output);
    }
}
