<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class Review
 * 
 * @property int $rvw_id
 * @property int $act_id
 * @property string $rvw_title
 * @property string $rvw_message
 * @property float $rvw_rating
 * @property \Carbon\Carbon $rvw_date_created
 * @property int $put_id
 * @property int $lge_id
 * @property int $ete_act_id
 * @property string $rvw_pros
 * @property string $rvw_cons
 * 
 * @property \App\Models\Account $account
 * @property \App\Models\ProductLanguage $product_language
 *
 * @package App\Models
 */
class Review extends Eloquent
{
	protected $primaryKey = 'rvw_id';
	public $timestamps = false;

	protected $casts = [
		'act_id' => 'int',
		'rvw_rating' => 'float',
		'put_id' => 'int',
		'lge_id' => 'int',
		'ete_act_id' => 'int'
	];

	protected $dates = [
		'rvw_date_created'
	];

	protected $fillable = [
		'act_id',
		'rvw_title',
		'rvw_message',
		'rvw_rating',
		'rvw_date_created',
		'put_id',
		'lge_id',
		'ete_act_id',
		'rvw_pros',
		'rvw_cons'
	];

    public function user()
    {
        return $this->belongsTo(\App\Models\Account::class, 'act_id');
    }

	public function account()
	{
		return $this->belongsTo(\App\Models\Account::class, 'ete_act_id');
	}

	public function product_language()
	{
		return $this->belongsTo(\App\Models\ProductLanguage::class, 'put_id')
					->where('product_languages.put_id', '=', 'reviews.put_id')
					->where('product_languages.lge_id', '=', 'reviews.lge_id');
	}
}
