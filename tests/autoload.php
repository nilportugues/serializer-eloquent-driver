<?php

include __DIR__.'/../vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Schema\Blueprint;

$capsule = new Capsule();

$capsule->addConnection(array(
    'driver' => 'sqlite',
    'database' => __DIR__.'/database.db',
    'prefix' => '',
));

$capsule->bootEloquent();
$capsule->setAsGlobal();

if (false == Capsule::schema()->hasTable('users')) {
    Capsule::schema()->create('users', function (Blueprint $table) {
        $table->primary('id');
        $table->integer('id');
        $table->string('username', 255);
        $table->string('password', 255);
        $table->string('email', 255)->unique('email');
        $table->timestamps();
        $table->softDeletes();
        $table->create();
    });
}
