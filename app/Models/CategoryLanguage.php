<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CategoryLanguage
 * 
 * @property int $cey_id
 * @property int $lge_id
 * @property string $cle_url
 * @property string $cle_name
 * @property bool $cle_active
 * @property string $cle_description
 * 
 * @property \App\Models\Language $language
 * @property \App\Models\Category $category
 *
 * @package App\Models
 */
class CategoryLanguage extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'cey_id' => 'int',
		'lge_id' => 'int',
		'cle_active' => 'bool'
	];

	protected $fillable = [
		'cle_url',
		'cle_name',
		'cle_active',
		'cle_description'
	];

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'lge_id');
	}

	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class, 'cey_id');
	}
}
