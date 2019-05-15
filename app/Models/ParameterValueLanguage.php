<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ParameterValueLanguage
 * 
 * @property int $pve_id
 * @property int $lge_id
 * @property string $pvs_value
 * @property bool $pvs_active
 * 
 * @property \App\Models\ParameterValue $parameter_value
 * @property \App\Models\Language $language
 *
 * @package App\Models
 */
class ParameterValueLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'pve_id' => 'int',
		'lge_id' => 'int',
		'pvs_active' => 'bool'
	];

	protected $fillable = [
		'pvs_value',
		'pvs_active'
	];

	public function parameter_value()
	{
		return $this->belongsTo(\App\Models\ParameterValue::class, 'pve_id');
	}

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}
}
