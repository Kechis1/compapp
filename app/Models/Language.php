<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Language
 * 
 * @property int $lge_id
 * @property string $lge_abbreviation
 * @property string $lge_name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $accounts
 * @property \Illuminate\Database\Eloquent\Collection $categories
 * @property \Illuminate\Database\Eloquent\Collection $guide_choice_languages
 * @property \Illuminate\Database\Eloquent\Collection $guide_steps
 * @property \Illuminate\Database\Eloquent\Collection $guides
 * @property \Illuminate\Database\Eloquent\Collection $parameters
 * @property \Illuminate\Database\Eloquent\Collection $parameter_values
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Language extends Eloquent
{
	protected $primaryKey = 'lge_id';
	public $timestamps = false;

	protected $fillable = [
		'lge_abbreviation',
		'lge_name'
	];

	public function accounts()
	{
		return $this->hasMany(\App\Models\Account::class, 'act_lge_id');
	}

	public function categories()
	{
		return $this->belongsToMany(\App\Models\Category::class, 'category_languages', 'lge_id', 'cey_id')
					->withPivot('cle_url', 'cle_name', 'cle_active', 'cle_description');
	}

	public function guide_choice_languages()
	{
		return $this->hasMany(\App\Models\GuideChoiceLanguage::class, 'lge_id');
	}

	public function guide_steps()
	{
		return $this->belongsToMany(\App\Models\GuideStep::class, 'guide_step_languages', 'lge_id', 'gsp_id')
					->withPivot('gss_title', 'gss_description');
	}

	public function guides()
	{
		return $this->belongsToMany(\App\Models\Guide::class, 'guides_languages', 'lge_id', 'gde_id')
					->withPivot('gle_name', 'gle_active');
	}

	public function parameters()
	{
		return $this->belongsToMany(\App\Models\Parameter::class, 'parameter_languages', 'lge_id', 'prr_id')
					->withPivot('pls_name', 'pls_unit');
	}

	public function parameter_values()
	{
		return $this->belongsToMany(\App\Models\ParameterValue::class, 'parameter_value_languages', 'lge_id', 'pve_id')
					->withPivot('pvs_value', 'pvs_active');
	}

	public function products()
	{
		return $this->belongsToMany(\App\Models\Product::class, 'product_languages', 'lge_id', 'put_id')
					->withPivot('ple_url', 'ple_name', 'ple_active', 'ple_desc_short', 'ple_desc_long');
	}
}
