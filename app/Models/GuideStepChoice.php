<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class GuideStepChoice
 * 
 * @property int $gse_id
 * @property int $gsp_id
 * @property bool $gse_default
 * @property int $next_step
 * @property int $iae_id
 * @property float $gse_min
 * @property float $gse_max
 * 
 * @property \App\Models\GuideStep $guide_step
 * @property \App\Models\Image $image
 * @property \Illuminate\Database\Eloquent\Collection $choice_values
 * @property \Illuminate\Database\Eloquent\Collection $guide_choice_languages
 *
 * @package App\Models
 */
class GuideStepChoice extends Eloquent
{
	protected $primaryKey = 'gse_id';
	public $timestamps = false;

	protected $casts = [
		'gsp_id' => 'int',
		'gse_default' => 'bool',
		'next_step' => 'int?',
		'iae_id' => 'int?',
		'gse_min' => 'float?',
		'gse_max' => 'float?'
	];

	protected $fillable = [
		'gsp_id',
		'gse_default',
		'next_step',
		'iae_id',
		'gse_min',
		'gse_max'
	];

	public function guide_next_step()
	{
		return $this->belongsTo(\App\Models\GuideStep::class, 'next_step');
	}

    public function guide_step()
    {
        return $this->belongsTo(\App\Models\GuideStep::class, 'gsp_id');
    }

	public function image()
	{
		return $this->belongsTo(\App\Models\Image::class, 'iae_id');
	}

	public function choice_values()
	{
		return $this->hasMany(\App\Models\ChoiceValue::class, 'gse_id');
	}

	public function guide_choice_languages()
	{
		return $this->hasMany(\App\Models\GuideChoiceLanguage::class, 'gse_id');
	}
}
