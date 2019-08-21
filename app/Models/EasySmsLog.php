<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Override\Laravel\Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Class EasySmsLog.
 *
 * @package App\Models
 * @property string $id
 * @property string $mobile_number
 * @property array $message
 * @property array $results
 * @property bool $successful
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder filter($query, array $input = [], $filter = null)
 * @mixin \Eloquent
 */
class EasySmsLog extends Model
{
    use Filterable;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'message' => '{}',
        'results' => '{}',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'mobile_number',
        'message',
        'results',
        'successful',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'message' => 'array',
        'results' => 'array',
        'successful' => 'bool',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /** @var static $model */
            $model->id = Uuid::uuid4()->toString();
        });
    }
}
