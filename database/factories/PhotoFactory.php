<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Photo;
use Faker\Generator as Faker;

$factory->define(Photo::class, function (Faker $faker) {

    return [

        'entity_id'=>factory(App\Entity::class) ,
        'caption'=>$faker->paragraph,
        'photo'=>$faker->image('public/storage/images/post_images',680,400,null,false),

    ];
});
