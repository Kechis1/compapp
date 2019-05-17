<?php

namespace App\Http\Controllers;

use App\Models\Parameter;
use App\Models\ParameterValue;
use App\Models\ProductParameter;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Request;

class ProductParametersController extends Controller
{
    private const SQL_BY_CLE_URL_SELECT_MANUFACTURERS = "select mur_id, mur_name, sum(count_mur) as count_mur From (select mur_id, mur_name, count(distinct put_id) count_mur from parameter_value_languages JOIN parameter_values USING (pve_id) JOIN product_parameters prr USING (pve_id) JOIN product_categories USING (put_id) JOIN products using (put_id) JOIN product_languages USING (put_id) JOIN product_enterprises pee using (put_id) JOIN category_languages USING (cey_id) join manufacturers USING (mur_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND cey_id = (select cey_id from category_languages where cle_url = ? and category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ? and cle_active = true )) and put_id IN (select put_id from product_enterprises where product_enterprises.pee_active = true) and pcy_active = true and pee_active = true and ple_active = true and ppr_active = true and pvs_active = true %s %s group by mur_id, mur_name %s UNION select mur_id, mur_name, 0 as count_mur from product_categories JOIN products using (put_id) JOIN product_languages USING (put_id) JOIN product_enterprises pee using (put_id) JOIN category_languages USING (cey_id) JOIN manufacturers USING (mur_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND cey_id = (select cey_id from category_languages where cle_url = ? and category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ? and cle_active = true )) and put_id IN (select put_id from product_enterprises where product_enterprises.pee_active = true) and pcy_active = true and pee_active = true and ple_active = true group by  mur_id, mur_name) t group by mur_id, mur_name";
    private const SQL_BY_CLE_URL_SELECT_AVAILABILITY = "select count(*) as count_availability from (select count(put_id) from parameter_value_languages JOIN parameter_values USING (pve_id) JOIN product_parameters prr USING (pve_id) JOIN product_categories USING (put_id) JOIN products using (put_id) JOIN product_languages USING (put_id) JOIN product_enterprises pee using (put_id) JOIN category_languages USING (cey_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND cey_id = (select cey_id from category_languages where cle_url = ? and category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ? and cle_active = true )) and pcy_active = true and pee_active = true and ple_active = true and ppr_active = true and pvs_active = true and put_id IN (select put_id from product_enterprises where product_enterprises.pee_active = true and product_enterprises.pee_availability = 0) %s %s %s group by put_id %s) t";
    private const SQL_BY_CLE_URL_SELECT_COUNT = "select count(*) as count from (select count(distinct prr.put_id) from parameter_value_languages JOIN parameter_values USING (pve_id) JOIN product_parameters prr USING (pve_id) JOIN product_categories USING (put_id) JOIN  product_enterprises pee using (put_id) JOIN products using (put_id) JOIN category_languages USING (cey_id) WHERE parameter_value_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND pvs_active = true AND cle_active = true AND pcy_active = true AND ppr_active = true AND pee.pee_active = true AND cle_url = ? AND category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) %s %s %s AND prr.pve_id IN (%s) GROUP BY prr.put_id HAVING count(distinct prr.pve_id) >= ?) t";
    private const SQL_BY_CLE_URL_SELECT_PVEALL = "select pve_id, pvs_value, count(put_id) count, null as count_plus from parameter_value_languages JOIN parameter_values USING (pve_id) JOIN product_parameters USING (pve_id) JOIN product_enterprises pee using (put_id) JOIN product_categories USING (put_id) join products using (put_id) JOIN category_languages USING (cey_id) WHERE parameter_value_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND pvs_active = true AND prr_id = ? AND cle_active = true AND pcy_active = true AND ppr_active = true AND cle_url = ? AND pee.pee_active = true %s %s %s AND category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) group by pve_id, pvs_value %s";
    private const SQL_BY_CLE_URL_SELECT_PARAMS = "select (case when (@row_number:=@row_number+1) > 5 then true else false end) as is_hidden, prr_id, pls_unit, pls_name, prr_numeric FROM parameter_languages JOIN parameters USING (prr_id) JOIN category_parameters USING (prr_id) JOIN (SELECT @row_number:=0) as t WHERE parameter_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND cey_id = (select cey_id from category_languages where category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND cle_url = ? AND cle_active = true) order by is_hidden, pls_name";
    private const SQL_BY_CLE_URL_SELECT_PRICES = "select min(price_min) price_min, max(price_max) price_max from (select min(pee_price) price_min, max(pee_price) price_max from product_enterprises JOIN products USING (put_id) JOIN  product_categories USING (put_id) JOIN product_languages USING (put_id) JOIN product_parameters USING (put_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) AND cey_id = (select cey_id from category_languages where cle_url = ? and category_languages.lge_id = (select lge_id from languages where lge_abbreviation = ? and cle_active = true )) and product_enterprises.pee_active = true and pcy_active = true and ppr_active = true and ple_active = true %s %s %s group by put_id %s) t";

    private const SQL_BY_SEARCH_SELECT_PRICES = "select min(price_min) price_min, max(price_max) price_max from (select min(pee_price) price_min, max(pee_price) price_max from product_enterprises JOIN products USING (put_id) JOIN product_languages USING (put_id) JOIN product_parameters USING (put_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) and product_enterprises.pee_active = true and ppr_active = true and ple_active = true and MATCH(ple_name) AGAINST (?) %s %s group by put_id) t";
    private const SQL_BY_SEARCH_SELECT_AVAILABILITY = "select count(*) as count_availability from (select count(put_id) from parameter_value_languages JOIN parameter_values USING (pve_id) JOIN product_parameters prr USING (pve_id) JOIN products using (put_id) JOIN product_languages USING (put_id) JOIN product_enterprises pee using (put_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) and pee_active = true and ple_active = true and ppr_active = true and pvs_active = true and put_id IN (select put_id from product_enterprises where product_enterprises.pee_active = true and product_enterprises.pee_availability = 0) and MATCH(ple_name) AGAINST (?) %s %s group by put_id) t";
    private const SQL_BY_SEARCH_SELECT_MANUFACTURERS = "select mur_id, mur_name, sum(count_mur) as count_mur From (select mur_id, mur_name, count(distinct put_id) count_mur from parameter_value_languages JOIN parameter_values USING (pve_id) JOIN product_parameters prr USING (pve_id) JOIN products using (put_id) JOIN product_languages USING (put_id) JOIN product_enterprises pee using (put_id) join manufacturers USING (mur_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) and put_id IN (select put_id from product_enterprises where product_enterprises.pee_active = true) and pee_active = true and ple_active = true and ppr_active = true and pvs_active = true %s and MATCH(ple_name) AGAINST (?) group by mur_id, mur_name UNION select mur_id, mur_name, 0 as count_mur from products JOIN product_languages USING (put_id) JOIN product_enterprises pee using (put_id) JOIN manufacturers USING (mur_id) where product_languages.lge_id = (select lge_id from languages where lge_abbreviation = ?) and put_id IN (select put_id from product_enterprises where product_enterprises.pee_active = true) and pee_active = true and ple_active = true and MATCH(ple_name) AGAINST (?) group by  mur_id, mur_name) t group by mur_id, mur_name";

    /**
     *
     */
    public function index()
    {
        $items = ProductParameter::all();
    }

    /**
     *
     */
    public function create()
    {
        //
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * @param ProductParameter $ProductParameter
     */
    public function show(ProductParameter $ProductParameter)
    {
        //
    }

    /**
     * @param ProductParameter $ProductParameter
     */
    public function edit(ProductParameter $ProductParameter)
    {
        //
    }

    /**
     * @param Request $request
     * @param ProductParameter $ProductParameter
     */
    public function update(Request $request, ProductParameter $ProductParameter)
    {
        //
    }

    /**
     * @param ProductParameter $ProductParameter
     */
    public function destroy(ProductParameter $ProductParameter)
    {
        //
    }

    public function getProductParametersByCleUrl($cleUrl)
    {
        $query = Request::query();
        $f = $f1 = $a = $m = $m1 = $pn = $px = null;
        $f1Count = 0;
        foreach ($query as $key => $value)
        {
            if (preg_match('/m:(.*?);/', $key, $match) == 1)
            {
                $isM = true;
                foreach (explode(',', $match[1]) as $man)
                {
                    if (strlen($man) == 0 || !is_numeric($man))
                    {
                        $m = "";
                        $isM = false;
                        break;
                    }
                }
                if ($isM)
                {
                    $m = implode(',', array_map('intval', explode(',', $match[1])));
                    $m1 = array_map('intval', explode(',', $match[1]));
                }
            }
            if (preg_match('/a:true;/', $key, $match) == 1)
            {
                $a = true;
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
                $f1 = $f = [];
                foreach (explode(';', $match[0]) as $item)
                {
                    if (is_numeric($item[0]) && Parameter::where('prr_id',intval($item))->exists())
                    {
                        $explode = explode(',', substr($item, strpos($item, ":") + 1));
                        array_push($f, [
                            "prr_id" => (string)intval($item),
                            "items" => []
                        ]);
                        $index = count($f)-1;
                        foreach ($explode as $exItem)
                        {
                            if (is_numeric($exItem) && ParameterValue::where([['prr_id', $item],['pve_id', $exItem]])->exists())
                            {
                                array_push($f[$index]["items"], $exItem);
                                $f1Count++;
                                foreach ($explode as $t)
                                {
                                    array_push($f1, $t);
                                }
                            }
                        }
                    }
                }
                $f1 = implode(',', array_map('intval', $f1));
            }
        }

        $murIdQuery = $m !== null && strlen($m) > 0 ? "AND mur_id IN ({$m})" : "";
        $availabilityQuery = $a !== null && $a ? "AND pee_availability = 0" : "";
        $paramsQuery = $f1 !== null && strlen($f1) > 0 ? "AND (pve_id IN ({$f1}))" : "";
        $paramsHavingQuery = $f1Count > 0 ? "HAVING count(distinct pve_id) >= {$f1Count}" : "";
        $prices = DB::selectOne(sprintf(self::SQL_BY_CLE_URL_SELECT_PRICES, $murIdQuery, $availabilityQuery, $paramsQuery ,$paramsHavingQuery), [App::getLocale(), $cleUrl, App::getLocale()]);
        $pricesQuery = $pn !== null && $px !== null && is_numeric($pn) && is_numeric($px) && doubleval($pn) >= $prices->price_min && doubleval($px) <= $prices->price_max && doubleval($px) > doubleval($pn) ? "AND pee_price BETWEEN ".doubleval($pn)." AND ".doubleval($px) : "";
        if (strlen($pricesQuery) > 0)
        {
            $prices->price_min_selected = doubleval($pn);
            $prices->price_max_selected = doubleval($px);
        }
        else
        {
            $prices->price_min_selected = $prices->price_min;
            $prices->price_max_selected = $prices->price_max;
        }
        $params = DB::select(self::SQL_BY_CLE_URL_SELECT_PARAMS, [App::getLocale(), App::getLocale(), $cleUrl]);
        foreach ($params as $key => $param)
        {
            $orderby = $param->prr_numeric ? "order by cast(pvs_value as double) asc" : "order by pvs_value asc";
            $pveall = DB::select(sprintf(self::SQL_BY_CLE_URL_SELECT_PVEALL, $pricesQuery, $murIdQuery, $availabilityQuery, $orderby), [App::getLocale(), $param->prr_id, $cleUrl, App::getLocale()]);
            $isSet = false;
            if (count($pveall) == 0)
                unset($params[$key]);
            else
            {
                $params[$key]->is_next_hidden = true;
                if ($f !== null) {
                    foreach ($f as $item) {
                        if ($item["prr_id"] == $param->prr_id) {
                            foreach ($pveall as $pveallkey => $pve) {
                                foreach ($item["items"] as $itempve) {
                                    if ($pve->pve_id == $itempve) {
                                        $isSet = true;
                                        $pveall[$pveallkey]->model = true;
                                        $params[$key]->is_hidden = false;
                                        if ($pveallkey >= 10) $params[$key]->is_next_hidden = false;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($isSet)
                $havingCount = $f1Count;
            else $havingCount = $f1Count+1;
            foreach ($pveall as $pvkey => $pvitem) {
                $f1Query = $f1 !== null && strlen($f1) > 0 ? $f1 . ',' . $pvitem->pve_id : $pvitem->pve_id;
                $count = DB::selectOne(sprintf(self::SQL_BY_CLE_URL_SELECT_COUNT, $murIdQuery, $availabilityQuery, $pricesQuery, $f1Query), [App::getLocale(), $cleUrl, App::getLocale(), $havingCount]);
                if ($isSet) {
                    $pveall[$pvkey]->count_plus = $count->count;
                }
                else {
                    $pveall[$pvkey]->count = $count->count;
                }
            }
            if (count($pveall) != 0) {
                $params[$key]->items_first = array_slice($pveall, 0, 10);
                $params[$key]->items_all = $pveall;
            }
        }
        $availability = DB::selectOne(sprintf(self::SQL_BY_CLE_URL_SELECT_AVAILABILITY, $murIdQuery, $paramsQuery, $pricesQuery, $paramsHavingQuery), [App::getLocale(), $cleUrl, App::getLocale()]);
        $manufacturers = DB::select(sprintf(self::SQL_BY_CLE_URL_SELECT_MANUFACTURERS, $paramsQuery, $pricesQuery, $paramsHavingQuery), [App::getLocale(), $cleUrl, App::getLocale(), App::getLocale(), $cleUrl, App::getLocale()]);
        $manSet = false;
        if  ($a !== null) $availability->model = $a;
        if ($m1 !== null) {
            foreach ($m1 as $item) {
                foreach ($manufacturers as $man) {
                    if ($item == $man->mur_id && $man->count_mur > 0) {
                        $man->model = true;
                        $manSet = true;
                    }
                }
            }
        }
        return json_encode(["man_set" => $manSet, "params" => $params, "prices" => $prices, "manufacturers" => $manufacturers, "availability" => $availability]);
    }

    public function getProductParametersBySearch()
    {
        $query = Request::query();
        $a = $m = $m1 = $pn = $px = null;
        $search = Input::get('search', false);
        foreach ($query as $key => $value)
        {
            if (preg_match('/m:(.*?);/', $key, $match) == 1)
            {
                $m = implode(',', array_map('intval', explode(',', $match[1])));
                $m1 = array_map('intval', explode(',', $match[1]));
            }
            if (preg_match('/a:true;/', $key, $match) == 1)
            {
                $a = true;
            }
            if (preg_match('/pn:(.*?);/', $key, $match) == 1)
            {
                $pn = $match[1];
            }
            if (preg_match('/px:(.*?);/', $key, $match) == 1)
            {
                $px = $match[1];
            }
        }
        $murIdQuery = $m !== null && strlen($m) > 0 ? "AND mur_id IN ({$m})" : "";
        $availabilityQuery = $a !== null && $a ? "AND pee_availability = 0" : "";
        $pricesQuery = $pn !== null && $px !== null ? "AND pee_price BETWEEN ".intval($pn)." AND ".intval($px) : "";
        $prices = DB::selectOne(sprintf(self::SQL_BY_SEARCH_SELECT_PRICES, $murIdQuery, $availabilityQuery), [App::getLocale(), $search]);
        $prices->price_min_selected = $pn !== null ? intval($pn) : $prices->price_min;
        $prices->price_max_selected = $px !== null ? intval($px) : $prices->price_max;
        $availability = DB::selectOne(sprintf(self::SQL_BY_SEARCH_SELECT_AVAILABILITY, $pricesQuery, $murIdQuery),  [App::getLocale(), $search]);
        $manufacturers = DB::select(sprintf(self::SQL_BY_SEARCH_SELECT_MANUFACTURERS, $pricesQuery), [App::getLocale(), $search, App::getLocale(), App::getLocale()]);

        $manSet = false;
        if  ($a !== null) $availability->model = $a;
        if ($m1 !== null) {
            foreach ($m1 as $item) {
                foreach ($manufacturers as $man) {
                    if ($item == $man->mur_id && $man->count_mur > 0) {
                        $man->model = true;
                        $manSet = true;
                    }
                }
            }
        }

        return json_encode(["man_set" => $manSet, "params" => null, "prices" => $prices, "manufacturers" => $manufacturers, "availability" => $availability]);
    }
}
