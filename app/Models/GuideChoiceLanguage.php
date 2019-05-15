<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GuideChoiceLanguage
 * 
 * @property int $gse_id
 * @property int $lge_id
 * @property string $gce_pros
 * @property string $gce_cons
 * @property string $gce_title
 * @property string $gce_description
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\GuideStepChoice $guide_step_choice
 *
 * @package App\Models
 */
class GuideChoiceLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'gse_id' => 'int',
		'lge_id' => 'int'
	];

	protected $fillable = [
		'gce_pros',
		'gce_cons',
		'gce_title',
		'gce_description'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}

	public function guide_step_choice()
	{
		return $this->belongsTo(\App\Models\GuideStepChoice::class, 'gse_id');
	}
}
