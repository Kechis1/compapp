<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Product
 * 
 * @property int $put_id
 * @property int $mur_id
 * @property string $put_ean
 * 
 * @property \App\Models\Manufacturer $manufacturer
 * @property \Illuminate\Database\Eloquent\Collection $product_categories
 * @property \Illuminate\Database\Eloquent\Collection $product_enterprises
 * @property \Illuminate\Database\Eloquent\Collection $images
 * @property \Illuminate\Database\Eloquent\Collection $languages
 * @property \Illuminate\Database\Eloquent\Collection $product_parameters
 *
 * @package App\Models
 */
class Product extends Eloquent
{
	protected $primaryKey = 'put_id';
	public $timestamps = false;

	protected $casts = [
		'mur_id' => 'int'
	];

	protected $fillable = [
		'mur_id',
		'put_ean'
	];

	public function manufacturer()
	{
		return $this->belongsTo(\App\Models\Manufacturer::class, 'mur_id');
	}

	public function product_categories()
	{
		return $this->hasMany(\App\Models\ProductCategory::class, 'put_id');
	}

	public function product_enterprises()
	{
		return $this->hasMany(\App\Models\ProductEnterprise::class, 'put_id');
	}

	public function images()
	{
		return $this->belongsToMany(\App\Models\Image::class, 'product_images', 'put_id', 'iae_id')
					->withPivot('pie_active');
	}

	public function languages()
	{
		return $this->belongsToMany(\App\Models\Language::class, 'product_languages', 'put_id', 'lge_id')
					->withPivot('ple_url', 'ple_name', 'ple_active', 'ple_desc_short', 'ple_desc_long');
	}

	public function product_parameters()
	{
		return $this->hasMany(\App\Models\ProductParameter::class, 'put_id');
	}
}
