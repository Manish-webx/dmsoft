<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasLocation
{
    protected static function bootHasLocation()
    {
        static::addGlobalScope('location', function (Builder $builder) {
            if (!app()->runningInConsole() && app()->bound('current_location')) {
                $location = app()->make('current_location');
                $builder->where($builder->getModel()->getTable() . '.location', $location);
            }
        });

        static::creating(function ($model) {
            if (!app()->runningInConsole() && app()->bound('current_location') && empty($model->location)) {
                $model->location = app()->make('current_location');
            } elseif (empty($model->location)) {
                $model->location = 'Gurgaon';
            }
        });
    }
}
