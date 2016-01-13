<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/01/16
 * Time: 23:18.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace NilPortugues\Tests\Serializer\Drivers\Eloquent;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 */
class User extends Model
{
    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function latestOrders()
    {
        return $this->hasMany(Orders::class, 'user_id')->limit(10);
    }
}
