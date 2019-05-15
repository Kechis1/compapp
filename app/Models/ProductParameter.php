<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductParameter
 * 
 * @property int $put_id
 * @property int $pve_id
 * @property bool $ppr_active
 * 
 * @property \App\Models\Product $product
 * @property \App\Models\ParameterValue $parameter_value
 *
 * @package App\Models
 */
class ProductParameter extends Eloquent
{
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'put_id' => 'int',
		'pve_id' => 'int',
		'ppr_active' => 'bool'
	];

	protected $fillable = [
		'ppr_active'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class, 'put_id');
	}

	public function parameter_value()
	{
		return $this->belongsTo(\App\Models\ParameterValue::class, 'pve_id');
	}
}
