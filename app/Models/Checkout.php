<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Checkout extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'checkouts';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 
        'order_id', 
        'product_id', 
        'product_name', 
        'product_price', 
        'product_quantity'
    ];

    protected $casts = [
        'id' => 'string',
        'order_id' => 'string',
        'product_id' => 'string',
        'product_name' => 'string',
        'product_price' => 'decimal:2',
        'product_quantity' => 'decimal:2',
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
