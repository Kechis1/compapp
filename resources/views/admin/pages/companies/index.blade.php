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
                        {{__('pages.companies')}}
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th></th>
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('inputs.tin')}}</th>
                                        <th>{{__('label.is_active')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($acts as $act)
                                        <tr>
                                            <td>{{$act->act_id}}</td>
                                            <td>
                                                @if($act->image()->first() !== null)
                                                    <img alt="" src="{{ asset('storage/'.$act->image()->first()->iae_path.'.'.$act->image()->first()->iae_type) }}"  style="max-width: 40px; max-height: 40px;">
                                                @else
                                                    <i class="fas text-secondary fa-2x fa-image"></i>
                                                @endif
                                            </td>
                                            <td>{{$act->ete_name}}</td>
                                            <td>{{$act->ete_tin}}</td>
                                            <td>
                                                @if($act->amr_active)
                                                    <span class="badge badge-success">{{__('label.yes')}}</span>
                                                @else
                                                    <span class="badge badge-danger">{{__('label.no')}}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{action('CompanyController@show', $act)}}" class="d-inline btn btn-sm btn-outline-secondary">{{__('buttons.show')}}</a>
                                                <form action="{{ action('CompanyController@statusUpdate', $act) }}" method="post" class="d-inline">
                                                    @csrf
                                                    {{ method_field('PATCH') }}
                                                    @if($act->amr_active)
                                                        <input name="deactivate" value="1" type="hidden">
                                                        <button type="submit" class="btn btn-sm btn-danger">{{__('buttons.deactivate')}}</button>
                                                    @else
                                                        <input name="activate" value="1" type="hidden">
                                                        <button type="submit" class="btn btn-sm btn-success">{{__('buttons.activate')}}</button>
                                                    @endif
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div class="alert alert-primary">
                                    {{__('pages.companies').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$offset+1}}-{{$offset+$acts->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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