<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GuidesLanguage
 * 
 * @property int $gde_id
 * @property int $lge_id
 * @property string $gle_name
 * @property bool $gle_active
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\Guide $guide
 *
 * @package App\Models
 */
class GuidesLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gde_id' => 'int',
		'lge_id' => 'int',
		'gle_active' => 'bool'
	];

	protected $fillable = [
		'gle_name',
		'gle_active'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}

	public function guide()
	{
		return $this->belongsTo(\App\Models\Guide::class, 'gde_id');
	}
}
