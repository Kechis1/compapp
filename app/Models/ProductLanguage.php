<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductLanguage
 * 
 * @property int $put_id
 * @property int $lge_id
 * @property string $ple_url
 * @property string $ple_name
 * @property bool $ple_active
 * @property string $ple_desc_short
 * @property string $ple_desc_long
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\Product $product
 * @property \Illuminate\Database\Eloquent\Collection $reviews
 *
 * @package App\Models
 */
class ProductLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'put_id' => 'int',
		'lge_id' => 'int',
		'ple_active' => 'bool'
	];

	protected $fillable = [
		'ple_url',
		'ple_name',
		'ple_active',
		'ple_desc_short',
		'ple_desc_long'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class, 'put_id');
	}

	public function reviews()
	{
		return $this->hasMany(\App\Models\Review::class, 'put_id');
	}
}
