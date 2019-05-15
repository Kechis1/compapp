<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ParameterValue
 * 
 * @property int $pve_id
 * @property int $prr_id
 * 
 * @property \App\Models\Parameter $parameter
 * @property \Illuminate\Database\Eloquent\Collection $choice_values
 * @property \Illuminate\Database\Eloquent\Collection $languages
 * @property \Illuminate\Database\Eloquent\Collection $product_parameters
 *
 * @package App\Models
 */
class ParameterValue extends Eloquent
{
	protected $primaryKey = 'pve_id';
	public $timestamps = false;

	protected $casts = [
		'prr_id' => 'int'
	];

	protected $fillable = [
		'prr_id'
	];

	public function parameter()
	{
		return $this->belongsTo(\App\Models\Parameter::class, 'prr_id');
	}

	public function choice_values()
	{
		return $this->hasMany(\App\Models\ChoiceValue::class, 'pve_id');
	}

	public function languages()
	{
		return $this->belongsToMany(\App\Models\Language::class, 'parameter_value_languages', 'pve_id', 'lge_id')
					->withPivot('pvs_value', 'pvs_active');
	}

	public function product_parameters()
	{
		return $this->hasMany(\App\Models\ProductParameter::class, 'pve_id');
	}
}
