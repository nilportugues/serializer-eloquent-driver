<?php

include __DIR__.'/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$capsule = new Capsule();

$capsule->addConnection(array(
    'driver' => 'sqlite',
    'database' => __DIR__.'/database.db',
    'prefix' => '',
), 'default');

$capsule->bootEloquent();
$capsule->setAsGlobal();

file_put_contents(__DIR__.'/database.db', '');

Capsule::schema('default')->dropIfExists('accountmanagers');
Capsule::schema('default')->create('accountmanagers', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->string('username', 255);
    $table->string('password', 255);
    $table->string('email', 255);
    $table->timestamps();
    $table->softDeletes();
});

Capsule::schema('default')->dropIfExists('users');
Capsule::schema('default')->create('users', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->integer('account_manager_id');
    $table->string('username', 255);
    $table->string('password', 255);
    $table->string('email', 255);
    $table->timestamps();
    $table->softDeletes();
});

Capsule::schema('default')->dropIfExists('profiles');
Capsule::schema('default')->create('profiles', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->integer('user_id');
    $table->string('gravatar', 255);
    $table->timestamps();
});

Capsule::schema('default')->dropIfExists('orders');
Capsule::schema('default')->create('orders', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->integer('user_id');
    $table->string('name', 255);
    $table->dateTime('ordered_at');
});

Capsule::schema('default')->dropIfExists('groups');
Capsule::schema('default')->create('groups', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->string('name', 255);
});

Capsule::schema('default')->dropIfExists('group_user');
Capsule::schema('default')->create('group_user', function (Blueprint $table) {
    $table->integer('user_id');
    $table->integer('group_id');
});

Capsule::schema('default')->dropIfExists('likes');
Capsule::schema('default')->create('likes', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->integer('user_id');
    $table->integer('likeable_id');
    $table->string('likeable_type');
    $table->timestamps();
});
