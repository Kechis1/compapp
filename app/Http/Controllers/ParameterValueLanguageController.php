<?php

namespace App\Http\Controllers;

use App\Models\CategoryLanguage;
use App\Models\ChoiceValue;
use App\Models\Guide;
use App\Models\GuidesLanguage;
use App\Models\GuideStep;
use App\Models\GuideStepChoice;
use App\Models\Image;
use App\Models\Language;
use App\Models\ParameterValue;
use App\Models\ParameterValueLanguage;
use Illuminate\Support\Facades\App;
use Request;

class ParameterValueLanguageController extends Controller
{
    /**
     *
     */
    public function index()
    {

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
     * @param Guide $Guide
     */
    public function show(Guide $Guide)
    {
        //
    }

    /**
     * @param Guide $Guide
     */
    public function edit(Guide $Guide)
    {
        //
    }

    /**
     * @param Request $request
     * @param Guide $Guide
     */
    public function update(Request $request, Guide $Guide)
    {
        //
    }

    /**
     * @param Guide $Guide
     */
    public function destroy(Guide $Guide)
    {
        //
    }

    public function getValuesByPrrIdAndMinMax($prrId, $min, $max)
    {
        $pveIds = array_column(ParameterValue::where('prr_id', $prrId)->get()->toArray(), 'pve_id');
        $pveIds = ParameterValueLanguage::whereIn('pve_id', $pveIds)
            ->where(['pvs_active' => true, 'lge_id' => Language::where('lge_abbreviation', App::getLocale())->first()->lge_id])
            ->whereBetween('pvs_value', [doubleval($min), doubleval($max)])
            ->get();
        return json_encode(["pve_ids" => array_column($pveIds->toArray(), 'pve_id')]);
    }
}