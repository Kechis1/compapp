<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Parameter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use mysql_xdevapi\Exception;

class ImageController extends Controller
{
    private const LIMIT = 10;
    /**
     *
     */
    public function index()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.images');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = is_numeric(Input::get('page', 1)) ? Input::get('page', 1) : 1;
        $iaes = Image::orderBy('iae_id');
        $count = $iaes->count();
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
        $iaes = $iaes
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

        return view('admin.pages.images.index', ['page' => $page, 'pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'iaes' => $iaes]);
    }

    /**
     *
     */
    public function create()
    {
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.images');
        $breadCrumb->url = action('ImageController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        return view('admin.pages.images.create', ['breadcrumbs' => $breadCrumbs]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'iae_image' => 'required'
        ]);
        try
        {
            if ($request->iae_image === NULL)
            {
                throw new \Exception();
            }
            $request->iae_image->store('public');
            if (!$request->file('iae_image')->isValid())
            {
                throw new \Exception();
            }
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
            $image->save();
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.image'), 'created' => __('alerts.successfully_created')]));
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
     * @param Image $image
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * @param Image $image
     */
    public function edit(Image $image)
    {

    }

    /**
     * @param Request $request
     * @param Image $image
     */
    public function update(Request $request, Image $image)
    {

    }

    /**
     * @param Image $image
     */
    public function destroy(Image $image)
    {
        try
        {
            DB::beginTransaction();
            $image->delete();
            Storage::delete('public/'.$image->iae_path.'.'.$image->iae_type);
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
