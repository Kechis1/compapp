<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Category
 * 
 * @property int $cey_id
 * @property int $cey_cey_id
 * @property int $iae_id
 * 
 * @property \App\Models\Category $category
 * @property \App\Models\Image $image
 * @property \Illuminate\Database\Eloquent\Collection $categories
 * @property \Illuminate\Database\Eloquent\Collection $languages
 * @property \Illuminate\Database\Eloquent\Collection $parameters
 * @property \Illuminate\Database\Eloquent\Collection $guides
 * @property \Illuminate\Database\Eloquent\Collection $product_categories
 *
 * @package App\Models
 */
class Category extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
	protected $primaryKey = 'cey_id';
	public $timestamps = false;

	protected $casts = [
		'cey_cey_id' => 'int',
		'iae_id' => 'int'
	];

	protected $fillable = [
		'cey_cey_id',
		'iae_id'
	];

	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class, 'cey_cey_id');
	}

	public function image()
	{
		return $this->belongsTo(\App\Models\Image::class, 'iae_id');
	}

	public function categories()
	{
		return $this->hasMany(\App\Models\Category::class, 'cey_cey_id');
	}

	public function languages()
	{
		return $this->belongsToMany(\App\Models\Language::class, 'category_languages', 'cey_id', 'lge_id')
					->withPivot('cle_url', 'cle_name', 'cle_active', 'cle_description');
	}

	public function parameters()
	{
		return $this->belongsToMany(\App\Models\Parameter::class, 'category_parameters', 'cey_id', 'prr_id');
	}

	public function guides()
	{
		return $this->hasMany(\App\Models\Guide::class, 'cey_id');
	}

	public function product_categories()
	{
		return $this->hasMany(\App\Models\ProductCategory::class, 'cey_id');
	}
}
