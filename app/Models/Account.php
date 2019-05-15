<?php

/**
 * Created by Reliese Model.
 * Date: Sun, 28 Apr 2019 17:20:57 +0000.
 */

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Account
 * 
 * @property int $act_id
 * @property int $act_lge_id
 * @property string $act_email
 * @property string $act_type
 * @property \Carbon\Carbon $act_date_created
 * @property int $act_iae_id
 * @property string $amr_password
 * @property bool $amr_active
 * @property string $amr_code_refresh
 * @property string $amr_first_name
 * @property string $amr_last_name
 * @property string $ete_tin
 * @property string $ete_vatin
 * @property string $ete_name
 * @property string $ete_cellnumber
 * @property string $ete_url_feed
 * @property string $ete_url_web
 * @property string $ete_country
 * @property string $ete_street
 * @property string $ete_zip
 * @property string $ete_city
 * 
 * @property \App\Models\Image $image
 * @property \App\Models\Language $language
 * @property \Illuminate\Database\Eloquent\Collection $feed_histories
 * @property \Illuminate\Database\Eloquent\Collection $product_enterprises
 * @property \Illuminate\Database\Eloquent\Collection $reviews
 *
 * @package App\Models
 */
class Account extends Authenticatable
{
    use Notifiable;

    protected $guard = 'portal';
	protected $primaryKey = 'act_id';

	public $timestamps = false;

	protected $casts = [
		'act_lge_id' => 'int',
		'act_iae_id' => 'int',
		'amr_active' => 'bool'
	];

	protected $dates = [
		'act_date_created'
	];

	protected $hidden = [
		'amr_password', 'remember_token'
	];

	protected $fillable = [
		'act_lge_id',
		'act_email',
		'act_type',
		'act_date_created',
		'act_iae_id',
		'amr_password',
		'amr_active',
		'amr_code_refresh',
		'amr_first_name',
		'amr_last_name',
		'ete_tin',
		'ete_vatin',
		'ete_name',
		'ete_cellnumber',
		'ete_url_feed',
		'ete_url_web',
		'ete_country',
		'ete_street',
		'ete_zip',
		'ete_city',
        'remember_token'
	];

	public function image()
	{
		return $this->belongsTo(\App\Models\Image::class, 'act_iae_id');
	}

	public function language()
	{
		return $this->belongsTo(\App\Models\Language::class, 'act_lge_id');
	}

	public function feed_histories()
	{
		return $this->hasMany(\App\Models\FeedHistory::class, 'act_id');
	}

	public function product_enterprises()
	{
		return $this->hasMany(\App\Models\ProductEnterprise::class, 'act_id');
	}

	public function reviews()
	{
		return $this->hasMany(\App\Models\Review::class, 'ete_act_id');
	}

    public function getAuthPassword()
    {
        return $this->amr_password;
    }
}
