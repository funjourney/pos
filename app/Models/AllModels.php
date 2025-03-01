<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Constants extends Model {
    public const PRODUCTS_TYPE = ['food', 'drink'];
    public const PRODUCTS_STATUS = ['available', 'not_available'];
    public const ORDERS_TYPE = ['dine_in', 'take_away', 'reservation'];
    public const ORDERS_STATUS = ['processing', 'canceled', 'completed'];
    public const PAYMENTS_TYPE = ['cashier', 'bank_transfer', 'ewallet', 'virtual_bank', 'qris'];
    public const PAYMENTS_STATUS = ['pending', 'canceled', 'success'];
}


class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'image', 'type', 'status',
    ];

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'products_inventories_many2many');
    }
}

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name', 'image', 'category', 'stock_quantity', 'unit', 'price_per_unit', 'supplier', 'last_restocked',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'products_inventories_many2many');
    }
}

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id', 'product_id', 'type', 'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'order_id', 'user_id', 'total_amount', 'type', 'status',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}