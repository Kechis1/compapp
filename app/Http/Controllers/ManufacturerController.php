<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ManufacturerController extends Controller
{
    private const LIMIT = 10;
    /**
     *
     */
    public function index()
    {
        self::initLocale();
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.manufacturers');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = is_numeric(Input::get('page', 1)) ? Input::get('page', 1) : 1;
        $murs = Manufacturer::orderBy('mur_id');
        $count = $murs->count();
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
        $murs = $murs
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

        return view('admin.pages.manufacturers.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'murs' => $murs]);
    }

    /**
     *
     */
    public function create()
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.manufacturers');
        $breadCrumb->url = action('ManufacturerController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        return view('admin.pages.manufacturers.create', ['breadcrumbs' => $breadCrumbs]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'mur_name' => 'required|unique:manufacturers|string|max:100'
        ]);
        try
        {
            $manufacturer = new Manufacturer();
            $manufacturer->mur_name = $request->input('mur_name');
            $manufacturer->save();
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.manufacturer'), 'created' => __('alerts.successfully_created')]));
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
     * @param Manufacturer $manufacturer
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * @param Manufacturer $manufacturer
     */
    public function edit(Manufacturer $manufacturer)
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.manufacturers');
        $breadCrumb->url = action('ManufacturerController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.update');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $man = Manufacturer::findOrFail($manufacturer->mur_id);
        return view('admin.pages.manufacturers.edit', ['breadcrumbs' => $breadCrumbs, 'mur' => $man]);
    }

    /**
     * @param Request $request
     * @param Manufacturer $manufacturer
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $this->validate($request, [
            'mur_name' => 'required|string|max:100'
        ]);
        try
        {
            $exists = Manufacturer::where('mur_name', $request->input('mur_name'))->first();
            if (isset($exists->mur_id) && $exists->mur_id != $manufacturer->mur_id)
            {
                throw new \Exception();
            }
            $manufacturer->mur_name = $request->input('mur_name');
            $manufacturer->save();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.manufacturer'), 'updated' => __('alerts.successfully_updated')]));
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
     * @param Manufacturer $manufacturer
     */
    public function destroy(Manufacturer $manufacturer)
    {
        try
        {
            $manufacturer->delete();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.manufacturer'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            return back()->with('error', __('alerts.unknown_error'));
        }
    }
}
