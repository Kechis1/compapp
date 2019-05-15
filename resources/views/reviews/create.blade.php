<form action="{{ action($store_controller, $url) }}" method="post" class="mb-4 needs-validation" novalidate>
    {{ csrf_field() }}
    <div class="form-group">
        <label for="email">{{__('inputs.email')}}: <span class="text-danger">*</span></label>
        <input type="email" name="email" class="form-control" id="email" aria-describedby="emailHelp" maxlength="100" placeholder="{{__('inputs.email')}}" required>
    </div>
    <div class="form-group">
        <label for="title">{{__('inputs.title')}}: <span class="text-danger">*</span></label>
        <input type="text" name="title" class="form-control" id="title" placeholder="{{__('inputs.title')}}" maxlength="50" required>
    </div>
    <div class="form-group">
        <label for="message">{{__('inputs.message')}}: <span class="text-danger">*</span></label>
        <textarea class="form-control" name="message" id="message" placeholder="{{__('inputs.message')}}" required></textarea>
    </div>
    <div class="form-group">
        <label for="pros">{{__('inputs.pros')}}</label>
        <textarea class="form-control" name="pros" id="pros" placeholder="{{__('inputs.pros')}}"></textarea>
    </div>
    <div class="form-group">
        <label for="cons">{{__('inputs.cons')}}</label>
        <textarea class="form-control" name="cons" id="cons" placeholder="{{__('inputs.cons')}}"></textarea>
    </div>
    <div class="form-group">
        <div class="rating">
            <label>
                <input type="radio" name="stars" value="1" required/>
                <span class="icon">★</span>
            </label>
            <label>
                <input type="radio" name="stars" value="2" required/>
                <span class="icon">★</span>
                <span class="icon">★</span>
            </label>
            <label>
                <input type="radio" name="stars" value="3" required/>
                <span class="icon">★</span>
                <span class="icon">★</span>
                <span class="icon">★</span>
            </label>
            <label>
                <input type="radio" name="stars" value="4" required/>
                <span class="icon">★</span>
                <span class="icon">★</span>
                <span class="icon">★</span>
                <span class="icon">★</span>
            </label>
            <label>
                <input type="radio" name="stars" value="5" checked required/>
                <span class="icon">★</span>
                <span class="icon">★</span>
                <span class="icon">★</span>
                <span class="icon">★</span>
                <span class="icon">★</span>
            </label></div>
    </div>
    <button type="submit" class="btn btn-primary">{{__('buttons.add')}}</button>
</form>