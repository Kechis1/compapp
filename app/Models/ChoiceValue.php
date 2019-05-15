<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ChoiceValue
 * 
 * @property int $gse_id
 * @property int $pve_id
 * 
 * @property \App\Models\ParameterValue $parameter_value
 * @property \App\Models\GuideStepChoice $guide_step_choice
 *
 * @package App\Models
 */
class ChoiceValue extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gse_id' => 'int',
		'pve_id' => 'int'
	];

	public function parameter_value()
	{
		return $this->belongsTo(\App\Models\ParameterValue::class, 'pve_id');
	}

	public function guide_step_choice()
	{
		return $this->belongsTo(\App\Models\GuideStepChoice::class, 'gse_id');
	}
}
