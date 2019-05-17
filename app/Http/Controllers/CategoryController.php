<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryLanguage;
use App\Models\CategoryParameter;
use App\Models\Image;
use App\Models\Language;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{ 
    private const LIMIT = 10;
    /**
     *
     */
    public function index()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.category');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $ceys = Category::orderBy('cey_id');
        $count = $ceys->count();
        if ($offset >= $count)
        {
            $offset = 0;
            $page = 1;
        }
        if ($count > 0)
        {
            $pages = self::calculatePages($count, self::LIMIT);
        }
        $ceys = $ceys
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

        return view('admin.pages.categories.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'ceys' => $ceys]);
    }

    /**
     *
     */
    public function create()
    {
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.category');
        $breadCrumb->url = action('CategoryController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $langActive = Input::get('lang', Language::first()->lge_id);
        return view('admin.pages.categories.create', ['breadcrumbs' => $breadCrumbs, 'lang_active' => $langActive]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'cle_name' => 'required|string|max:70'
        ]);
        try
        {
            DB::beginTransaction();
            $category = new Category();
            $category->cey_cey_id = $request->input('cey_cey_id',0) == 0 ? null : $request->input('cey_cey_id');
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
                        $category->iae_id = $image->iae_id;
                    }
                }
            }
            $category->save();
            $categoryLang = new CategoryLanguage();
            $categoryLang->cey_id = $category->cey_id;
            $categoryLang->lge_id = $request->input('lang');
            $cleUrl = "";
            if ($category->cey_cey_id !== null)
            {
                $cleUrl = CategoryLanguage::where([['lge_id', $categoryLang->lge_id], ['cey_id', $category->cey_cey_id]])->first()->cle_url.'/';
            }
            $categoryLang->cle_url = $cleUrl . self::generateUrl($request->input('cle_name'));
            $categoryLang->cle_name = $request->input('cle_name');
            $categoryLang->cle_active = $request->input('cle_active') !== null;
            $categoryLang->cle_description = $request->input('cle_description');
            $categoryLang->save();
            foreach($request->input('params',[]) as $param)
            {
                $categoryParams = new CategoryParameter();
                $categoryParams->prr_id = $param;
                $categoryParams->cey_id = $category->cey_id;
                $categoryParams->save();
            }
            DB::commit();
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.category'), 'created' => __('alerts.successfully_created')]));
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

    /**
     * @param Category $category
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * @param Category $category
     */
    public function edit(Category $category)
    {
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.category');
        $breadCrumb->url = action('CategoryController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.update');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $cey = Category::findOrFail($category->cey_id);
        $langActive = Input::get('lang', Language::first()->lge_id);
        return view('admin.pages.categories.edit', ['breadcrumbs' => $breadCrumbs, 'cey' => $cey, 'lang_active' => $langActive]);
    }

    /**
     * @param Request $request
     * @param Category $category
     */
    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'cle_name' => 'required|string|max:70'
        ]);
        try
        {
            Category::findOrFail($category->cey_id);
            DB::beginTransaction();
            $category->cey_cey_id = $request->input('cey_cey_id',0) == 0 ? null : $request->input('cey_cey_id');
            if ($request->iae_image !== NULL)
            {
                if ($category->iae_id !== null)
                {
                    Storage::delete('public/' . $category->image()->first()->iae_path . '.' . $category->image()->first()->iae_type);
                }
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
                        $category->iae_id = $image->iae_id;
                    }
                    else
                    {
                        $category->iae_id = $existImg->iae_id;
                    }
                }
            }
            $category->save();
            $categoryLang = new CategoryLanguage();
            $categoryLang->cey_id = $category->cey_id;
            $categoryLang->lge_id = $request->input('lang');
            $cleUrl = "";
            if ($category->cey_cey_id !== null)
            {
                $langUrl = CategoryLanguage::where([['lge_id', $categoryLang->lge_id], ['cey_id', $category->cey_cey_id]])->first();
                if ($langUrl === null)
                {
                    return back()->with('error', __('alerts.parent_category_is_not_set'));
                }
                else
                {
                    $cleUrl = $langUrl->cle_url . '/';
                }
            }
            $categoryLang->cle_url = $cleUrl . self::generateUrl($request->input('cle_name'));
            $categoryLang->cle_name = $request->input('cle_name');
            $categoryLang->cle_active = $request->input('cle_active') !== null;
            $categoryLang->cle_description = $request->input('cle_description');
            if ($category->languages()->where('category_languages.lge_id', $request->input('lang'))->first() !== null)
            {
                CategoryLanguage::where([['lge_id', $categoryLang->lge_id], ['cey_id', $category->cey_id]])->update(
                  ['cle_url' => $categoryLang->cle_url, 'cle_name' => $categoryLang->cle_name, 'cle_active' => $categoryLang->cle_active, 'cle_description' => $categoryLang->cle_description]
                );
            }
            else
            {
                $categoryLang->save();
            }
            $category->parameters()->detach();
            foreach($request->input('params',[]) as $param)
            {
                $categoryParams = new CategoryParameter();
                $categoryParams->prr_id = $param;
                $categoryParams->cey_id = $category->cey_id;
                $categoryParams->save();
            }
            DB::commit();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.category'), 'updated' => __('alerts.successfully_updated')]));
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

    /**
     * @param Category $category
     */
    public function destroy(Category $category)
    {
        try
        {
            DB::beginTransaction();
            $category->delete();
            if ($category->iae_id !== null)
            {
                Storage::delete('public/' . $category->image()->first()->iae_path . '.' . $category->image()->first()->iae_type);
            }
            DB::commit();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.image'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', __('alerts.unknown_error'));
        }
    }
}
