<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class CategoryParameter
 * 
 * @property int $prr_id
 * @property int $cey_id
 * 
 * @property \App\Models\Category $category
 * @property \App\Models\Parameter $parameter
 *
 * @package App\Models
 */
class CategoryParameter extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'prr_id' => 'int',
		'cey_id' => 'int'
	];

	public function category()
	{
		return $this->belongsTo(\App\Models\Category::class, 'cey_id');
	}

	public function parameter()
	{
		return $this->belongsTo(\App\Models\Parameter::class, 'prr_id');
	}
}
