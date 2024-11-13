<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;

class ProductVariations extends Model
{

    /**
     *
     * @var array
     *
     */
    protected $fillable = ['product_id', 'color', 'size', 'quantity', 'availability', 'attributes'];

    /**
     *
     * @var string
     *
     */

     protected $table = 'product_variations';


     /**
      *
      * @var number
      */
     protected $primaryKey = 'id';

     /**
      *
      * @var boolean
      */

      public $incrementing = true;

      public function product()
      {
          return $this->belongsTo(Product::class);
      }
}
