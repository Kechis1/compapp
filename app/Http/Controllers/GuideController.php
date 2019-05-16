<?php

namespace App\Http\Controllers;

use App\Models\CategoryLanguage;
use App\Models\ChoiceValue;
use App\Models\Guide;
use App\Models\GuideChoiceLanguage;
use App\Models\GuidesLanguage;
use App\Models\GuideStep;
use App\Models\GuideStepChoice;
use App\Models\GuideStepLanguage;
use App\Models\Image;
use App\Models\Language;
use App\Models\Parameter;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class GuideController extends Controller
{
    private const LIMIT = 10;

    /**
     *
     */
    public function index()
    {
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.guides');
        $breadCrumb->active = TRUE;
        $pages = $first = $current = $prev = $next = $last = 0;
        $page = Input::get('page', 1);
        $offset = ($page * self::LIMIT) - self::LIMIT;
        $gdes = Guide::orderBy('gde_id');
        $count = $gdes->count();
        if ($offset >= $count)
        {
            $offset = 0;
            $page = 1;
        }
        if ($count > 0)
        {
            $pages = $this->calculatePages($count);
        }
        $gdes = $gdes
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

        return view('admin.pages.guides.index', ['pagination' => $this->getPagination($first, $current, $prev, $next, $last), "offset" => $offset, 'count' => $count, "limit" => self::LIMIT, 'pages' => $pages, 'breadcrumbs' => [$breadCrumb], 'gdes' => $gdes]);
    }

    /**
     *
     */
    public function create()
    {
        $breadCrumbs = [];
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.guides');
        $breadCrumb->url = action('GuideController@index');
        $breadCrumb->active = 0;
        array_push($breadCrumbs, $breadCrumb);
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.create');
        $breadCrumb->active = 1;
        array_push($breadCrumbs, $breadCrumb);
        $langActive = Input::get('lang', Language::first()->lge_id);
        return view('admin.pages.guides.create', ['breadcrumbs' => $breadCrumbs, 'lang_active' => $langActive]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'cey_id' => 'required|numeric|exists:categories',
            'list' => 'required',
            'gle_active' => 'required|boolean',
            'gle_name' => 'required|string|max:70'
        ]);
        $langActive = $request->input('lang', Language::first()->lge_id);
        $gleActive = $request->input('gle_active');
        try
        {
            if  ($request->input('gle_active'))
            {
                $guides = Guide::where('cey_id', $request->cey_id)->get();
                foreach ($guides as $guide)
                {
                    $langGuide = $guide->languages()->where('guides_languages.lge_id', $langActive)->first();
                    if (isset($langGuide->pivot) && $langGuide->pivot->gle_active)
                    {
                        $gleActive = false;
                    }
                }
            }

            DB::beginTransaction();
            $guide = new Guide();
            $guide->cey_id = $request->input('cey_id');
            $guide->save();
            $guideLang = new GuidesLanguage();
            $guideLang->gde_id = $guide->gde_id;
            $guideLang->lge_id = $langActive;
            $guideLang->gle_name = $request->input('gle_name');
            $guideLang->gle_active = $gleActive;
            $guideLang->save();

            $list = $request->input('list', []);
            $this->recursive($list, $guide->gde_id, true, $langActive, null, $request->input('images'));

            DB::commit();
            return response()->json(NULL, 200);
        }
        catch (ValidationException $e)
        {
            DB::rollBack();
            return response()->json(["message" => $e->getMessage(), "errors" => $e->errors()], 422);
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return response()->json($e->getMessage(), 400);
        }
    }

    function recursive($array, $gdeId, $gspStart, $lgeId, $gsp, $images)
    {
        foreach($array as $value)
        {
            if (is_array($value))
            {
                if (strcmp($value["type"],"S") == 0)
                {
                    $request = new Request([
                        'prr_id' => $value["prr_id"],
                        'choice' => $value["choice"],
                        "gss_title" => strlen(trim($value["title"])) == 0 ? null : trim($value["title"])
                    ]);

                    $this->validate($request, [
                        'prr_id' => 'required|numeric|exists:parameters',
                        'choice' => 'required|numeric|between:0,2',
                        'gss_title' => 'required|string|max:100',
                    ]);
                    $guidStep = new GuideStep();
                    $guidStep->gde_id = $gdeId;
                    $guidStep->prr_id = $value["prr_id"];
                    $guidStep->gsp_choice = $value["choice"];
                    $guidStep->gsp_start = $gspStart;
                    $guidStep->save();
                    $guideStepLang = new GuideStepLanguage();
                    $guideStepLang->gsp_id = $guidStep->gsp_id;
                    $guideStepLang->lge_id = $lgeId;
                    $guideStepLang->gss_title = $value["title"];
                    $guideStepLang->gss_description = $value["description"];
                    $guideStepLang->save();
                    $gspStart = false;
                }
                else
                {
                    if ($value["step"] !== null)
                    {
                        $request = new Request([
                            'step_prr_id' => $value["step"]["prr_id"],
                            'step_choice' => $value["step"]["choice"]
                        ]);

                        $this->validate($request, [
                            'step_prr_id' => 'required|numeric',
                            'step_choice' => 'required|numeric|between:0,2',
                        ]);

                        Parameter::findOrfail($value["step"]["prr_id"]);

                        $nextStep = new GuideStep();
                        $nextStep->gde_id = $gdeId;
                        $nextStep->prr_id = $value["step"]["prr_id"];
                        $nextStep->gsp_choice = $value["step"]["choice"];
                        $nextStep->gsp_start = false;
                        $nextStep->save();
                        $guideStepLang = new GuideStepLanguage();
                        $guideStepLang->gsp_id = $nextStep->gsp_id;
                        $guideStepLang->lge_id = $lgeId;
                        $guideStepLang->gss_title = $value["step"]["title"];
                        $guideStepLang->gss_description = $value["step"]["description"];
                        $guideStepLang->save();
                        $gspStart = false;
                    }
                    $gsChoices = new GuideStepChoice();

                    $gsChoices->gsp_id = $gsp->gsp_id;
                    if ($value["default"])
                    {
                        $gsChoices->gse_default = GuideStepChoice::where([['gse_default', true], ['gsp_id', $gsp->gsp_id]])->get()->count() > 0 ? false : true;
                    }
                    $gsChoices->next_step = isset($nextStep) ? $nextStep->gsp_id : null;
                    foreach ($images as $image)
                    {
                        if (strcmp($image["id"], $value["id"])==0 && $image["image"] !== null)
                        {
                            $newImage = preg_replace('/data(.*?)base64/', '', $image["image"]);
                            preg_match('/image\/(.*?);/', $image["image"], $match);
                            $imageName = time();
                            $imagePath = $imageName . '.' . $match[1];
                            File::put(public_path(). '/storage/' . $imagePath, base64_decode($newImage));
                            $image = new Image();
                            $image->iae_type = $match[1];
                            $image->iae_path = $imageName;
                            $image->iae_name = $imageName;
                            $image->iae_size = filesize(public_path().'/storage/'.$imagePath)/1000;
                            $image->save();
                        }
                    }
                    $gsChoices->iae_id = isset($image) && isset($image->iae_id) ? $image->iae_id : null;
                    if ((isset($value["min"]) && is_numeric($value["min"])) || (isset($value["max"]) && is_numeric($value["max"])))
                    {
                        $request = new Request([
                            'min' => $value["min"],
                            'max' => $value["max"],
                        ]);

                        $this->validate($request, [
                            'min' => 'required|numeric|lt:max',
                            'max' => 'required|numeric|gt:min'
                        ]);
                        $gsChoices->gse_min = doubleval($value["min"]);
                        $gsChoices->gse_max = doubleval($value["max"]);
                    }
                    else
                    {
                        $gsChoices->gse_min = null;
                        $gsChoices->gse_max = null;
                    }

                    $gsChoices->save();
                    $isSetPveId = false;
                    foreach ($value["pve_id"] as $pveId)
                    {
                        if ($pveId != 0)
                        {
                            $request = new Request([
                                'pve_id' => $pveId,
                            ]);

                            $this->validate($request, [
                                'pve_id' => 'required|numeric|exists:parameter_values',
                            ]);
                            $choiceValues = new ChoiceValue();
                            $choiceValues->gse_id = $gsChoices->gse_id;
                            $choiceValues->pve_id = $pveId;
                            $choiceValues->save();
                            $isSetPveId = true;
                        }
                    }
                    if ($gsp->gsp_choice == 2 || (($gsp->gsp_choice >= 0 && $gsp->gsp_choice < 2) && $isSetPveId === false))
                    {
                        $request = new Request([
                            'min' => $value["min"],
                            'max' => $value["max"],
                        ]);

                        $this->validate($request, [
                            'min' => 'required|numeric|lt:max',
                            'max' => 'required|numeric|gt:min'
                        ]);
                    }

                    $request = new Request([
                        'gce_title' => $value["title"],
                    ]);

                    $this->validate($request, [
                        'gce_title' => 'required|string|max:50',
                    ]);
                    $guideChoiceLang = new GuideChoiceLanguage();
                    $guideChoiceLang->gse_id = $gsChoices->gse_id;
                    $guideChoiceLang->lge_id = $lgeId;
                    $guideChoiceLang->gce_pros = $this->toJson($value["advantages"]) == null ? null : json_encode($this->toJson($value["advantages"]));
                    $guideChoiceLang->gce_cons = $this->toJson($value["disadvantages"]) == null ? null : json_encode($this->toJson($value["disadvantages"]));
                    $guideChoiceLang->gce_title = $value["title"];
                    $guideChoiceLang->gce_description = $value["description"];
                    $guideChoiceLang->save();
                }
                if (isset($value["items"]))
                {
                    $gsp = isset($guidStep) && $guidStep !== null ? $guidStep : (isset($gsp) ? $gsp : null);
                    $this->recursive($value["items"], $gdeId, false, $lgeId, $gsp, $images);
                }
                if (isset($value["step"]) && $value["step"] !== null)
                {
                    if (isset($value["step"]["items"]))
                    {
                        $this->recursive($value["step"]["items"], $gdeId, false, $lgeId, isset($nextStep) ? $nextStep : null, $images);
                    }
                }
            }
        }
    }

    private function toJson($array)
    {
        $items = [];
        if (!is_array($array))
        {
            foreach (preg_split('/\r\n|\r|\n/', $array) as $key => $item)
            {
                if (strlen(trim($item)) != 0) $items[$key]["item"] = trim($item);
            }
        }
        return count($items) == 0 ? null : $items;
    }

    /**
     * @param Guide $guide
     */
    public function show(Guide $guide)
    {
    }

    /**
     * @param Guide $guide
     */
    public function edit(Guide $guide)
    {

    }

    /**
     * @param Request $request
     * @param Guide $guide
     */
    public function update(Request $request, Guide $guide)
    {

    }

    /**
     * @param Guide $guide
     */
    public function destroy(Guide $guide)
    {
        try
        {
            DB::beginTransaction();
            $steps = GuideStep::where('gde_id', $guide->gde_id)->get();

            foreach ($steps->count() > 0 ? $steps : [] as $step)
            {
                $images = Image::whereIn('iae_id', array_column(GuideStepChoice::where('gsp_id', $step->gsp_id)->whereNotNull('iae_id')->get()->toArray(), 'iae_id'))->get();
                foreach ($images as $image)
                {
                    echo $image->iae_path;
                    Storage::delete('public/'.$image->iae_path.'.'.$image->iae_type);
                }
            }
            $guide->delete();
            DB::commit();
            return back()->with('success', __('alerts.deleted', ['object' => __('alerts.guide'), 'deleted' => __('alerts.successfully_deleted')]));
        }
        catch (\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', __('alerts.unknown_error'));
        }
    }

    private function calculatePages($count)
    {
        return ceil($count / self::LIMIT);
    }

    private function getPagination($first, $current, $prev, $next, $last)
    {
        return [$first, $current, $prev, $next, $last];
    }

    public function getGuideByPage($page)
    {
        $guide = NULL;
        try
        {
            $guide = GuidesLanguage::where([
                ['gle_active', '=', TRUE],
                ['lge_id', '=', Language::where('lge_abbreviation', '=', App::getLocale())->first()->lge_id],
                ['gde_id', '=', Guide::where(['cey_id' => CategoryLanguage::where('cle_url', '=', substr($page, strlen('category/')))->first()->cey_id])->first()->gde_id]
            ])->first();
            if (strpos($page, 'category') != 0 || $guide === NULL)
            {
                $title = __('buttons.guide_title');
                $state = 0;
                $body = GuidesLanguage::where([['gle_active', '=', TRUE], ['lge_id', '=', Language::where('lge_abbreviation', '=', App::getLocale())->first()->lge_id]])->get();
                return json_encode(["title" => $title, "list" => 1, "body" => $body, "state" => $state]);
            }
        }
        catch (\Exception $e)
        {
            $title = __('buttons.guide_title');
            $state = 0;
            $body = GuidesLanguage::where([['gle_active', '=', TRUE], ['lge_id', '=', Language::where('lge_abbreviation', '=', App::getLocale())->first()->lge_id]])->get();
            return json_encode(["title" => $title, "list" => 1, "body" => $body, "state" => $state]);
        }
        $step = $guide->guide()->first()->guide_steps()->where('gsp_start', 1)->first();
        return $this->getStepInfo($step);
    }

    public function getStepByChoice($choiceId)
    {
        try
        {
            $next = GuideStepChoice::find($choiceId)->guide_next_step()->first();
            if ($next === NULL)
            {
                return json_encode(["finished" => TRUE]);
            }
            return $this->getStepInfo($next);
        }
        catch (\Exception $e)
        {
            return json_encode(["finished" => TRUE]);
        }
    }

    public function getStepByGuide($guideId)
    {
        try
        {
            $next = GuideStep::where('gde_id', $guideId)->first();
            if ($next === NULL)
            {
                return json_encode(["error" => TRUE]);
            }
            return $this->getStepInfo($next);
        }
        catch (\Exception $e)
        {
            return json_encode(["error" => TRUE]);
        }
    }

    private function getStepInfo($guide)
    {
        $category = CategoryLanguage::where([['cey_id', '=', $guide->guide()->first()->cey_id], ['lge_id', '=', Language::where('lge_abbreviation', App::getLocale())->first()->lge_id]])->first()->cle_url;
        $stepLang = $guide->languages()->where('guide_step_languages.lge_id', Language::where('lge_abbreviation', App::getLocale())->first()->lge_id)->first();
        $choices = $guide->guide_step_choices()->get();
        $title = $stepLang->pivot->gss_title;
        $body = [
            "prr_id" => $guide->prr_id,
            "step_desc" => $stepLang->pivot->gss_description,
            "step_choice" => $guide->gsp_choice,
            "choices" => []
        ];
        $state = 1;
        $choiceModel = NULL;
        foreach ($choices as $choice)
        {
            $choiceLang = $choice->guide_choice_languages()->where('guide_choice_languages.lge_id', Language::where('lge_abbreviation', App::getLocale())->first()->lge_id)->first();
            $image = NULL;
            if ($choice->iae_id !== NULL && $choice->iae_id != 0)
            {
                $image = Image::find($choice->iae_id);
                $image = $image->iae_path . '.' . $image->iae_type;
            }
            $pveIds = [];
            foreach (ChoiceValue::where('gse_id', $choice->gse_id)->get() as $pveId)
            {
                array_push($pveIds, $pveId->pve_id);
            }
            array_push($body["choices"],
                [
                    "gse_id" => $choice->gse_id,
                    "next_step" => $choice->next_step,
                    "image" => $image,
                    "gse_min" => $choice->gse_min,
                    "gse_max" => $choice->gse_max,
                    "gce_pros" => json_decode($choiceLang->gce_pros, 1),
                    "gce_cons" => json_decode($choiceLang->gce_cons, 1),
                    "gce_title" => $choiceLang->gce_title,
                    "gce_description" => $choiceLang->gce_description,
                    "pve_ids" => $pveIds
                ]
            );
            if ($choice->gse_default == 1)
            {
                $choiceModel = $choice->gse_id;
            }
        }
        return json_encode(["title" => $title, "start" => $guide->gsp_start, "category" => $category, "body" => $body, "state" => $state, "choice_model" => $choiceModel]);
    }
}
