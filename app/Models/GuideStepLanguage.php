<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GuideStepLanguage
 * 
 * @property int $gsp_id
 * @property int $lge_id
 * @property string $gss_title
 * @property string $gss_description
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\GuideStep $guide_step
 *
 * @package App\Models
 */
class GuideStepLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gsp_id' => 'int',
		'lge_id' => 'int'
	];

	protected $fillable = [
		'gss_title',
		'gss_description'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}

	public function guide_step()
	{
		return $this->belongsTo(\App\Models\GuideStep::class, 'gsp_id');
	}
}
