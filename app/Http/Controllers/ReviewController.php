<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Language;
use App\Models\Product;
use App\Models\ProductLanguage;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('reviews.create');
    }

    private function toJson($array)
    {
        $items = [];
        foreach (explode(PHP_EOL, $array) as $key => $item)
        {
            if(strlen(trim($item)) != 0) $items[$key]["item"] = trim($item);
        }
        return count($items) == 0 ? null : $items;
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|max:100',
            'title' => 'required|max:50',
            'message' => 'required',
            'stars' => 'required|digits_between:1,5'
        ]);

        $account = new Account;
        $account->act_date_created = now();
        $account->act_email = $request->input('email');
        $account->act_type = 'UER';
        $account->act_lge_id = Language::where('lge_abbreviation', App::getLocale())->first()->lge_id;
        $exists = Account::where('act_email', $account->act_email)->first();
        if(!isset($exists->act_id))
        {
            $account->save();
        }

        $pros = $this->toJson($request->input('pros'));
        $cons = $this->toJson($request->input('cons'));

        $review = new Review;
        $review->act_id = Account::where('act_email', $request->input('email'))->first()->act_id;
        $review->rvw_title = $request->input('title');
        $review->rvw_message = $request->input('message');
        $review->lge_id = $account->act_lge_id;
        $review->rvw_rating = $request->input('stars');
        $review->rvw_date_created = now();
        $review->rvw_cons = $cons === null || count($cons) == 0 ? null : sprintf("%s", json_encode($cons));
        $review->rvw_pros = $pros === null || count($pros) == 0 ? null : sprintf("%s", json_encode($pros));
        if  ($request->route('product'))
        {
            $review->put_id = ProductLanguage::where('ple_url', $request->route('product'))->first()->put_id;
            $exist = Review::where(['put_id' => $review->put_id, 'act_id' => $review->act_id])->first();
        }

        if  ($request->route('company'))
        {
            $review->ete_act_id = Account::where('act_id', $request->route('company'))->first()->act_id;
            $exist = Review::where(['ete_act_id' => $review->ete_act_id, 'act_id' => $review->act_id])->first();
        }

        if (!isset($exist->rvw_id)) {
            $review->save();
            return redirect('/'.$request->path())->with('success', __('alerts.created', ['object' => __('alerts.review'), 'created' => __('alerts.successfully_created')]));
        }

        return redirect('/'.$request->path())->with('error', __('alerts.already_created', ['object' => __('alerts.review'), 'already_created' => __('alerts.already_created_msg')]));
    }

    public function storeProduct(Request $request)
    {
        return $this->store($request);
    }

    public function storeCompany(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function show(Review $review)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function edit(Review $review)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Review $review)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\Response
     */
    public function destroy(Review $review)
    {
        //
    }
}
