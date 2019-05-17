@extends('portal.layout')

@section('content')
    @include('portal.includes.breadcrumbs')

    <div id="main">
        <div class="container">
            @include('portal.includes.messages')

            <h1>{{__('pages.companies')}}</h1>

            @if(count($companies) > 0)
                <div class="table-responsive">
                    <table class="table bg-white border">
                        <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <td class="text-center align-middle" style="width:80px;">
                                        @if(isset($company->image))
                                            <img alt="{{$company->image->iae_name}}" src="{{ asset('storage/'.$company->image->iae_path.'.'.$company->image->iae_type) }}" data-holder-rendered="true" style="max-width: 60px; max-height: 60px;">
                                        @else
                                            <i class="fas text-secondary fa-3x fa-image"></i>
                                        @endif
                                    </td>
                                    <th class="text-left align-middle">
                                        <a class="text-primary" href="/companies/{{$company->act_id}}">{{ $company->ete_name }}</a>
                                    </th>
                                    <td class="text-center align-middle">
                                        {{$company->act_email}}
                                    </td>
                                    <td class="text-center align-middle">
                                        {{$company->ete_cellnumber}}
                                    </td>
                                    <td class="text-center align-middle">
                                        <a href="/companies/{{$company->act_id}}">
                                            <ul class="list-unstyled m-0">
                                                @php
                                                    $reviews = $company->reviews->where('ete_act_id', '=', $company->act_id)->where('lge_id','=',$lge_id);
                                                @endphp
                                                @if(!isset($reviews) || $reviews->count() == 0)
                                                    <li class="text-muted">{{__('label.no_rating')}}</li>
                                                @else
                                                    @php
                                                        $rating = ($reviews->sum('rvw_rating')/$reviews->count())*20;
                                                    @endphp
                                                    <li class="@if($rating < 50) text-danger @elseif($rating<70) text-dark @else text-success @endif">{{intval($rating)}}%</li>
                                                @endif
                                                <li class="text-dark">{{$reviews->count()}} {{$reviews->count()>4||$reviews->count()==0?__('label.reviews'):__('label.reviews_sa')}}</li>
                                            </ul>
                                        </a>
                                    </td>
                                    <td class="text-right align-middle">
                                        <a class="btn btn-outline-secondary" role="button" href="/companies/{{$company->act_id}}">{{__('buttons.show_reviews')}}</a>
                                        <a class="btn btn-primary" role="button" href="{{$company->ete_url_web}}">{{__('buttons.to_the_shop')}}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div id="shopPagination" class="pagination-wrapper">
                    @if($pages > 1)
                        <nav aria-label="Page navigation example" class="pages">
                            <ul class="pagination">
                                @for($i = 1; $i <= $pages; $i++)
                                    @if($i==$page)
                                        <li class="page-item active"><a class="page-link" href="#">{{ $i }}</a></li>
                                    @else
                                        <li class="page-item"><a class="page-link" href="/companies?page={{$i}}">{{ $i }}</a></li>
                                    @endif
                                @endfor
                            </ul>
                        </nav>
                    @endif
                </div>
            @else
                <div class="alert alert-primary" role="alert">
                    {{ ucfirst(__('pages.companies')) . ' ' . __('alerts.not_found') }}
                </div>
            @endif
        </div>
    </div>
@endsection