<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class ProductEnterpriseDelivery
 * 
 * @property int $dly_id
 * @property int $pee_id
 * @property float $pey_price
 * @property float $pey_price_cod
 * 
 * @property \App\Models\ProductEnterprise $product_enterprise
 * @property \App\Models\Delivery $delivery
 *
 * @package App\Models
 */
class ProductEnterpriseDelivery extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_enterprise_deliveries';
	public $incrementing = false;
	public $timestamps = false;

	protected $casts = [
		'dly_id' => 'int',
		'pee_id' => 'int',
		'pey_price' => 'float',
		'pey_price_cod' => 'float'
	];

	protected $fillable = [
		'pey_price',
		'pey_price_cod'
	];

	public function product_enterprise()
	{
		return $this->belongsTo(\App\Models\ProductEnterprise::class, 'pee_id');
	}

	public function delivery()
	{
		return $this->belongsTo(\App\Models\Delivery::class, 'dly_id');
	}
}
