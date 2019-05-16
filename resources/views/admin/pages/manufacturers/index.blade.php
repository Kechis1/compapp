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
                        {{__('pages.manufacturers')}}
                    </div>
                    <div class="card-body">
                        <a href="{{action('ManufacturerController@create')}}" class="mb-4 btn btn-primary">{{__('buttons.add')}}</a>
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>{{__('label.name')}}</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($murs as $mur)
                                        <tr>
                                            <td>{{$mur->mur_id}}</td>
                                            <td>{{$mur->mur_name}}</td>
                                            <td>
                                                <a href="{{action('ManufacturerController@edit', $mur)}}" class="d-inline btn btn-sm btn-outline-secondary">{{__('buttons.show')}}</a>
                                                <form action="{{ action('ManufacturerController@destroy', $mur) }}" method="post" class="d-inline">
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
                                    {{__('pages.manufacturers').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$murs->count() == 0 ? $offset : $offset+1}}-{{$offset+$murs->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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