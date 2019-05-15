@extends('layout')

@section('content')
    <div id="wrapper">
        @include('shop.includes.sidebar')
        <div id="content-wrapper">
            <div class="container-fluid">
                @include('breadcrumbs')

                <div class="alert alert-info">
                    {{__('alerts.feed_parameters_table')}}.<br>
                    {{__('alerts.feed_parameters_tag', ['tag' => __('alerts.tag_parameter')])}}.<br>
                    <br>
                    <b>{{__('alerts.example')}}:</b>
                    <code class="text-dark">
                        {{__('alerts.feed_parameters_example', ['param_name' => __('label.height')])}}
                    </code>
                    <br><br>
                    {{__('label.feed_detail')}} <a class="alert-link" href="{{action('ShopController@feed')}}">{{__('label.here')}}</a>.
                </div>

                <div class="card">
                    <div class="card-header">
                        {{__('pages.parameters')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>{{__('pages.parameters')}}</th>
                                        <th>{{__('label.numeric')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($params as $param)
                                        @php
                                            $la = $param->languages();
                                            $langs = $la->get();
                                        @endphp
                                        <tr>
                                            <td>
                                                <table>
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center" colspan="{{$la->count()}}">{{__('label.language')}} [{{__('label.unit')}}]</th>
                                                        </tr>
                                                        <tr>
                                                            @foreach($langs as $lang)
                                                                <td>{{$lang->lge_name}} @if($lang->pivot->pls_unit)[{{$lang->pivot->pls_unit}}]@endif</td>
                                                            @endforeach
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th class="text-center" colspan="{{$la->count()}}">{{__('label.name')}}</th>
                                                        </tr>
                                                        <tr>
                                                            @foreach($langs as $lang)
                                                                <td>{{$lang->pivot->pls_name}}</td>
                                                            @endforeach
                                                        </tr>
                                                        <tr>
                                                            <th class="text-center" colspan="{{$la->count()}}">{{__('label.values')}}</th>
                                                        </tr>
                                                        <tr>
                                                            @foreach($langs as $lang)
                                                                <td>
                                                                    <table>
                                                                        <tbody>
                                                                            @php
                                                                                $values = \App\Models\ParameterValue::where('prr_id', $lang->pivot->prr_id)->get()->toArray();
                                                                            @endphp

                                                                            @foreach(\App\Models\ParameterValueLanguage::whereIn('pve_id', array_column($values, 'pve_id'))->where([['pvs_active', true],['lge_id', $lang->lge_id]])->get() as $value)
                                                                                <tr><td>{{$value->pvs_value}}</td></tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            @endforeach
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                @if($param->prr_numeric)
                                                    <span class="badge badge-success">{{__('label.yes')}}</span>
                                                @else
                                                    <span class="badge badge-danger">{{__('label.no')}}</span>
                                                @endif
                                            </td>

                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.parameters').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$offset+1}}-{{$offset+$params->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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