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
                        {{__('pages.images')}}
                    </div>
                    <div class="card-body">
                        <a href="{{action('ImageController@create')}}" class="mb-4 btn btn-primary">{{__('buttons.add')}}</a>
                        <div class="table-responsive">
                            @if($count>0)
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th></th>
                                        <th>{{__('label.name')}}</th>
                                        <th>{{__('label.size')}} [KB]</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($iaes as $iae)
                                        <tr>
                                            <td>{{$iae->iae_id}}</td>
                                            <td><img alt="" style="max-width:50px;max-height: 50px;" src="{{ asset('storage/'.$iae->iae_path.'.'.$iae->iae_type) }}"></td>
                                            <td>{{$iae->iae_name}}</td>
                                            <td>{{$iae->iae_size}}</td>
                                            <td>
                                                <form action="{{ action('ImageController@destroy', $iae) }}" method="post" class="d-inline">
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
                                    {{__('pages.images').' '.__('alerts.not_found')}}
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-sm-12 col-md-5">
                                <span class="items-count text-muted">{{$iaes->count() == 0 ? $offset : $offset+1}}-{{$offset+$iaes->count()}} {{__('label.of')}} {{$count}} {{__('label.entries')}}</span>
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