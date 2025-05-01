<?php

declare(strict_types=1);

namespace App\Support\Models;

// use App\Domains\Activity\Enums\LogNameEnum;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\SoftDeletes;

// use Spatie\Activitylog\LogOptions;
// use Spatie\Activitylog\Models\Activity;
// use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
abstract class Model extends EloquentModel
{
    use HasFactory, SoftDeletes;
    // use LogsActivity;

    abstract protected static function newFactory(): Factory;

    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logFillable()
    //         ->logOnlyDirty()
    //         ->useLogName(LogNameEnum::ModelEvents->value);
    // }

    // public function tapActivity(Activity $activity)
    // {
    //     $activity->geoloc = geoip(request()->ip())['attributes'] ?? [];
    // }

    public function getDataClass(): ?string
    {
        $className = sprintf('%sData', str_replace('Models', 'Data', get_class($this)));

        return class_exists($className) ? $className : null;
    }
}
