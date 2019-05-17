<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ProductController extends Controller
{
    private const LIMIT = 10;

    /**
     *
     */
    public function index()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.products');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = is_numeric(Input::get('page', 1)) ? Input::get('page', 1) : 1;
        $puts = Product::orderBy('put_id');
        $count = $puts->count();
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
        $puts = $puts
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

        return view('admin.pages.products.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'puts' => $puts]);
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
     * @param Product $company
     */
    public function show(Product $company)
    {
    }

    /**
     * @param Product $product
     */
    public function edit(Product $product)
    {

    }

    /**
     * @param Request $request
     * @param Product $product
     */
    public function update(Request $request, Product $product)
    {
        
    }

    public function statusUpdate(Request $request, Product $product)
    {
        try
        {
            $isActivate = $request->input('statusBtn') !== NULL && $request->input('statusBtn') == 1;
            $isDeactivate = $request->input('statusBtn') !== NULL && $request->input('statusBtn') == 2;
            if ($isActivate === FALSE && $isDeactivate === FALSE)
            {
                throw new \Exception();
            }
            Product::findOrFail($product->put_id);
            ProductLanguage::where('put_id', $product->put_id)->update(['ple_active' => $isActivate]);
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.company'), 'updated' => __('alerts.successfully_updated')]));
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
     * @param Product $product
     */
    public function destroy(Product $product)
    {

    }
}
