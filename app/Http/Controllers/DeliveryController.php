<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class DeliveryController extends Controller
{
    private const LIMIT = 10;
    /**
     *
     */
    public function index()
    {
        self::initLocale();
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.deliveries');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = is_numeric(Input::get('page', 1)) ? Input::get('page', 1) : 1;
        $dlys = Delivery::orderBy('dly_id');
        $count = $dlys->count();
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
        $dlys = $dlys
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

        return view('admin.pages.deliveries.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'dlys' => $dlys]);
    }

    /**
     *
     */
    public function create()
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.deliveries');
        $breadCrumb->url = action('DeliveryController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        return view('admin.pages.deliveries.create', ['breadcrumbs' => $breadCrumbs]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'dly_name' => 'required|unique:deliveries|string|max:40'
        ]);
        try
        {
            $delivery = new Delivery();
            $delivery->dly_name = $request->input('dly_name');
            $delivery->save();
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.delivery'), 'created' => __('alerts.successfully_created')]));
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
     * @param Delivery $delivery
     */
    public function show(Delivery $delivery)
    {
        //
    }

    /**
     * @param Delivery $delivery
     */
    public function edit(Delivery $delivery)
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.deliveries');
        $breadCrumb->url = action('DeliveryController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.update');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $dly = Delivery::findOrFail($delivery->dly_id);
        return view('admin.pages.deliveries.edit', ['breadcrumbs' => $breadCrumbs, 'dly' => $dly]);
    }

    /**
     * @param Request $request
     * @param Delivery $delivery
     */
    public function update(Request $request, Delivery $delivery)
    {
        $this->validate($request, [
            'dly_name' => 'required|string|max:40'
        ]);
        try
        {
            $exists = Delivery::where('dly_name', $request->input('dly_name'))->first();
            if (isset($exists->dly_id) && $exists->dly_id != $delivery->dly_id)
            {
                throw new \Exception();
            }
            $delivery->dly_name = $request->input('dly_name');
            $delivery->save();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.delivery'), 'updated' => __('alerts.successfully_updated')]));
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
     * @param Delivery $delivery
     */
    public function destroy(Delivery $delivery)
    {
        try
        {
            $delivery->delete();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.delivery'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            return back()->with('error', __('alerts.unknown_error'));
        }
    }
}
