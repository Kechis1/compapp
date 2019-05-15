<?php

namespace App\Http\Controllers;

use App\Mail\AccountForgotPassword;
use App\Models\Account;
use App\Models\Image;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public static function generateBytes(int $length): string
    {
        return bin2hex(random_bytes($length));
    }

    public function refreshPassword(Request $request, $actId, $amrCodeRefresh)
    {
        $this->validate($request, [
            'amr_password' => 'required|string|min:6',
            'password_again' => 'required|string|min:6|same:amr_password',
        ]);
        try
        {
            $act = Account::findOrFail($actId)->where('amr_code_refresh', $amrCodeRefresh)->firstOrFail();
            $act->amr_code_refresh = null;
            $act->amr_password = Hash::make($request->input('amr_password'));
            $act->save();
            $request->session()->flash('success', __('alerts.password_refresh'));
            return redirect()->action('Auth\LoginController@showShopLoginForm');
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', __('alerts.unknown_error'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function forgotPassword(Request $request)
    {
        $this->validate($request, [
            'act_email' => 'required|email|string|max:100|exists:accounts,act_email'
        ]);
        try
        {
            $act = Account::where([['act_type', '<>', 'UER'],['act_email', $request->input('act_email')]])->firstOrFail();
            $act->amr_code_refresh = $this->generateBytes(32);
            $act->save();
            Mail::to($act->act_email)->send(new AccountForgotPassword($act));
            $request->session()->flash('success', __('alerts.password_refresh_email'));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', __('alerts.unknown_error'));
        }
        finally
        {
            return redirect()->action('PagesController@shopForgotPassword');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function signUp(Request $request)
    {
        $this->validate($request, [
            'act_email' => 'required|email|string|max:100',
            'amr_password' => 'required|string|min:6',
            'password_again' => 'required|string|min:6|same:amr_password',
            'amr_first_name' => 'required|string|max:30',
            'amr_last_name' => 'required|string|max:40',
            'ete_cellnumber' => 'required|max:20',
            'ete_name' => 'required|max:100',
            'ete_url_web' => 'required|string|max:100',
            'ete_url_feed' => 'required|string|max:200',
            'ete_tin' => 'required|max:15',
            'ete_vatin' => 'max:25',
            'ete_country' => 'required|string|max:60',
            'ete_street' => 'required|string|max:60',
            'ete_city' => 'required|string|max:60',
            'ete_zip' => 'required|max:12'
        ]);

        $account = new Account();
        $account->act_email = $request->input('act_email');
        $account->act_type = 'ETE';
        $account->act_date_created = now();
        $account->act_iae_id = NULL;
        $account->amr_password = Hash::make($request->input('amr_password'));
        $account->amr_active = FALSE;
        $account->amr_code_refresh = NULL;
        $account->amr_first_name = $request->input('amr_first_name');
        $account->amr_last_name = $request->input('amr_last_name');
        $account->ete_tin = $request->input('ete_tin');
        $account->ete_vatin = $request->input('ete_vatin', null);
        $account->ete_name = $request->input('ete_name');
        $account->ete_cellnumber = $request->input('ete_cellnumber');
        $account->ete_url_feed = $request->input('ete_url_feed');
        $account->ete_url_web = $request->input('ete_url_web');
        $account->ete_country = $request->input('ete_country');
        $account->ete_street = $request->input('ete_street');
        $account->ete_zip = $request->input('ete_zip');
        $account->ete_city = $request->input('ete_city');
        $account->act_lge_id = Language::where('lge_abbreviation', App::getLocale())->first()->lge_id;
        $exist = Account::where('act_email', $account->act_email)->first();
        try
        {
            if (!(!isset($exist->act_id) || $exist->act_type != 'ETE'))
            {
                throw new \Exception();
            }
            $account->save();
            if ($request->iae_image !== NULL)
            {
                $request->iae_image->store('public');
                if ($request->file('iae_image')->isValid())
                {
                    $origExt = $request->iae_image->getClientOriginalExtension();
                    $origPath = $request->iae_image->getClientOriginalName();
                    $name = substr_replace($origPath, "", strrpos($origPath, $origExt) - 1, strlen($origExt) + 1);
                    $ext = $request->iae_image->extension();
                    $path = substr_replace($request->iae_image->hashName(), "", strrpos($request->iae_image->hashName(), $ext) - 1, strlen($ext) + 1);
                    $size = $request->iae_image->getSize() / 1000;
                    $image = new Image();
                    $image->iae_path = $path;
                    $image->iae_name = $name;
                    $image->iae_size = $size;
                    $image->iae_type = $ext;
                    $existImg = Image::where('iae_path', $image->iae_path)->first();
                    if (!isset($existImg->iae_id))
                    {
                        $image->save();
                        $account->act_iae_id = $image->iae_id;
                        $account->save();
                    }
                }
            }
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.account'), 'created' => __('alerts.successfully_created')]));
            $request->session()->flash('info', __('alerts.account_create_info'));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', __('alerts.already_created', ['object' => __('alerts.account'), 'already_created' => __('alerts.already_created_msg')]));
        }
        finally
        {
            return redirect()->action('PagesController@shopSignUp');
        }
    }
}
