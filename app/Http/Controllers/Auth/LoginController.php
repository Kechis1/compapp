<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Language;
use http\Cookie;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:shop');
        $this->middleware('guest:admin');
    }

    public function showShopLoginForm()
    {
        return view('signin', ['amr_active' => 1, 'type' => 'ETE', 'title' => __('pages.signin'), 'page_name' => 'page-signin', 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function shopLogin(Request $request)
    {
        return $this->login($request, 'ETE', 'shop', 'ShopController@index');
    }

    public function showAdminLoginForm()
    {
        return view('signin', ['amr_active' => 1, 'type' => 'AMR', 'title' => __('pages.signin'), 'page_name' => 'page-signin', 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function adminLogin(Request $request)
    {
        return $this->login($request, 'AMR', 'admin', 'AdminController@index');
    }

    private function login(Request $request, string $actType, string $guardName, string $controllerName)
    {
        $this->validate($request, [
            'act_email'   => 'required|email|string|max:100',
            'amr_password' => 'required|string|min:6'
        ]);
        if (Auth::guard($guardName)->attempt(['amr_active' => 1, 'act_type' => $actType, 'act_email' => $request->act_email, 'password' => $request->amr_password], true))
        {
            $act = Account::where('act_email', $request->act_email)->first();
            $act->guard = $guardName;
            return redirect()->intended(action($controllerName));
        }
        $request->session()->flash('error', __('alerts.login_error'));
        return back()->withInput($request->only('act_email'));
    }
}