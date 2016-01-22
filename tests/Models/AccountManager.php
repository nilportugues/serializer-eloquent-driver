<?php

/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 12/01/16
 * Time: 23:18.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\Serializer\Drivers\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 */
class AccountManager extends Model
{
    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var string
     */
    protected $table = 'accountmanagers';

    public function orders()
    {
        return $this->hasManyThrough(Orders::class, User::class);
    }

    public function likes()
    {
        return $this->morphMany('NilPortugues\Tests\Serializer\Drivers\Eloquent\Models\Like', 'likeable');
    }
}
