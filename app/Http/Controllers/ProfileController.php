<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
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
        //
    }

    /**
     * @param Request $request
     * @return int
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Account $Account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $Account)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Account $Account
     * @return \Illuminate\Http\Response
     */
    public function edit(Account $Account)
    {
        self::initLocale();
        $breadCrumb = new \StdClass;
        $breadCrumb->name = __('pages.profile');
        $breadCrumb->active = TRUE;

        return view('admin.pages.profile', ['breadcrumbs' => [$breadCrumb], 'user' => Auth::user()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Account $Account
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Account $Account)
    {
        $this->validate($request, [
            'act_email' => 'required|email|string|max:100',
            'amr_first_name' => 'required|string|max:30',
            'amr_last_name' => 'required|string|max:40',
            'new_amr_password' => $request->input('new_amr_password') === NULL ? '' : 'string|min:6',
            'new_password_again' => $request->input('new_amr_password') === NULL ? '' : 'string|min:6|same:new_amr_password'
        ]);

        try
        {
            $exists = Account::where('act_email', $request->input('act_email'))->first();
            if (isset($exists->act_id) && $exists->act_id != Auth::id())
            {
                throw new \Exception();
            }
            $account = Auth::user();
            $account->act_email = $request->input('act_email');
            if (strlen($request->input('new_amr_password')) > 5)
            {
                $account->amr_password = Hash::make($request->input('new_amr_password'));
            }
            $account->amr_first_name = $request->input('amr_first_name');
            $account->amr_last_name = $request->input('amr_last_name');
            $account->save();
            $request->session()->flash('success', __('alerts.updated', ['object' => __('alerts.account'), 'updated' => __('alerts.successfully_updated')]));
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
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Account $Account
     * @return \Illuminate\Http\Response
     */
    public function destroy(Account $Account)
    {
        //
    }
}
