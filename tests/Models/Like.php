<?php

namespace NilPortugues\Tests\Serializer\Drivers\Eloquent\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class User.
 */
class Like extends Model
{
    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * @var string
     */
    protected $table = 'likes';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}
