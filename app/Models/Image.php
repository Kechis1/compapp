<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Image
 * 
 * @property int $iae_id
 * @property string $iae_path
 * @property string $iae_name
 * @property float $iae_size
 * @property string $iae_type
 * 
 * @property \Illuminate\Database\Eloquent\Collection $accounts
 * @property \Illuminate\Database\Eloquent\Collection $categories
 * @property \Illuminate\Database\Eloquent\Collection $guide_step_choices
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Image extends Eloquent
{
	protected $primaryKey = 'iae_id';
	public $timestamps = false;

	protected $casts = [
		'iae_size' => 'float'
	];

	protected $fillable = [
		'iae_path',
		'iae_name',
		'iae_size',
		'iae_type'
	];

	public function accounts()
	{
		return $this->hasMany(\App\Models\Account::class, 'act_iae_id');
	}

	public function categories()
	{
		return $this->hasMany(\App\Models\Category::class, 'iae_id');
	}

	public function guide_step_choices()
	{
		return $this->hasMany(\App\Models\GuideStepChoice::class, 'iae_id');
	}

	public function products()
	{
		return $this->belongsToMany(\App\Models\Product::class, 'product_images', 'iae_id', 'put_id')
					->withPivot('pie_active');
	}
}
