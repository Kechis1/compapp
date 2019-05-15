<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Delivery
 * 
 * @property int $dly_id
 * @property string $dly_name
 * 
 * @property \Illuminate\Database\Eloquent\Collection $product_enterprise_deliveries
 *
 * @package App\Models
 */
class Delivery extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'deliveries';
	protected $primaryKey = 'dly_id';
	public $timestamps = false;

	protected $fillable = [
		'dly_name'
	];

	public function product_enterprise_deliveries()
	{
		return $this->hasMany(\App\Models\ProductEnterpriseDelivery::class, 'dly_id');
	}
}
