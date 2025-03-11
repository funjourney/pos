<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Categorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = ['id', 'name', 'path_image', 'status'];

    protected $casts = [
        'id' => 'string',
        'name' => 'string',
        'path_image' => 'string',
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
