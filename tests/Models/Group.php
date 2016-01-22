<?php

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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function orders()
    {
        return $this->hasManyThrough(Orders::Class, User::class);
    }
}
