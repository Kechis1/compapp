<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container">
        <div class="col-md-2">
            <button class="btn btn-primary text-white" data-toggle="modal" data-target="#guide" role="button">{{ __('buttons.guide_title') }}</button>
        </div>
        <div class="col-md-8">
            <div class="active-cyan-4">
                <form method="get" action="/search">
                    <input name="search" class="form-control" type="text" placeholder="{{ __('inputs.search') }}" value="@if(isset($search)){{$search}}@endif" aria-label="Search">
                    <button class="search btn btn-primary" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="col-md-2">

            @if ($languages->count() > 0)
                <select id="select" class="form-control" name="lang" @change="onChange()" v-model="key">

                    @foreach ($languages as $item)
                        @if ($item->lge_abbreviation == $user_lang)
                            <option value="{{ $item->lge_abbreviation }}" selected>{{ $item->lge_name }}</option>
                        @else
                            <option value="{{ $item->lge_abbreviation }}">{{ $item->lge_name }}</option>
                        @endif
                    @endforeach

                </select>
                <script>
                    const app = new Vue({
                        el: '#select',
                        data: {
                            key: VueCookie.get('lang') !== null ? VueCookie.get('lang') : 'en'
                        },
                        methods: {
                            onChange: function() {
                                VueCookie.set('lang', this.key);
                                this.$http.get('{{Config::get('app.url')}}/set/locale/'+this.key).then(function () {
                                    window.location.reload();
                                });
                            }
                        }
                    });
                </script>

            @endif
        </div>
    </div>
</nav>