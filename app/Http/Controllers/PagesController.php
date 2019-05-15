<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Request;

class PagesController extends Controller
{
    private const SORT = ["sort_name", "sort_price_min", "sort_price_max"];
    private const LIMIT = 10;

    private const SQL_HOME_SELECT_CATEGORIES = "SELECT * FROM categories JOIN category_languages USING (cey_id) WHERE lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) AND cle_active = true and cey_id IN (select cey_id from categories where cey_cey_id in (select cey_id from categories where cey_cey_id is null)or cey_cey_id is null)";

    private const SQL_CATEGORY_SELECT_CATEGORIES = "select * from categories JOIN category_languages USING (cey_id) left join images using (iae_id) where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and cle_active = true AND cey_cey_id = (SELECT cey_id FROM category_languages WHERE cle_url = ? and cle_active = true and lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?))";
    private const SQL_CATEGORY_SELECT_CATEGORIES_PARENT_BY_URL = "select * from categories JOIN category_languages USING (cey_id) where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) AND cle_url = ?";
    private const SQL_CATEGORY_SELECT_CATEGORIES_PARENT_BY_CEY_CEY_ID = "select * from categories JOIN category_languages USING (cey_id) where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) AND cey_id = ?";
    private const SQL_CATEGORY_SELECT_PRODUCTS = "select (select count(*) from (select count(distinct put_id) from products put1 JOIN product_languages ple1 USING (put_id) join product_categories pce1 using (put_id) join product_enterprises pee1 using (put_id) JOIN product_parameters ppr1 USING (put_id) where ple1.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and cey_id = (select cey_id from category_languages where cle_url = ? and lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?)) and pee1.pee_active = true and ple1.ple_active = true AND ppr1.ppr_active = true and pce1.pcy_active = true %s %s %s %s group by put_id %s) t) count, put.put_id, ple.ple_url, ple.ple_desc_short, ple.ple_name, min(pee_price) pee_price_min, max(pee_price) pee_price_max, (select count(rvw_id) from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and put_id = ple.put_id) reviews, (select count(put_id) from product_enterprises where put_id = ple.put_id and product_enterprises.pee_active = true) shops, (select concat(iae_path,'.',iae_type) from images join product_images using (iae_id) where put_id = put.put_id and product_images.pie_active = true limit 1) image, (select (sum(rvw_rating) / count(rvw_rating)) * 20 from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and put_id = ple.put_id) rating from products put JOIN product_languages ple USING (put_id) join product_categories pce using (put_id) join product_enterprises pee using (put_id) JOIN product_parameters ppr USING (put_id) where ple.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and cey_id = (select cey_id from category_languages where cle_url = ? and lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?)) and pee.pee_active = true and ple.ple_active = true AND ppr.ppr_active = true and pcy_active = true %s %s %s %s group by ple.put_id, put.put_id, ple.ple_url, ple.ple_desc_short, ple.ple_name %s order by %s limit ?,?";

    private const SQL_SEARCH_SELECT_PRODUCTS = "select MATCH(ple_name) AGAINST (? IN NATURAL LANGUAGE MODE) AS score, (select count(*) from (select count(distinct put_id) from products put1 JOIN product_languages ple1 USING (put_id) join product_enterprises pee1 using (put_id) JOIN product_parameters ppr1 USING (put_id) where ple1.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and pee1.pee_active = true and ple1.ple_active = true AND ppr1.ppr_active = true and MATCH(ple_name) AGAINST (?) %s %s %s %s group by put_id %s) t) count, put.put_id, ple.ple_url, ple.ple_desc_short, ple.ple_name, min(pee_price) pee_price_min, max(pee_price) pee_price_max, (select count(rvw_id) from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and put_id = ple.put_id) reviews, (select count(put_id) from product_enterprises where put_id = ple.put_id and product_enterprises.pee_active = true) shops, (select concat(iae_path,'.',iae_type) from images join product_images using (iae_id) where put_id = put.put_id and product_images.pie_active = true limit 1) image, (select (sum(rvw_rating) / count(rvw_rating)) * 20 from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and put_id = ple.put_id) rating from products put JOIN product_languages ple USING (put_id) join product_enterprises pee using (put_id) JOIN product_parameters ppr USING (put_id) where ple.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and pee.pee_active = true and ple.ple_active = true AND ppr.ppr_active = true and MATCH(ple_name) AGAINST (?) %s %s %s %s group by ple.put_id, put.put_id, ple.ple_url, ple.ple_desc_short, ple.ple_name %s order by %s limit ?,?";

    private const SQL_OFFERS_FIND_PRODUCT_BY_URL = "select put.put_id, ple.ple_url, ple.ple_desc_short, ple.ple_desc_long, ple.ple_name, min(pee_price) pee_price_min, (select count(rvw_id) from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and put_id = ple.put_id) reviews, (select concat(iae_path,'.',iae_type) from images join product_images using (iae_id) where put_id = put.put_id and product_images.pie_active = true limit 1) image, (select (sum(rvw_rating) / count(rvw_rating)) * 20 from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and put_id = ple.put_id) rating from products put JOIN product_languages ple USING (put_id) join product_enterprises pee using (put_id) where ple.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and pee.pee_active = true and ple.ple_active = true and ple.ple_url = ? group by put.put_id,ple.put_id,ple.ple_url, ple.ple_desc_short, ple.ple_desc_long, ple.ple_name";
    private const SQL_OFFERS_SELECT_OFFERS = "select (select count(distinct pee_id) from product_enterprises pee JOIN accounts act using (act_id) LEFT JOIN product_enterprise_deliveries pey USING (pee_id) where put_id = ? and pee_active = true) count, act_id, pee_url, pee_price, pee_availability, ete_name, ete_url_web, (select count(rvw_id) from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and ete_act_id = act.act_id) reviews, (select (sum(rvw_rating) / count(rvw_rating)) * 20 from reviews where lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and ete_act_id = act.act_id) rating, (select concat(iae_path, '.', iae_type) from images where iae_id = act.act_iae_id) image, min(pey.pey_price) pey_price, min(pey.pey_price_cod) pey_price_cod from product_enterprises pee JOIN accounts act using (act_id) LEFT JOIN product_enterprise_deliveries pey USING (pee_id) where put_id = ? and pee_active = true GROUP BY pee_url, pee_price, pee_availability, ete_name, ete_url_web, image, act.act_id, pee.act_id LIMIT ?,?";
    private const SQL_OFFERS_SELECT_PARAMS = "select ple.pls_name, ple.pls_unit, pvl.pvs_value from product_parameters join parameter_values pve using (pve_id) join parameter_languages ple using (prr_id) join parameter_value_languages pvl using (pve_id) where ple.lge_id = (select lge_id from languages where languages.lge_abbreviation = ?) and pvl.pvs_active = true and pvl.lge_id = (select lge_id from languages where languages.lge_abbreviation = ?) and ppr_active = true and put_id = ?";
    private const SQL_OFFERS_SELECT_CATEGORIES = "SELECT cey_id, categories.cey_cey_id, category_languages.cle_url as url, category_languages.cle_name as name FROM category_languages JOIN product_categories USING (cey_id) JOIN product_languages USING (put_id) JOIN categories USING (cey_id) WHERE product_languages.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) AND ple_url = ? AND pcy_active = TRUE AND ple_active = TRUE AND cle_active = TRUE AND category_languages.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?)";
    private const SQL_OFFERS_SELECT_CATEGORIES_BY_CEY_CEY_ID = "SELECT cey_id FROM categories WHERE cey_cey_id = ?";
    private const SQL_OFFERS_FIND_CATEGORY = "SELECT cey_id, cey_cey_id, cle_name as name, cle_url as url FROM categories JOIN category_languages using (cey_id) JOIN product_categories USING (cey_id) JOIN product_languages using (put_id) where category_languages.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) and product_languages.lge_id = (SELECT lge_id FROM languages WHERE lge_abbreviation = ?) AND ple_url = ? AND ple_active = true AND cey_id = ? AND cle_active = true and pcy_active = true";
    private const SQL_OFFERS_SELECT_REVIEWS_BY_PUT_ID = "select (select COUNT(*) from reviews join accounts using (act_id) where put_id = ? and lge_id = (select lge_id from languages where lge_abbreviation = ?)) as count, rvw_title, rvw_message, rvw_rating * 20 rvw_rating, rvw_date_created, rvw_pros, rvw_cons, act_email from reviews join accounts using (act_id) where put_id = ? and lge_id = (select lge_id from languages where lge_abbreviation = ?) LIMIT ?, ?";

    public function home()
    {
        $title = __('pages.home');
        $pageName = 'page-home';
        $categories = DB::select(self::SQL_HOME_SELECT_CATEGORIES, [App::getLocale()]);
        $catList = [];
        foreach ($categories as $cat)
        {
            if ($cat->cey_cey_id === NULL)
            {
                $catList[$cat->cey_id] = $cat;
            }
            else
            {
                $catList[$cat->cey_cey_id]->items[] = $cat;
            }
        }
        return view('portal.pages.home', ['page_name' => $pageName, 'categories' => $catList, 'title' => $title, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function category($cleUrl)
    {
        $title = __('pages.category');
        $pageName = 'page-category';
        $order = $this->getOrder();

        $query = Request::query();
        $f = $a = $m = $pn = $px = NULL;
        $fCount = 0;
        foreach ($query as $key => $value)
        {
            if (preg_match('/m:(.*?);/', $key, $match) == 1)
            {
                $m = implode(',', array_map('intval', explode(',', $match[1])));
            }
            if (preg_match('/a:true;/', $key, $match) == 1)
            {
                $a = TRUE;
            }
            if (preg_match('/pn:(.*?);/', $key, $match) == 1)
            {
                $pn = $match[1];
            }
            if (preg_match('/px:(.*?);/', $key, $match) == 1)
            {
                $px = $match[1];
            }
            if (preg_match('/(?<=f:).*([0-9]:)(.*?)(?=;)/', $key, $match) == 1)
            {
                $f = [];
                foreach (explode(';', $match[0]) as $item)
                {
                    $fCount++;
                    $tmp = explode(',', substr($item, strpos($item, ":") + 1));
                    foreach ($tmp as $t)
                    {
                        array_push($f, $t);
                    }
                }

                $f = implode(',', array_map('intval', $f));
            }
        }

        $murIdQuery = $m !== NULL && strlen($m) > 0 ? "AND mur_id IN ({$m})" : "";
        $availabilityQuery = $a !== NULL && $a ? "AND pee_availability = 0" : "";
        $paramsQuery = $f !== NULL && strlen($f) > 0 ? "AND (pve_id IN ({$f}))" : "";
        $paramsHavingQuery = $fCount > 0 ? "HAVING count(distinct pve_id) >= {$fCount}" : "";
        $pricesQuery = $pn !== NULL && $px !== NULL ? "AND pee_price BETWEEN " . intval($pn) . " AND " . intval($px) : "";
        $categories = DB::select(self::SQL_CATEGORY_SELECT_CATEGORIES, [App::getLocale(), $cleUrl, App::getLocale()]);
        $bread = DB::selectOne(self::SQL_CATEGORY_SELECT_CATEGORIES_PARENT_BY_URL, [App::getLocale(), $cleUrl]);
        $breadList = [];
        if (is_object($bread))
        {
            $ceyCeyId = $bread->cey_cey_id;
            $bread->active = TRUE;
            array_push($breadList, $bread);
            while ($ceyCeyId !== NULL)
            {
                $parent = DB::selectOne(self::SQL_CATEGORY_SELECT_CATEGORIES_PARENT_BY_CEY_CEY_ID, [App::getLocale(), $ceyCeyId]);
                $parent->active = FALSE;
                array_unshift($breadList, $parent);
                $ceyCeyId = $parent->cey_cey_id;
            }
        }
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $products = DB::select(sprintf(self::SQL_CATEGORY_SELECT_PRODUCTS, $pricesQuery, $murIdQuery, $availabilityQuery, $paramsQuery, $paramsHavingQuery, $pricesQuery, $murIdQuery, $availabilityQuery, $paramsQuery, $paramsHavingQuery, $order), [App::getLocale(), $cleUrl, App::getLocale(), App::getLocale(), App::getLocale(), App::getLocale(), $cleUrl, App::getLocale(), $offset, self::LIMIT]);

        if (count($products) > 0)
            $pages = $this->calculatePages($products[0]->count);
        else $pages = 0;

        preg_match('/(.*?)(category\/)/', Request::fullUrl(), $paramUrlMatch);
        $paramUrl = str_replace($paramUrlMatch[0], '', Request::fullUrl());

        return view('portal.pages.category', ["paramController" => 'ProductParametersController@getProductParametersByCleUrl', "paramUrl" => $paramUrl, "offset" => $offset, "limit" => self::LIMIT, "sort_selected" => $this->getSortSelected(), 'sort' => self::SORT, 'pages' => $pages, 'products' => $products, 'bread_list' => $breadList, 'page_name' => $pageName, 'url' => $cleUrl, 'categories' => $categories, 'title' => $title, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function offers($url)
    {
        $title = 'Offers';
        $pageName = "page-offers";
        $pageReviews = Input::get('page_reviews', 1);
        $offsetReviews = ($pageReviews * self::LIMIT) - self::LIMIT;
        $pageOffers = Input::get('page_offers', 1);
        $offsetOffers = ($pageOffers * self::LIMIT) - self::LIMIT;
        $product = DB::selectOne(self::SQL_OFFERS_FIND_PRODUCT_BY_URL, [App::getLocale(), App::getLocale(), App::getLocale(), $url]);
        if ($product === NULL)
        {
            return view('portal.errors.404', ['page_name' => 'page-error404', 'title' => $title, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
        }
        $offers = DB::select(self::SQL_OFFERS_SELECT_OFFERS, [$product->put_id, App::getLocale(), App::getLocale(), $product->put_id, $offsetOffers, self::LIMIT]);
        $params = DB::select(self::SQL_OFFERS_SELECT_PARAMS, [App::getLocale(), App::getLocale(), $product->put_id]);
        $categories = DB::select(self::SQL_OFFERS_SELECT_CATEGORIES, [App::getLocale(), $url, App::getLocale()]);
        $breadCrumbs = [];
        $ceyCeyId = NULL;
        foreach ($categories as $category)
        {
            if (count(DB::select(self::SQL_OFFERS_SELECT_CATEGORIES_BY_CEY_CEY_ID, [$category->cey_id])) == 0)
            {
                $ceyCeyId = $category->cey_cey_id;
                array_push($breadCrumbs, $category);
                break;
            }
        }
        while ($ceyCeyId !== NULL)
        {
            $parent = DB::selectOne(self::SQL_OFFERS_FIND_CATEGORY, [App::getLocale(), App::getLocale(), $url, $ceyCeyId]);
            $ceyCeyId = $parent->cey_cey_id;
            array_unshift($breadCrumbs, $parent);
        }
        $object = new \stdClass();
        $object->name = $product->ple_name;
        $object->url = $product->ple_url;
        array_push($breadCrumbs, $object);
        $reviews = DB::select(self::SQL_OFFERS_SELECT_REVIEWS_BY_PUT_ID, [$product->put_id, App::getLocale(), $product->put_id, App::getLocale(), $offsetReviews, self::LIMIT]);
        if (count($reviews) > 0)
        {
            $pagesReviews = $this->calculatePages($reviews[0]->count);
        }
        else
        {
            $pagesReviews = 0;
        }
        if (count($offers) > 0)
        {
            $pagesOffers = $this->calculatePages($offers[0]->count);
        }
        else
        {
            $pagesOffers = 0;
        }
        foreach ($reviews as $key => $review)
        {
            $reviews[$key]->rvw_pros = json_decode($review->rvw_pros, 1);
            $reviews[$key]->rvw_cons = json_decode($review->rvw_cons, 1);
        }
        return view('portal.pages.offers', ['pages_reviews' => $pagesReviews, 'pages_offers' => $pagesOffers, 'store_controller' => 'ReviewController@storeProduct', "limit" => self::LIMIT, "url" => $url, "reviews" => $reviews, "params" => $params, "offers" => $offers, "bread_product" => $breadCrumbs, "sort_selected" => $this->getSortSelected(), 'sort' => self::SORT, 'product' => $product, 'bread_list' => [], 'page_name' => $pageName, 'title' => $title, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function search()
    {
        $title = __('pages.search');
        $pageName = 'page-search';
        $search = Input::get('search', FALSE);
        $order = $this->getOrder();
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $query = Request::query();
        $f = $a = $m = $pn = $px = NULL;
        $fCount = 0;
        foreach ($query as $key => $value)
        {
            if (preg_match('/m:(.*?);/', $key, $match) == 1)
            {
                $m = implode(',', array_map('intval', explode(',', $match[1])));
            }
            if (preg_match('/a:true;/', $key) == 1)
            {
                $a = TRUE;
            }
            if (preg_match('/pn:(.*?);/', $key, $match) == 1)
            {
                $pn = $match[1];
            }
            if (preg_match('/px:(.*?);/', $key, $match) == 1)
            {
                $px = $match[1];
            }
            if (preg_match('/(?<=f:).*([0-9]:)(.*?)(?=;)/', $key, $match) == 1)
            {
                $f = [];
                foreach (explode(';', $match[0]) as $item)
                {
                    $fCount++;
                    $tmp = explode(',', substr($item, strpos($item, ":") + 1));
                    foreach ($tmp as $t)
                    {
                        array_push($f, $t);
                    }
                }
                $f = implode(',', array_map('intval', $f));
            }
        }
        $murIdQuery = $m !== NULL && strlen($m) > 0 ? "AND mur_id IN ({$m})" : "";
        $availabilityQuery = $a !== NULL && $a ? "AND pee_availability = 0" : "";
        $paramsQuery = $f !== NULL && strlen($f) > 0 ? "AND (pve_id IN ({$f}))" : "";
        $paramsHavingQuery = $fCount > 0 ? "HAVING count(distinct pve_id) >= {$fCount}" : "";
        $pricesQuery = $pn !== NULL && $px !== NULL ? "AND pee_price BETWEEN " . intval($pn) . " AND " . intval($px) : "";
        $products = DB::select(sprintf(self::SQL_SEARCH_SELECT_PRODUCTS, $pricesQuery, $murIdQuery, $availabilityQuery, $paramsQuery , $paramsHavingQuery, $pricesQuery, $murIdQuery, $availabilityQuery, $paramsQuery, $paramsHavingQuery, $order), [$search, App::getLocale(), $search, App::getLocale(), App::getLocale(), App::getLocale(), $search, $offset, self::LIMIT]);
        $sort = self::SORT;
        array_push($sort, "sort_relevance");
        if (count($products) > 0)
        {
            $pages = $this->calculatePages($products[0]->count);
        }
        else
        {
            $pages = 0;
        }
        preg_match('/(.*?)(search)/', Request::fullUrl(), $paramUrlMatch);
        $paramUrl = str_replace($paramUrlMatch[0] . '?', '', Request::fullUrl());
        return view('portal.pages.category', ["paramController" => 'ProductParametersController@getProductParametersBySearch', "paramUrl" => urldecode($paramUrl), "offset" => $offset, "limit" => self::LIMIT, "search" => $search, "sort_selected" => $this->getSortSelected(), 'sort' => $sort, 'pages' => $pages, 'products' => $products, 'bread_list' => [], 'page_name' => $pageName, 'url' => '', 'categories' => [], 'title' => $title, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function companies()
    {
        $pageName = 'page-company';
        $title = __('pages.companies');
        $breadItem = new \stdClass();
        $breadItem->url = "/companies";
        $breadItem->name = __('pages.companies');
        $breadCrums[] = $breadItem;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $companies = Account::where('act_type', '=', 'ETE');

        if ($companies->count() > 0)
        {
            $pages = $this->calculatePages($companies->count());
        }
        else
        {
            $pages = 0;
        }
        $companies = $companies->offset($offset)
            ->limit(self::LIMIT)
            ->get();

        return view('portal.pages.companies', ['lge_id' => Language::where('lge_abbreviation', '=', App::getLocale())->first()->lge_id, 'pages' => $pages, 'user_lang' => App::getLocale(), 'languages' => Language::all(), "page_name" => $pageName, "breads" => $breadCrums, "companies" => $companies, "title" => $title]);
    }

    public function company($actId)
    {
        $pageName = 'page-company';
        $breadCrums = [];
        $breadItem = new \stdClass();
        $breadItem->url = "/companies";
        $breadItem->name = __('pages.companies');
        array_push($breadCrums, $breadItem);
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $company = Account::where([['act_type', '=', 'ETE'], ['act_id', '=', intval($actId)]])->first();
        $lgeId = Language::where('lge_abbreviation', '=', App::getLocale())->first()->lge_id;
        $reviews = $company->reviews()->where([['ete_act_id', '=', $company->act_id], ['lge_id', '=', $lgeId]]);
        if ($reviews->count() > 0)
        {
            $pages = $this->calculatePages($reviews->count());
        }
        else
        {
            $pages = 0;
        }
        $reviewsCount = $reviews->count();
        $rating = $reviews->count() == 0 ? NULL : ($reviews->sum('rvw_rating') / $reviews->count()) * 20;
        $reviews = $reviews->offset($offset)
            ->limit(self::LIMIT)
            ->get();

        $breadItem = new \stdClass();
        $breadItem->name = $title = $company->ete_name;
        array_push($breadCrums, $breadItem);

        return view('portal.pages.company', ['reviews_count' => $reviewsCount, 'pages' => $pages, 'rating' => $rating, 'reviews' => $reviews, 'store_controller' => 'ReviewController@storeCompany', 'url' => ["company" => $actId], 'lge_id' => $lgeId, 'user_lang' => App::getLocale(), 'languages' => Language::all(), "page_name" => $pageName, "breads" => $breadCrums, "company" => $company, "title" => $title]);
    }

    public function refreshPassword(\Illuminate\Http\Request $request, $actId, $amrCodeRefresh)
    {
        $title = __('pages.refresh_password');
        $pageName = 'page-refresh-password';

        try
        {
            Account::findOrFail($actId)->where('amr_code_refresh', $amrCodeRefresh)->firstOrFail();
            return view('portal.pages.refreshPassword', ['act_id' => $actId, 'amr_code_refresh' => $amrCodeRefresh, 'title' => $title, 'page_name' => $pageName, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
        }
        catch (\Exception $e)
        {
            $request->session()->flash('error', __('alerts.url_not_valid'));
            return redirect()->action('PagesController@shopForgotPassword');
        }
    }

    public function shopSignUp()
    {
        $title = __('pages.signup');
        $pageName = 'page-signup';

        return view('portal.pages.signup', ['title' => $title, 'page_name' => $pageName, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    public function shopForgotPassword()
    {
        $title = __('pages.forgot_password');
        $pageName = 'page-forgot-password';

        return view('portal.pages.forgotPassword', ['title' => $title, 'page_name' => $pageName, 'user_lang' => App::getLocale(), 'languages' => Language::all()]);
    }

    private function getSortSelected()
    {
        return Input::get('sort', "sort_name");
    }

    private function calculatePages($count)
    {
        return ceil($count / self::LIMIT);
    }

    private function getOrder()
    {
        $order = "pee_price_max desc";
        switch ($this->getSortSelected())
        {
            case "sort_name":
                $order = "ple.ple_name asc";
                break;
            case "sort_price_min":
                $order = "pee_price_min asc";
                break;
            case "sort_relevance":
                $order = "score desc";
                break;
        }
        return $order;
    }
}
