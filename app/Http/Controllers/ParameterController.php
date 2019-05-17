<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryLanguage;
use App\Models\CategoryParameter;
use App\Models\Language;
use App\Models\Parameter;
use App\Models\ParameterLanguage;
use App\Models\ParameterValue;
use App\Models\ParameterValueLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ParameterController extends Controller
{
    private const LIMIT = 10;
    /**
     *
     */
    public function index()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.parameters');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $prrs = Parameter::orderBy('prr_id');
        $count = $prrs->count();
        if ($offset >= $count)
        {
            $offset = 0;
            $page = 1;
        }
        if ($count > 0)
        {
            $pages = self::calculatePages($count, self::LIMIT);
        }
        $prrs = $prrs
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
        return view('admin.pages.parameters.index', ['pagination' => self::getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'prrs' => $prrs]);
    }

    /**
     *
     */
    public function create()
    {
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.parameters');
        $breadCrumb->url = action('ParameterController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $langActive = Input::get('lang', Language::first()->lge_id);
        return view('admin.pages.parameters.create', ['breadcrumbs' => $breadCrumbs, 'lang_active' => $langActive]);
    }

    /**
     * @param Request $request
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'pls_name' => 'required|string|max:80'
        ]);
        try
        {
            DB::beginTransaction();
            $parameter = new Parameter();
            $parameter->prr_numeric = $request->input('prr_numeric',0) == 0 ? 0 : 1;
            $parameter->save();
            $parameterLang = new ParameterLanguage();
            $parameterLang->prr_id = $parameter->prr_id;
            $parameterLang->lge_id = $request->input('lang', Language::first()->lge_id);
            $parameterLang->pls_name = $request->input('pls_name');
            $parameterLang->pls_unit = $request->input('pls_unit');
            $parameterLang->save();
            foreach (explode(PHP_EOL, $request->input('pvs_value','')) as $key => $item)
            {
                if(strlen(trim($item)) != 0)
                {
                    $parameterValue = new ParameterValue();
                    $parameterValue->prr_id = $parameter->prr_id;
                    $parameterValue->save();
                    $parameterValueLang = new ParameterValueLanguage();
                    $parameterValueLang->lge_id = $request->input('lang');
                    $parameterValueLang->pve_id = $parameterValue->pve_id;
                    $parameterValueLang->pvs_value = trim($item);
                    $parameterValueLang->pvs_active = true;
                    $parameterValueLang->save();
                }
            }
            DB::commit();
            $request->session()->flash('success', __('alerts.created', ['object' => __('alerts.parameter'), 'created' => __('alerts.successfully_created')]));
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
     * @param Parameter $parameter
     */
    public function show(Parameter $parameter)
    {
        //
    }

    /**
     * @param Parameter $parameter
     */
    public function edit(Parameter $parameter)
    {
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.parameters');
        $breadCrumb->url = action('ParameterController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.update');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $prr = Parameter::findOrFail($parameter->prr_id);
        $langActive = Input::get('lang', Language::first()->lge_id);
        $pvs_value = '';
        foreach($prr->parameter_values()->get() === null ? [] : $prr->parameter_values()->get() as $pve)
        {
            $pveLang = $pve->languages()->where('parameter_value_languages.lge_id', $langActive)->get();
            foreach ($pveLang === null ? [] : $pveLang as $pveItem)
            {
                $pvs_value .= $pveItem->pivot->pvs_value . "\n";
            }
        }
        return view('admin.pages.parameters.edit', ['breadcrumbs' => $breadCrumbs, 'prr' => $prr, 'lang_active' => $langActive, 'pvs_value' => $pvs_value]);
    }

    /**
     * @param Request $request
     * @param Parameter $parameter
     */
    public function update(Request $request, Parameter $parameter)
    {
        $this->validate($request, [
            'pls_name' => 'required|string|max:80'
        ]);
        try
        {
            Parameter::findOrFail($parameter->prr_id);
            DB::beginTransaction();
            $parameter->prr_numeric = $request->input('prr_numeric',0) == 0 ? 0 : 1;
            $parameter->update();

            if ($parameter->languages()->where('parameter_languages.lge_id', $request->input('lang'))->first() !== null)
            {
                ParameterLanguage::where([['parameter_languages.lge_id',$request->input('lang')],['prr_id', $parameter->prr_id]])
                    ->update(
                        ['pls_name' => $request->input('pls_name'), 'pls_unit' => $request->input('pls_unit')]
                    );
            }
            else
            {
                $parameterLang = new ParameterLanguage();
                $parameterLang->prr_id = $parameter->prr_id;
                $parameterLang->lge_id = $request->input('lang');
                $parameterLang->pls_name = $request->input('pls_name');
                $parameterLang->pls_unit = $request->input('pls_unit');
                $parameterLang->save();
            }

            ParameterValueLanguage::whereIn('pve_id', array_column(ParameterValue::where('prr_id', $parameter->prr_id)->get()->toArray(), 'pve_id'))
                ->where('lge_id', $request->input('lang'))
                ->delete();

            foreach (explode(PHP_EOL, $request->input('pvs_value','')) as $key => $item)
            {
                if(strlen(trim($item)) != 0)
                {
                    $parameterValue = new ParameterValue();
                    $parameterValue->prr_id = $parameter->prr_id;
                    $parameterValue->save();
                    $parameterValueLang = new ParameterValueLanguage();
                    $parameterValueLang->lge_id = $request->input('lang');
                    $parameterValueLang->pve_id = $parameterValue->pve_id;
                    $parameterValueLang->pvs_value = trim($item);
                    $parameterValueLang->pvs_active = true;
                    $parameterValueLang->save();
                }
            }
            DB::commit();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.parameter'), 'updated' => __('alerts.successfully_updated')]));
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
     * @param Parameter $parameter
     */
    public function destroy(Parameter $parameter)
    {
        try
        {
            $parameter->delete();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.parameter'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            return back()->with('error', __('alerts.unknown_error'));
        }
    }

    public function getParamsByCeyId($ceyId)
    {
        $langActive = Input::get('lang', Language::first()->lge_id);
        Category::findOrFail($ceyId);
        $params = ParameterLanguage::where('lge_id', $langActive)->whereIn('prr_id', array_column(CategoryParameter::where('cey_id', $ceyId)->get()->toArray(), 'prr_id'))->orderBy('pls_name')->get();
        return json_encode($params);
    }

    public function getChoicesByPrrId($prrId)
    {
        $langActive = Input::get('lang', Language::first()->lge_id);
        Parameter::findOrFail($prrId);
        $choices = ParameterValueLanguage::where('lge_id', $langActive)->whereIn('pve_id', array_column(ParameterValue::where('prr_id', $prrId)->get()->toArray(), 'pve_id'))->orderBy('pvs_value')->get();
        return json_encode($choices);
    }
}
