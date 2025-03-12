<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'payments';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 
        'order_id', 
        'user_id', 
        'total_amount', 
        'type',
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'order_id' => 'string',
        'user_id' => 'string',
        'total_amount' => 'decimal:2',
        'type' => 'string',
        'status' => 'string',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    public function getImageUrlAttribute()
    {
        return $this->path_image ? asset('storage/' . $this->path_image) : null;
    }

    /**
     * Override method toArray untuk mengubah snake_case menjadi camelCase.
     */
    public function toArray()
    {
        $array = parent::toArray();

        return collect($array)->mapWithKeys(function ($value, $key) {
            return [Str::camel($key) => $value];
        })->all();
    }

}
