<?php


namespace Feggu\Core\Partner\Models;


use Illuminate\Support\Str;

trait fegguTrait
{
    public static function bootCreatable()
    {
        // if user is logged in
        if (\Partner::user()) {

            // Creating Function
            static::creating(function($model){
                $model->slug = Str::uuid()->toString();
            });

        }
    }
}
