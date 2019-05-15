<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductEnterprise
 * 
 * @property int $pee_id
 * @property int $put_id
 * @property int $act_id
 * @property string $pee_url
 * @property float $pee_price
 * @property float $pee_availability
 * @property bool $pee_active
 * 
 * @property \App\Models\Product $product
 * @property \App\Models\Account $account
 * @property \Illuminate\Database\Eloquent\Collection $product_enterprise_deliveries
 *
 * @package App\Models
 */
class ProductEnterprise extends Eloquent
{
	protected $primaryKey = 'pee_id';
	public $timestamps = false;

	protected $casts = [
		'put_id' => 'int',
		'act_id' => 'int',
		'pee_price' => 'float',
		'pee_availability' => 'float',
		'pee_active' => 'bool'
	];

	protected $fillable = [
		'put_id',
		'act_id',
		'pee_url',
		'pee_price',
		'pee_availability',
		'pee_active'
	];

	public function product()
	{
		return $this->belongsTo(\App\Models\Product::class, 'put_id');
	}

	public function account()
	{
		return $this->belongsTo(\App\Models\Account::class, 'act_id');
	}

	public function product_enterprise_deliveries()
	{
		return $this->hasMany(\App\Models\ProductEnterpriseDelivery::class, 'pee_id');
	}
}
