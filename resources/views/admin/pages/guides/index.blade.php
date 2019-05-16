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
                        {{__('pages.guides')}}
                    </div>
                    <div class="card-body">
                        <a href="{{action('GuideController@create')}}" class="mb-4 btn btn-primary">{{__('buttons.add')}}</a>
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('pages.category')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($gdes as $gde)
                                        @php
                                            $gdePivot = $gde->languages()->where('guides_languages.lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first();
                                            $catPivot = $gde->category()->first()->languages()->where('category_languages.lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first();
                                        @endphp
                                        <tr>
                                            <td>{{$gde->gde_id}}</td>
                                            <td>@if($gdePivot!==null){{$gdePivot->pivot->gle_name}}@endif</td>
                                            <td>@if($catPivot!==null){{$catPivot->pivot->cle_name}}@endif</td>
                                            <td>
                                                <form action="{{ action('GuideController@destroy', $gde) }}" method="post" class="d-inline">
                                                    @csrf
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="btn btn-sm btn-danger">{{__('buttons.delete')}}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.guides').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$gdes->count() == 0 ? $offset : $offset+1}}-{{$offset+$gdes->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
                            </div>
                            <div class="col-sm-12 col-md-7">
                                @include('pagination')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection