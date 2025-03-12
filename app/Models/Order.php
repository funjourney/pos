<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id', 
        'user_id', 
        'type', 
        'status'
    ];

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
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

    public function checkouts()
    {
        Log::info('Fetching checkouts for category: ' . $this->id);
        return $this->hasMany(Checkout::class, 'order_id', 'id');
    }

    public function payments()
    {
        Log::info('Fetching payments for category: ' . $this->id);
        return $this->hasMany(Payment::class, 'order_id', 'id');
    }

}
