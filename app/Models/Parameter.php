<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Parameter
 * 
 * @property int $prr_id
 * @property bool $prr_numeric
 * 
 * @property \Illuminate\Database\Eloquent\Collection $categories
 * @property \Illuminate\Database\Eloquent\Collection $guide_steps
 * @property \Illuminate\Database\Eloquent\Collection $languages
 * @property \Illuminate\Database\Eloquent\Collection $parameter_values
 *
 * @package App\Models
 */
class Parameter extends Eloquent
{
	protected $primaryKey = 'prr_id';
	public $timestamps = false;

	protected $casts = [
		'prr_numeric' => 'bool'
	];

	protected $fillable = [
		'prr_numeric'
	];

	public function categories()
	{
		return $this->belongsToMany(\App\Models\Category::class, 'category_parameters', 'prr_id', 'cey_id');
	}

	public function guide_steps()
	{
		return $this->hasMany(\App\Models\GuideStep::class, 'prr_id');
	}

	public function languages()
	{
		return $this->belongsToMany(\App\Models\Language::class, 'parameter_languages', 'prr_id', 'lge_id')
					->withPivot('pls_name', 'pls_unit');
	}

	public function parameter_values()
	{
		return $this->hasMany(\App\Models\ParameterValue::class, 'prr_id');
	}
}
