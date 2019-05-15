<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ParameterLanguage
 * 
 * @property int $prr_id
 * @property int $lge_id
 * @property string $pls_name
 * @property string $pls_unit
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\Parameter $parameter
 *
 * @package App\Models
 */
class ParameterLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'prr_id' => 'int',
		'lge_id' => 'int'
	];

	protected $fillable = [
		'pls_name',
		'pls_unit'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}

	public function parameter()
	{
		return $this->belongsTo(\App\Models\Parameter::class, 'prr_id');
	}
}
