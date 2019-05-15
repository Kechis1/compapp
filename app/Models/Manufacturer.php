<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Manufacturer
 * 
 * @property int $mur_id
 * @property string $mur_name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $products
 *
 * @package App\Models
 */
class Manufacturer extends Eloquent
{
	protected $primaryKey = 'mur_id';
	public $timestamps = false;

	protected $fillable = [
		'mur_name'
	];

	public function products()
	{
		return $this->hasMany(\App\Models\Product::class, 'mur_id');
	}
}
