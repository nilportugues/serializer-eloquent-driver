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

Capsule::schema('default')->dropIfExists('users');
Capsule::schema('default')->create('users', function (Blueprint $table) {
    $table->primary('id');
    $table->integer('id');
    $table->string('username', 255);
    $table->string('password', 255);
    $table->string('email', 255);
    $table->timestamps();
    $table->softDeletes();
});

Capsule::schema('default')->dropIfExists('orders');
Capsule::schema('default')->create('orders', function (Blueprint $table) {
    $table->integer('user_id', 255);
    $table->string('name', 255);
    $table->dateTime('ordered_at');
});
