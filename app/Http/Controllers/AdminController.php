<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Guide;
use App\Models\Language;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function logout(Request $request)
    {
        $acc = Account::find(Auth::id());
        $acc->remember_token = NULL;
        $acc->save();
        Auth::logout();
        $request->session()->invalidate();
        Session::flush();
        return redirect('/');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        self::initLocale();
        return view('admin.pages.dashboard', ['shop_total' => Account::where('act_type', 'ETE')->count(), 'guide_total' => Guide::count(), 'product_total' => Product::count()]);
    }

    public function setLocale($locale)
    {
        app()->setLocale($locale);

        if (Auth::check())
        {
            $acc = Account::find(Auth::id());
            $acc->act_lge_id = Language::where('lge_abbreviation', $locale)->first()->lge_id;
            $acc->save();
        }
        return back();
    }
}
