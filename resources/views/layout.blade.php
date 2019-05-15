<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="app">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Comparison') }} - admin</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" type="text/javascript"></script>

    <!-- Styles -->
    <link href="{{ asset('css/backend.css') }}" rel="stylesheet" type="text/css">
</head>
<body>
<div id="admin">
    @include('header')
    @yield('content')
</div>

<script>
            @if(\Illuminate\Support\Facades\Auth::check())
    let csActive = '{{\App\Models\Language::where('lge_id', \Illuminate\Support\Facades\Auth::user()->act_lge_id)->first()->lge_abbreviation}}' == 'cs';
            @else
    let csActive = VueCookie.get('lang') !== null && VueCookie.get('lang') == 'cs';
            @endif

    let admin = new Vue({
            el: '#admin',
            data: {
                images: [],
                guideId: null,
                cey_id: 0,
                gle_active: true,
                gle_name: null,
                errors: false,
                successMessage: false,
                loaded: true,
                parameters: [],
                langActive: {{\Illuminate\Support\Facades\Input::get('lang', \App\Models\Language::first()->lge_id)}},
                newChoice: {
                    "type": "V",
                    "id": null,
                    "title": "Value",
                    "pve_id": [0],
                    "min": null,
                    "max": null,
                    "description": null,
                    "image": null,
                    "advantages": [],
                    "disadvantages": [],
                    "default": true,
                    "step": null,
                    "values": []
                },
                foundStep: 0,
                newStep: {
                    "type": "S",
                    "id": null,
                    "title": "Step",
                    "prr_id": 0,
                    "description": null,
                    "choice": 0,
                    "items": []
                },
                selected: {
                    selectedStep: null,
                    selectedChoice: null
                },
                list: [],
                isToggleSidebarActive: false,
                langItems: [
                    {lge_abbreviation: 'en', lge_name: 'english', active: !csActive},
                    {lge_abbreviation: 'cs', lge_name: 'čeština', active: csActive},
                ],
                checkReviewAll: false,
                checkReview: [],
                checkProductAll: false,
                checkProduct: [],
                checkReviewLength: @if(isset($act) && strpos(Route::currentRouteName(), 'companies.show')!==FALSE && strpos(Route::currentRouteName(), 'companies.show')==0) {{$act->reviews()->where('lge_id', $lang_active)->count()}} @else 0 @endif,
                checkProductLength: @if(isset($act) && strpos(Route::currentRouteName(), 'companies.show')!==FALSE && strpos(Route::currentRouteName(), 'companies.show')==0) {{$act->product_enterprises()->count()}} @else 0 @endif,
            },
            mounted() {
                this.list.push(Object.assign({}, this.newStep));
                this.generateStrings();
                this.list[0].id = this.guideId;
                this.newStep.id = this.guideId;
                this.newStep.items = [];
                this.addChoice(this.list[0]);
                this.selectGuide(this.list[0]);
                this.addChoice(this.list[0]);
                this.addStep();
                this.selectGuide(this.list[0].items[0]);
                this.selected.selectedStep = this.list[0].items[2];
                this.addChoice(this.list[0].items[2]);
                this.addChoice(this.list[0].items[2]);
                this.selected.selectedStep = null;
                this.selectGuide(this.list[0].items[2].items[0]);
                this.addStep();
                this.selectGuide(this.list[0].items[2].items[1]);
                this.addStep();
                this.selectGuide(this.list[0].items[2].items[0].step);
                this.addChoice(this.list[0].items[2].items[0].step);
                this.addChoice(this.list[0].items[2].items[0].step);
                this.selectGuide(this.list[0].items[2].items[1].step);
                this.addChoice(this.list[0].items[2].items[1].step);
            },
            methods: {
                submitForm: function () {
                    let vm = this;
                    if (vm.loaded) {
                        vm.loaded = false;
                        vm.successMessage = false;
                        vm.errors = false;
                        window.axios.post('{{ action('GuideController@store') }}', {
                            lang: this.langActive,
                            list: this.list,
                            images: this.images,
                            gle_active: this.gle_active,
                            gle_name: this.gle_name,
                            cey_id: parseInt(this.cey_id)
                        }).then(function (result) {
                            console.log(result);
                            vm.loaded = true;
                            vm.successMessage = true;
                        }).catch(function (error) {
                            vm.loaded = true;
                            vm.errors = true;
                        }).finally(function () {
                            vm.loaded = true;
                        });
                    }
                },
                onChangeCategory: function () {
                    let prrId = 0;
                    this.$http.get('/categories/'+this.cey_id+'/parameters?lang='+this.langActive).then(function (result) {
                        this.parameters = result.body;
                        if (this.parameters.length > 0) {
                            prrId = this.parameters[0].prr_id;
                        }
                        this.list[0].prr_id = 0;
                        this.changeStepParamDefault(this.list[0].items);
                        this.selected.selectedStep.prr_id = 0;
                    });
                },
                onChangeParameter: function () {
                    this.$http.get('/parameters/'+this.selected.selectedStep.prr_id+'/choices?lang='+this.langActive).then(function (result) {
                        this.changeChoiceValueDefault(this.selected.selectedStep.items, result.body);
                        if (this.selected.selectedChoice !== undefined && this.selected.selectedChoice !== null)
                        {
                            this.selected.selectedChoice.pve_id = [0];
                        }
                    });
                },
                addStep: function () {
                    this.generateStrings();
                    this.newStep.id = this.guideId;
                    this.foundStep = 0;
                    if (this.selected.selectedChoice !== null && this.selected.selectedChoice.step === null) {
                        this.getStepValue(this.list[0].items, this.selected.selectedChoice.id);
                        if (this.foundStep === 0) {
                            this.selected.selectedChoice.step = Object.assign({}, this.newStep);
                        }
                    } else if (this.selected.selectedStep !== null) {
                        if (this.selected.selectedChoice !== null) {
                            this.getStepValue(this.list[0].items, this.selected.selectedStep.items[0].id);
                            if (this.foundStep === 0) {
                                let isStep = false;
                                if (this.selected.selectedStep.items !== undefined) {
                                    this.selected.selectedStep.items.forEach(function (value) {
                                        if (value.type === "S") {
                                            isStep = true;
                                        }
                                    });
                                }
                                if (!isStep) {
                                    this.selected.selectedStep.items.push(Object.assign({}, this.newStep));
                                }
                            }
                        } else {
                            let isStep = false;
                            if (this.selected.selectedStep.items !== undefined) {
                                this.selected.selectedStep.items.forEach(function (value) {
                                    if (value.type === "S") {
                                        isStep = true;
                                    }
                                });
                            }
                            if (!isStep) {
                                this.selected.selectedStep.items.push(Object.assign({}, this.newStep));
                            }
                        }
                    }
                    this.newStep.items = [];
                },
                addChoice: function (step) {
                    this.generateStrings();
                    this.newChoice.id = this.guideId;
                    step.items.push(Object.assign({}, this.newChoice));
                    this.newStep.items = [];
                },
                onFileChange(e) {
                    let files = e.target.files || e.dataTransfer.files;
                    if (!files.length)
                        return;
                    this.createImage(files[0]);
                },
                createImage(file) {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.images.push({
                            image: e.target.result,
                            id: this.selected.selectedChoice.id
                        });
                        this.selected.selectedChoice.image = e.target.result;
                    };
                    reader.readAsDataURL(file);
                },
                selectGuide: function (item) {
                    if (item.type === "V") {
                        this.selected.selectedChoice = this.selected.selectedChoice !== null && this.selected.selectedChoice.id === item.id ? null : item;
                    } else {
                        this.selected.selectedStep = this.selected.selectedStep !== null && this.selected.selectedStep.id === item.id ? null : item;
                    }
                },
                deleteLayer: function (step, choice) {
                    if (step !== undefined && step !== null) {
                        this.getSubMenuItem(this.list[0].items, step.id);
                    }

                    if (choice !== undefined && choice !== null) {
                        this.getSubMenuItem(this.list[0].items, choice.id);
                    }
                    this.selected.selectedStep = null;
                    this.selected.selectedChoice = null;
                },
                getStepValue: function (subMenuItems, id) {
                    if (subMenuItems) {
                        for (let i = 0; i < subMenuItems.length; i++) {
                            if (subMenuItems[i].id === id) {
                                for (let i = 0; i < subMenuItems.length; i++) {
                                    if (subMenuItems[i].type === "S") {
                                        this.foundStep = 1;
                                        return true;
                                    }
                                }
                            }
                            if (subMenuItems[i].step !== undefined && subMenuItems[i].step !== null && subMenuItems[i].step.items.length > 0) {
                                this.getStepValue(subMenuItems[i].step.items, id);
                            }
                            result = this.getStepValue(subMenuItems[i].items, id);
                            if (result) {
                                return result;
                            }
                        }
                    }
                },
                getSubMenuItem: function (subMenuItems, id) {
                    if (subMenuItems) {
                        for (let i = 0; i < subMenuItems.length; i++) {
                            if (subMenuItems[i].id === id) {
                                subMenuItems.splice(i, 1);
                                return false;
                            }
                            if (subMenuItems[i].step !== undefined && subMenuItems[i].step !== null) {
                                if (subMenuItems[i].step.id === id) {
                                    subMenuItems[i].step = null;
                                    return false;
                                }
                                if (subMenuItems[i].step.items.length > 0) {
                                    this.getSubMenuItem(subMenuItems[i].step.items, id);
                                }
                            }
                            this.getSubMenuItem(subMenuItems[i].items, id);
                        }
                    }
                },
                changeChoiceValueDefault: function (subMenuItems, body) {
                    if (subMenuItems) {
                        for (let i = 0; i < subMenuItems.length; i++) {
                            if (subMenuItems[i].type === "V") {
                                subMenuItems[i].pve_id = [0];
                                subMenuItems[i].values = body;
                            }
                            if (subMenuItems[i].step !== undefined && subMenuItems[i].step !== null) {
                                if (subMenuItems[i].step.items.length > 0) {
                                    this.changeChoiceValueDefault(subMenuItems[i].step.items, body);
                                }
                            }
                            this.changeChoiceValueDefault(subMenuItems[i].items, body);
                        }
                    }
                },
                changeStepParamDefault: function (subMenuItems) {
                    if (subMenuItems) {
                        for (let i = 0; i < subMenuItems.length; i++) {
                            if (subMenuItems[i].type === "S") {
                                subMenuItems[i].prr_id = 0;
                            }
                            if (subMenuItems[i].step !== undefined && subMenuItems[i].step !== null) {
                                if (subMenuItems[i].step.type === "S") {
                                    subMenuItems[i].step.prr_id = 0;
                                }
                                if (subMenuItems[i].step.items.length > 0) {
                                    this.changeStepParamDefault(subMenuItems[i].step.items);
                                }
                            }
                            this.changeStepParamDefault(subMenuItems[i].items);
                        }
                    }
                },
                toggleSidebar: function () {
                    this.isToggleSidebarActive = !this.isToggleSidebarActive;
                },
                onLangClick: function (ab) {
                    VueCookie.set('lang', ab);
                    @if(\Illuminate\Support\Facades\Auth::check())
                        this.$http.get('/set/locale/' + ab).then(function () {
                        window.location.reload();
                    });
                    @else
                    window.location.reload();
                    @endif
                },
                checkAllReviews: function () {
                    for (let i = 0; i < this.checkReviewLength; i++) {
                        this.checkReview[i] = this.checkReviewAll;
                    }
                },
                checkAllProduct: function () {
                    for (let j = 0; j < this.checkProductLength; j++) {
                        this.checkProduct[j] = this.checkProductAll;
                    }
                },
                generateStrings: function () {
                    this.guideId = randomstring.generate(20);
                }
            }
        });

</script>
</body>
</html>