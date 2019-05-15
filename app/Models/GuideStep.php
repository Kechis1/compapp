<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GuideStep
 * 
 * @property int $gsp_id
 * @property int $gde_id
 * @property int $prr_id
 * @property string $gsp_choice
 * @property bool $gsp_start
 * 
 * @property \App\Models\Guide $guide
 * @property \App\Models\Parameter $parameter
 * @property \Illuminate\Database\Eloquent\Collection $guide_step_choices
 * @property \Illuminate\Database\Eloquent\Collection $languages
 *
 * @package App\Models
 */
class GuideStep extends Eloquent
{
	protected $primaryKey = 'gsp_id';
	public $timestamps = false;

	protected $casts = [
		'gde_id' => 'int',
		'prr_id' => 'int',
		'gsp_start' => 'bool'
	];

	protected $fillable = [
		'gde_id',
		'prr_id',
		'gsp_choice',
		'gsp_start'
	];

	public function guide()
	{
		return $this->belongsTo(\App\Models\Guide::class, 'gde_id');
	}

	public function parameter()
	{
		return $this->belongsTo(\App\Models\Parameter::class, 'prr_id');
	}

	public function guide_next_choices()
	{
		return $this->hasMany(\App\Models\GuideStepChoice::class, 'next_step');
	}

    public function guide_step_choices()
    {
        return $this->hasMany(\App\Models\GuideStepChoice::class, 'gsp_id');
    }

	public function languages()
	{
		return $this->belongsToMany(\App\Models\Language::class, 'guide_step_languages', 'gsp_id', 'lge_id')
					->withPivot('gss_title', 'gss_description');
	}
}
