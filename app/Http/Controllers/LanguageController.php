<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class LanguageController extends Controller
{
    private const LIMIT = 10;
    /**
     *
     */
    public function index()
    {
        self::initLocale();
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.languages');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = is_numeric(Input::get('page', 1)) ? Input::get('page', 1) : 1;
        $langs = Language::orderBy('lge_id');
        $count = $langs->count();
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
        $langs = $langs
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

        return view('admin.pages.languages.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'langs' => $langs]);
    }

    /**
     *
     */
    public function create()
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.languages');
        $breadCrumb->url = action('LanguageController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        return view('admin.pages.languages.create', ['breadcrumbs' => $breadCrumbs]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'lge_abbreviation' => 'required|unique:languages|string|max:4',
            'lge_name' => 'required|string|max:40'
        ]);
        try
        {
            $language = new Language();
            $language->lge_abbreviation = $request->input('lge_abbreviation');
            $language->lge_name = $request->input('lge_name');
            $language->save();
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.language'), 'created' => __('alerts.successfully_created')]));
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
     * @param Language $language
     */
    public function show(Language $language)
    {
        //
    }

    /**
     * @param Language $language
     */
    public function edit(Language $language)
    {
        self::initLocale();
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.languages');
        $breadCrumb->url = action('LanguageController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.update');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $lang = Language::findOrFail($language->lge_id);
        return view('admin.pages.languages.edit', ['breadcrumbs' => $breadCrumbs, 'lang' => $lang]);
    }

    /**
     * @param Request $request
     * @param Language $language
     */
    public function update(Request $request, Language $language)
    {
        $this->validate($request, [
            'lge_abbreviation' => 'required|string|max:4',
            'lge_name' => 'required|string|max:40'
        ]);
        try
        {
            if (in_array($language->lge_abbreviation, ['cs', 'en']))
            {
                return back()->with('error', __('alerts.cant_update_primary_languages'));
            }
            $exists = Language::where('lge_abbreviation', $request->input('lge_abbreviation'))->first();
            if (isset($exists->lge_id) && $exists->lge_id != $language->lge_id)
            {
                throw new \Exception();
            }
            $language->lge_abbreviation = $request->input('lge_abbreviation');
            $language->lge_name = $request->input('lge_name');
            $language->save();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.language'), 'updated' => __('alerts.successfully_updated')]));
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
     * @param Language $language
     */
    public function destroy(Language $language)
    {
        try
        {
            if (Language::count() < 3 || in_array($language->lge_abbreviation, ['cs', 'en']))
            {
                return back()->with('error', __('alerts.cant_delete_primary_languages'));
            }
            $language->delete();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.language'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            return back()->with('error', __('alerts.unknown_error'));
        }
    }
}
