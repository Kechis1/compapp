<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Reliese\Database\Eloquent\Model as Eloquent;

/**
 * Class FeedHistory
 * 
 * @property int $fhy_id
 * @property int $act_id
 * @property \Carbon\Carbon $fhy_date
 * @property string $fhy_status
 * @property string $fhy_message
 * 
 * @property \App\Models\Account $account
 *
 * @package App\Models
 */
class FeedHistory extends Eloquent
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'feed_histories';
	protected $primaryKey = 'fhy_id';
	public $timestamps = false;

	protected $casts = [
		'act_id' => 'int'
	];

	protected $dates = [
		'fhy_date'
	];

	protected $fillable = [
		'act_id',
		'fhy_date',
		'fhy_status',
		'fhy_message'
	];

	public function account()
	{
		return $this->belongsTo(\App\Models\Account::class, 'act_id');
	}
}
