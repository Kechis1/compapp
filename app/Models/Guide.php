<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Guide
 * 
 * @property int $gde_id
 * @property int $cey_id
 * 
 * @property \App\Models\Category $category
 * @property \Illuminate\Database\Eloquent\Collection $guide_steps
 * @property \Illuminate\Database\Eloquent\Collection $languages
 *
 * @package App\Models
 */
class Guide extends Eloquent
{
	protected $primaryKey = 'gde_id';
	public $timestamps = false;

	protected $casts = [
		'cey_id' => 'int'
	];

	protected $fillable = [
		'cey_id'
	];

	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class, 'cey_id');
	}

	public function guide_steps()
	{
		return $this->hasMany(\App\Models\GuideStep::class, 'gde_id');
	}

	public function languages()
	{
		return $this->belongsToMany(\App\Models\Language::class, 'guides_languages', 'gde_id', 'lge_id')
					->withPivot('gle_name', 'gle_active');
	}
}
