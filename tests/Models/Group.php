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
class Group extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    public $fillable = ['name'];

    /**
     * @var string
     */
    protected $table = 'groups';

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Orders::Class, User::class);
    }
}
