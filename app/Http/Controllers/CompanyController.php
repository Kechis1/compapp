<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Language;
use App\Models\ProductEnterprise;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CompanyController extends Controller
{
    private const LIMIT = 10;

    /**
     *
     */
    public function index()
    {
        self::initLocale();
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.companies');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = is_numeric(Input::get('page', 1)) ? Input::get('page', 1) : 1;
        $acts = Account::where('act_type', 'ETE')->orderBy('act_id');
        $count = $acts->count();
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $offset = $offset > $count || $offset < 0 ? 0 : $offset;
        if ($offset == 0)
        {
            $page = 1;
        }
        if ($count > 0)
        {
            $pages = self::calculatePages($count, self::LIMIT);
        }
        $acts = $acts
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

        return view('admin.pages.companies.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'acts' => $acts]);
    }

    /**
     *
     */
    public function create()
    {

    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {

    }

    /**
     * @param Account $company
     */
    public function show(Account $company)
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.companies');
        $breadCrumb->url = action('CompanyController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.detail');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $langActive = Input::get('lang', Language::first()->lge_id);

        $act = Account::findOrFail($company->act_id);

        return view('admin.pages.companies.show', ['lang_active' => $langActive, 'breadcrumbs' => $breadCrumbs, 'act' => $act]);
    }

    /**
     * @param Account $account
     */
    public function edit(Account $account)
    {

    }

    /**
     * @param Request $request
     * @param Account $account
     */
    public function update(Request $request, Account $account)
    {
    }

    public function statusUpdate(Request $request, Account $account)
    {
        try
        {
            $isActivate = $request->input('activate') !== NULL;
            $isDeactivate = $request->input('deactivate') !== NULL;
            if ($isActivate === FALSE && $isDeactivate === FALSE)
            {
                throw new \Exception();
            }
            $acc = Account::where([['act_type', 'ETE'], ['act_id', $account->act_id]])->firstOrFail();
            $acc->amr_active = $isActivate;
            DB::beginTransaction();
            $acc->save();
            if ($isDeactivate)
            {
                ProductEnterprise::where('act_id', $acc->act_id)->update(['pee_active' => 0]);
            }
            DB::commit();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.company'), 'updated' => __('alerts.successfully_updated')]));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            $request->session()->flash('error', __('alerts.unknown_error'));
        }
        finally
        {
            return back();
        }
    }

    public function productUpdate(Request $request, Account $account)
    {
        try
        {
            $checkboxes = $request->input('checkProduct');
            $isActivate = $request->input('productBtn') !== NULL && $request->input('productBtn') == 1;
            $isDeactivate = $request->input('productBtn') !== NULL && $request->input('productBtn') == 2;
            if ($isActivate === FALSE && $isDeactivate === FALSE)
            {
                throw new \Exception();
            }
            ProductEnterprise::whereIn('pee_id', $checkboxes)->update(['pee_active' => $isActivate]);
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.products'), 'updated' => __('alerts.successfully_updated')]));
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

    /**
     * @param Account $account
     */
    public function destroy(Account $account)
    {

    }

    public function destroyReview(Request $request, Account $account)
    {
        try
        {
            $checkboxes = $request->input('checkReview');
            Review::whereIn('rvw_id', $checkboxes)->delete();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.review'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            return back()->with('error', __('alerts.unknown_error'));
        }
    }
}
