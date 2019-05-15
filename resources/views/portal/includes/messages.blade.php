@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="m-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ucfirst(session('success'))}}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ucfirst(session('error'))}}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info">
        {{ucfirst(session('info'))}}
    </div>
@endif
