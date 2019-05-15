<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Category;
use App\Models\Delivery;
use App\Models\FeedHistory;
use App\Models\Image;
use App\Models\Language;
use App\Models\Manufacturer;
use App\Models\Parameter;
use App\Models\ProductEnterprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    private const LIMIT = 10;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:shop');
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
        $feed = FeedHistory::where('act_id', Auth::id())->orderBy('fhy_date', 'desc')->first();
        $products = ProductEnterprise::where('act_id', Auth::id())->get()->count();
        return view('shop.pages.dashboard', ['feed' => $feed, 'products' => $products]);
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

    public function profile()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.profile');
        $breadCrumb->active = TRUE;

        return view('shop.pages.profile', ['breadcrumbs' => [$breadCrumb]]);
    }

    public function profileUpdate(Request $request)
    {
        $this->validate($request, [
            'act_email' => 'required|email|string|max:100',
            'amr_first_name' => 'required|string|max:30',
            'amr_last_name' => 'required|string|max:40',
            'new_amr_password' => $request->input('new_amr_password') === NULL ? '' : 'string|min:6',
            'new_password_again' => $request->input('new_amr_password') === NULL ? '' : 'string|min:6|same:new_amr_password',
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

        try
        {
            $exists = Account::where('act_email', $request->input('act_email'))->first();
            if (isset($exists->act_id) && $exists->act_id != Auth::id())
            {
                throw new \Exception();
            }
            $account = Auth::user();
            $account->act_email = $request->input('act_email');
            if (strlen($request->input('new_amr_password')) > 5)
            {
                $account->amr_password = Hash::make($request->input('new_amr_password'));
            }
            $account->amr_first_name = $request->input('amr_first_name');
            $account->amr_last_name = $request->input('amr_last_name');
            $account->ete_tin = $request->input('ete_tin');
            $account->ete_vatin = $request->input('ete_vatin', NULL);
            $account->ete_name = $request->input('ete_name');
            $account->ete_cellnumber = $request->input('ete_cellnumber');
            $account->ete_url_feed = $request->input('ete_url_feed');
            $account->ete_url_web = $request->input('ete_url_web');
            $account->ete_country = $request->input('ete_country');
            $account->ete_street = $request->input('ete_street');
            $account->ete_zip = $request->input('ete_zip');
            $account->ete_city = $request->input('ete_city');
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
                    }
                }
            }
            $account->save();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.account'), 'updated' => __('alerts.successfully_updated')]));
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', __('alerts.unknown_error'));
        }
        finally
        {
            return back();
        }
    }

    public function feed()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.feed');
        $breadCrumb->active = TRUE;

        return view('shop.pages.feed', ['breadcrumbs' => [$breadCrumb]]);
    }

    public function manufacturers()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.manufacturers');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $manufacturers = Manufacturer::orderBy('mur_name');
        $count = $manufacturers->count();
        if ($count > 0)
        {
            $pages = $this->calculatePages($count);
        }
        $manufacturers = $manufacturers
            ->offset($offset)
            ->limit(self::LIMIT)
            ->get();
        if ($pages > 0)
        {
            $first = $page == 1 ? NULL : 1;
            $current = $page;
            $prev = $current - 1 > 1 ? $current - 1 : NULL;
            $next = $current + 1 < $pages ? $current + 1 : NULL;
            $last = $current == $pages ? NULL : $pages;
        }

        return view('shop.pages.manufacturers', ['pagination' => $this->getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'manufacturers' => $manufacturers]);
    }

    public function categories()
    {

        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.category');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $categories = Category::whereNotIn('cey_id', array_column(Category::whereNotNull('cey_cey_id')->distinct()->get()->toArray(), 'cey_cey_id'))->orderBy('cey_cey_id');
        $count = $categories->count();
        if ($count > 0)
        {
            $pages = $this->calculatePages($count);
        }

        $categories = $categories
            ->offset($offset)
            ->limit(self::LIMIT)
            ->get();
        if ($pages > 0)
        {
            $first = $page == 1 ? NULL : 1;
            $current = $page;
            $prev = $current - 1 > 1 ? $current - 1 : NULL;
            $next = $current + 1 < $pages ? $current + 1 : NULL;
            $last = $current == $pages ? NULL : $pages;
        }

        return view('shop.pages.categories', ['pagination' => $this->getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'categories' => $categories]);
    }

    public function deliveries()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.deliveries');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $deliveries = Delivery::orderBy('dly_name');
        $count = $deliveries->count();
        if ($count > 0)
        {
            $pages = $this->calculatePages($count);
        }
        $deliveries = $deliveries
            ->offset($offset)
            ->limit(self::LIMIT)
            ->get();
        if ($pages > 0)
        {
            $first = $page == 1 ? NULL : 1;
            $current = $page;
            $prev = $current - 1 > 1 ? $current - 1 : NULL;
            $next = $current + 1 < $pages ? $current + 1 : NULL;
            $last = $current == $pages ? NULL : $pages;
        }

        return view('shop.pages.deliveries', ['pagination' => $this->getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'deliveries' => $deliveries]);
    }

    private function getPagination($first, $current, $prev, $next, $last)
    {
        return [$first, $current, $prev, $next, $last];
    }

    public function parameters()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.parameters');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $params = Parameter::orderBy('prr_id');
        $count = $params->count();
        if ($count > 0)
        {
            $pages = $this->calculatePages($count);
        }
        $params = $params
            ->offset($offset)
            ->limit(self::LIMIT)
            ->get();
        if ($pages > 0)
        {
            $first = $page == 1 ? NULL : 1;
            $current = $page;
            $prev = $current - 1 > 1 ? $current - 1 : NULL;
            $next = $current + 1 < $pages ? $current + 1 : NULL;
            $last = $current == $pages ? NULL : $pages;
        }

        return view('shop.pages.parameters', ['pagination' => $this->getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'params' => $params]);
    }

    public function products()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.products');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $productEnterprises = Auth::user()->product_enterprises()->orderBy('pee_url');
        $count = $productEnterprises->count();
        if ($count > 0)
        {
            $pages = $this->calculatePages($count);
        }
        $productEnterprises = $productEnterprises
            ->offset($offset)
            ->limit(self::LIMIT)
            ->get();
        if ($pages > 0)
        {
            $first = $page == 1 ? NULL : 1;
            $current = $page;
            $prev = $current - 1 > 1 ? $current - 1 : NULL;
            $next = $current + 1 < $pages ? $current + 1 : NULL;
            $last = $current == $pages ? NULL : $pages;
        }

        return view('shop.pages.products', ['pagination' => $this->getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'products' => $productEnterprises]);
    }

    private function calculatePages($count)
    {
        return ceil($count / self::LIMIT);
    }
}
