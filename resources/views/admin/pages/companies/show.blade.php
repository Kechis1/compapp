@extends('layout')

@section('content')
    <div id="wrapper">
        @include('admin.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')
                @include('portal.includes.messages')
                <div class="card">
                    <div class="card-header">
                        {{__('pages.companies').' - '.__('pages.detail')}}
                    </div>
                    <div class="card-body">

                        <ul class="list-inline">
                            @foreach(\App\Models\Language::all() as $lang)
                                <li class="list-inline-item">
                                    @if($lang->lge_id == $lang_active)
                                        <a class="btn btn-primary" href="?lang={{$lang->lge_id}}">{{$lang->lge_abbreviation}}</a>
                                    @else
                                        <a class="btn btn-outline-primary" href="?lang={{$lang->lge_id}}">{{$lang->lge_abbreviation}}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <div class="table-responsive">
                            @if($act->reviews()->where('lge_id', $lang_active)->count() > 0)
                                <form action="{{ action('CompanyController@destroyReview', $act) }}" method="post" class="d-inline">
                                    @csrf
                                    {{ method_field('DELETE') }}
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="bg-light text-center">
                                            <th colspan="5">{{__('pages.reviews')}}</th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input v-model="checkReviewAll" @change="checkAllReviews()" type="checkbox" name="checkReviewsAll" class="custom-control-input" id="checkReviewsAll">
                                                    <label class="custom-control-label" for="checkReviewsAll"></label>
                                                </div>
                                            </th>
                                            <th>{{__('label.created')}}</th>
                                            <th>{{__('inputs.title')}}</th>
                                            <th>{{__('inputs.message')}}</th>
                                            <th>{{__('label.rating')}} [%]</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($act->reviews()->where('lge_id', $lang_active)->get() as $rvw)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input v-model="checkReview[{{$loop->index}}]" value="{{$rvw->rvw_id}}" type="checkbox" name="checkReview[]" class="custom-control-input" id="checkReview{{$rvw->rvw_id}}">
                                                        <label class="custom-control-label" for="checkReview{{$rvw->rvw_id}}"></label>
                                                    </div>
                                                </td>
                                                <td>{{$rvw->user()->first()->act_email}}</td>
                                                <td>{{$rvw->rvw_title}}</td>
                                                <td>{{$rvw->rvw_message}}</td>
                                                <td>{{$rvw->rvw_rating*20}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="bg-light">
                                                <th colspan="5">
                                                    <button class="btn btn-danger" type="submit">{{__('buttons.delete')}}</button>
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.reviews').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>

                        <div class="table-responsive">
                            @if($act->product_enterprises()->count() > 0)
                                <form action="{{ action('CompanyController@productUpdate', $act) }}" method="post" class="d-inline">
                                    @csrf
                                    {{ method_field('PATCH') }}
                                    <table class="table table-bordered">
                                        <thead>
                                        <tr class="bg-light text-center">
                                            <th colspan="6">{{__('pages.products')}}</th>
                                        </tr>
                                        <tr>
                                            <th>
                                                <div class="custom-control custom-checkbox">
                                                    <input v-model="checkProductAll" @change="checkAllProduct()" type="checkbox" name="checkProductAll" class="custom-control-input" id="checkProductAll">
                                                    <label class="custom-control-label" for="checkProductAll"></label>
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>URL</th>
                                            <th>{{__('label.price')}} [Kč]</th>
                                            <th>{{__('label.availability')}}</th>
                                            <th>{{__('label.is_active')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($act->product_enterprises()->get() as $pee)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input v-model="checkProduct[{{$loop->index}}]" type="checkbox" name="checkProduct[]" value="{{$pee->pee_id}}" class="custom-control-input" id="checkProduct{{$loop->index}}">
                                                        <label class="custom-control-label" for="checkProduct{{$loop->index}}"></label>
                                                    </div>
                                                </td>
                                                <td>{{$pee->put_id}}</td>
                                                <td>{{$pee->pee_url}}</td>
                                                <td>{{$pee->pee_price}}</td>
                                                <td>
                                                    @if($pee->pee_availability==0)
                                                        <p class="text-success m-0">{{__('label.in_stock')}}</p>
                                                    @elseif($pee->pee_availability==-1)
                                                        <p class="text-muted m-0">{{__('label.out_of_stock')}}</p>
                                                    @else
                                                        <p class="text-dark m-0">{{__('label.within_days', ["days" => $pee->pee_availability])}}</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($pee->pee_active)
                                                        <span class="badge badge-success">{{__('label.yes')}}</span>
                                                    @else
                                                        <span class="badge badge-danger">{{__('label.no')}}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="6">
                                                <button class="btn btn-success" name="productBtn" value="1" type="submit">{{__('buttons.activate')}}</button>
                                                <button class="btn btn-danger" name="productBtn" value="2" type="submit">{{__('buttons.deactivate')}}</button>
                                            </th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.products').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection