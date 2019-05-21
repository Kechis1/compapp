<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryLanguage;
use App\Models\Image;
use App\Models\Language;
use App\Models\ParameterLanguage;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductLanguage;
use App\Models\ProductParameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private const LIMIT = 10;

    /**
     *
     */
    public function index()
    {
        self::initLocale();
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

        return view('admin.pages.products.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), 'offset' => $offset, 'count' => $count, 'limit' => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'puts' => $puts]);
    }

    /**
     *
     */
    public function create()
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.products');
        $breadCrumb->url = action('ProductController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $langActive = Input::get('lang', Language::first()->lge_id);
        $params = ParameterLanguage::where('lge_id', $langActive)->orderBy('pls_name')->get();
        $cats = CategoryLanguage::where('lge_id', $langActive)->orderBy('cle_name')->get();
        return view('admin.pages.products.create', ['breadcrumbs' => $breadCrumbs, 'lang_active' => $langActive, 'params' => $params, 'cats' => $cats]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'mur_id' => 'required|numeric|exists:manufacturers',
            'put_ean' => 'nullable|string|max:14|unique:products',
            'ple_name' => 'required|max:125|string',
        ]);
        $langActive = Input::get('lang', Language::first()->lge_id);
        try
        {
            DB::beginTransaction();
            $product = new Product();
            $product->mur_id = $request->input('mur_id');
            $product->put_ean = $request->input('put_ean');
            $product->save();
            $productLang = new ProductLanguage();
            $productLang->put_id = $product->put_id;
            $productLang->lge_id = $langActive;
            $productLang->ple_name = $request->input('ple_name');
            $productLang->ple_active = $request->input('ple_active', FALSE);
            $productLang->ple_url = self::generateUrl($request->input('ple_name'));
            $productLang->ple_desc_short = $request->input('ple_desc_short');
            $productLang->ple_desc_long = $request->input('ple_desc_long');
            $productLang->save();

            if ($images = $request->file('iae_image'))
            {
                foreach ($images as $iaeImage)
                {
                    $origExt = $iaeImage->getClientOriginalExtension();
                    $origPath = $iaeImage->getClientOriginalName();
                    if (!in_array($origExt, ['png', 'jpeg', 'jpg', 'gif']))
                    {
                        throw new \Exception();
                    }
                    $iaeImage->store('public');
                    if ($iaeImage->isValid())
                    {
                        $name = substr_replace($origPath, '', strrpos($origPath, $origExt) - 1, strlen($origExt) + 1);
                        $ext = $iaeImage->extension();
                        $path = substr_replace($iaeImage->hashName(), '', strrpos($iaeImage->hashName(), $ext) - 1, strlen($ext) + 1);
                        $size = $iaeImage->getSize() / 1000;
                        $image = new Image();
                        $image->iae_path = $path;
                        $image->iae_name = $name;
                        $image->iae_size = $size;
                        $image->iae_type = $ext;
                        $existImg = Image::where('iae_path', $image->iae_path)->first();
                        $productImage = new ProductImage();
                        if (!isset($existImg->iae_id))
                        {
                            $image->save();
                            $productImage->iae_id = $image->iae_id;
                        }
                        else
                        {
                            $productImage->iae_id = $existImg->iae_id;
                        }
                        $productImage->put_id = $product->put_id;
                        $productImage->pie_active = TRUE;
                        $productImage->save();
                    }
                }
            }
            foreach ($request->input('cats', []) as $cat)
            {
                Category::findOrFail($cat);
                try
                {
                    $productCat = new ProductCategory();
                    $productCat->cey_id = $cat;
                    $productCat->put_id = $product->put_id;
                    $productCat->pcy_active = TRUE;
                    $productCat->save();
                }
                catch (\Exception $e)
                {
                    DB::rollBack();
                    return back()->with('error', __('alerts.already_created', ['object' => __('alerts.products'), 'already_created' => __('alerts.already_created_msg')]));
                }
            }
            if ($request->input('params') !== NULL && count($request->input('params', [])) > 0)
            {
                foreach ($request->input('params', []) as $param)
                {
                    $request = new Request([
                        'pve_id' => $param
                    ]);
                    $this->validate($request, [
                        'pve_id' => 'required|numeric|exists:parameter_values'
                    ]);
                    $newParam = new ProductParameter();
                    $newParam->pve_id = $param;
                    $newParam->put_id = $product->put_id;
                    $newParam->ppr_active = TRUE;
                    $newParam->save();
                }
            }
            DB::commit();
            return back()->with('success', __('alerts.created', ['object' => __('alerts.products'), 'created' => __('alerts.successfully_created')]));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', __('alerts.unknown_error'));
        }
    }

    /**
     * @param Product $company
     */
    public function show(Product $company)
    {
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Product $product)
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.products');
        $breadCrumb->url = action('ProductController@index');
        $breadCrumb->active = 0;
        $breadCrumbs[] = $breadCrumb;
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.update');
        $breadCrumb->active = 1;
        $breadCrumbs[] = $breadCrumb;
        $langActive = Input::get('lang', Language::first()->lge_id);
        if (isset($product->put_id))
        {
            Product::findOrFail($product->put_id);
        }
        else
        {
            return back()->with('error', __('label.products') . ' ' . __('alerts.was_not_found'));
        }
        $params = ParameterLanguage::where('lge_id', $langActive)->orderBy('pls_name')->get();
        $cats = CategoryLanguage::where('lge_id', $langActive)->orderBy('cle_name')->get();
        return view('admin.pages.products.edit', ['breadcrumbs' => $breadCrumbs, 'product' => $product, 'lang_active' => $langActive, 'params' => $params, 'cats' => $cats]);
    }

    /**
     * @param Request $request
     * @param Product $product
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'mur_id' => 'required|numeric|exists:manufacturers',
            'put_ean' => 'nullable|string|max:14',
            'ple_name' => 'required|max:125|string',
        ]);
        $langActive = Input::get('lang', Language::first()->lge_id);
        try
        {
            DB::beginTransaction();
            $product->mur_id = $request->input('mur_id');
            $product->put_ean = $request->input('put_ean');
            $product->save();
            if (($productLang = ProductLanguage::where([['put_id', $product->put_id],['lge_id', $langActive]]))->exists())
            {
                $productLang->update(
                    [
                        'ple_name' => $request->input('ple_name'),
                        'ple_active' => $request->input('ple_active', FALSE),
                        'ple_url' => self::generateUrl($request->input('ple_name')),
                        'ple_desc_short' => $request->input('ple_desc_short'),
                        'ple_desc_long' => $request->input('ple_desc_long')
                    ]
                );
            }
            else
            {
                $productLang = new ProductLanguage();
                $productLang->put_id = $product->put_id;
                $productLang->lge_id = $langActive;
                $productLang->ple_name = $request->input('ple_name');
                $productLang->ple_active = $request->input('ple_active', FALSE);
                $productLang->ple_url = self::generateUrl($request->input('ple_name'));
                $productLang->ple_desc_short = $request->input('ple_desc_short');
                $productLang->ple_desc_long = $request->input('ple_desc_long');
                $productLang->save();
            }

            if ($images = $request->file('iae_image'))
            {
                if (($productImages = ProductImage::where('put_id', $product->put_id))->get()->count() > 0)
                {
                    foreach ($productImages->get() as $pImage)
                    {
                        Storage::delete('public/'.$pImage->iae_path.'.'.$pImage->iae_type);
                    }
                    $productImages->delete();
                }
                foreach ($images as $iaeImage)
                {
                    $origExt = $iaeImage->getClientOriginalExtension();
                    $origPath = $iaeImage->getClientOriginalName();
                    if (!in_array($origExt, ['png', 'jpeg', 'jpg', 'gif']))
                    {
                        throw new \Exception();
                    }
                    $iaeImage->store('public');
                    if ($iaeImage->isValid())
                    {
                        $name = substr_replace($origPath, '', strrpos($origPath, $origExt) - 1, strlen($origExt) + 1);
                        $ext = $iaeImage->extension();
                        $path = substr_replace($iaeImage->hashName(), '', strrpos($iaeImage->hashName(), $ext) - 1, strlen($ext) + 1);
                        $size = $iaeImage->getSize() / 1000;
                        $image = new Image();
                        $image->iae_path = $path;
                        $image->iae_name = $name;
                        $image->iae_size = $size;
                        $image->iae_type = $ext;
                        $existImg = Image::where('iae_path', $image->iae_path)->first();
                        $productImage = new ProductImage();
                        if (!isset($existImg->iae_id))
                        {
                            $image->save();
                            $productImage->iae_id = $image->iae_id;
                        }
                        else
                        {
                            $productImage->iae_id = $existImg->iae_id;
                        }
                        $productImage->put_id = $product->put_id;
                        $productImage->pie_active = TRUE;
                        $productImage->save();
                    }
                }
            }
            if (count($request->input('cats', [])) > 0)
            {
                ProductCategory::where('put_id', $product->put_id)->delete();
            }
            foreach ($request->input('cats', []) as $cat)
            {
                Category::findOrFail($cat);
                try
                {
                    $productCat = new ProductCategory();
                    $productCat->cey_id = $cat;
                    $productCat->put_id = $product->put_id;
                    $productCat->pcy_active = TRUE;
                    $productCat->save();
                }
                catch (\Exception $e)
                {
                    DB::rollBack();
                    return back()->with('error', __('alerts.already_created', ['object' => __('alerts.products'), 'already_created' => __('alerts.already_created_msg')]));
                }
            }
            if ($request->input('params') !== NULL && count($request->input('params', [])) > 0)
            {
                ProductParameter::where('put_id', $product->put_id)->delete();
                foreach ($request->input('params', []) as $param)
                {
                    $request = new Request([
                        'pve_id' => $param
                    ]);
                    $this->validate($request, [
                        'pve_id' => 'required|numeric|exists:parameter_values'
                    ]);
                    $newParam = new ProductParameter();
                    $newParam->pve_id = $param;
                    $newParam->put_id = $product->put_id;
                    $newParam->ppr_active = TRUE;
                    $newParam->save();
                }
            }
            DB::commit();
            return back()->with('success', __('alerts.updated', ['object' => __('alerts.products'), 'updated' => __('alerts.successfully_updated')]));
        }
        catch (\Exception $e)
        {

            DB::rollBack();
            print_r($e->getMessage());
         //   return back()->with('error', __('alerts.unknown_error'));
        }
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
