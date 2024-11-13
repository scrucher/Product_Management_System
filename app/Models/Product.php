<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ProductVariations;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'sku',
        'status',
        'price',
        'quantity',
        'currency',
    ];

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'sku' => 'string',
        'status' => 'string',
        'currency' => 'string',
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The primary key associated with the table.
     *
     * @var number
     */
    protected $primaryKey = 'id';

    /**
     * The "type" of the primary key ID.
     *
     * @var number
     */
    protected $keyType = 'number';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var boolean
     */
    public $incrementing = true;

    /**
     * Get the product variations for the product.
     */

    public function product_variations()
    {
        return $this->hasMany(ProductVariations::class);
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',];

}
