<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductImage
 * 
 * @property int $iae_id
 * @property int $put_id
 * @property bool $pie_active
 * 
 * @property \App\Models\Image $image
 * @property \App\Models\Product $product
 *
 * @package App\Models
 */
class ProductImage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'iae_id' => 'int',
		'put_id' => 'int',
		'pie_active' => 'bool'
	];

	protected $fillable = [
		'pie_active'
	];

	public function image()
	{
		return $this->belongsTo(\App\Models\Image::class, 'iae_id');
	}

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class, 'put_id');
	}
}
