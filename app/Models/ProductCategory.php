<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductCategory
 * 
 * @property int $cey_id
 * @property int $put_id
 * @property bool $pcy_active
 * 
 * @property \App\Models\Category $category
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class ProductCategory extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_categories';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cey_id' => 'int',
		'put_id' => 'int',
		'pcy_active' => 'bool'
	];

	protected $fillable = [
		'pcy_active'
	];

	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class, 'cey_id');
	}

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class, 'put_id');
	}
}
