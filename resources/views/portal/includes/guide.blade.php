<div id="guide" aria-hidden="true" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" v-bind:class="{'modal-xl': item!==null&&item.state!=0}" role="document">
        <div class="modal-content" v-if="error||item==null">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('alerts.error') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    {{ __('alerts.unknown_error') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('buttons.close')}}</button>
            </div>
        </div>

        <div class="modal-content" v-else>
            <div class="modal-header">
                <h5 class="modal-title">@{{ item.title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" v-if="item.state==0">
                <div class="form-group">
                    <label for="selectGuide">{{__('label.select_category')}}:</label>
                    <select id="selectGuide" name="selectGuide" class="form-control" v-model="selectGuide">
                        <option v-bind:value="option.gde_id" v-for="option in item.body">@{{ option.gle_name }}</option>
                    </select>
                </div>
            </div>
            <div class="modal-body" v-else>
                @{{ item.body.step_desc }}
                <div class="row">
                    <div v-for="(choice,index) in item.body.choices" class="col-md-6">
                        <div class="modal-item">
                            <p class="modal-ad-dis" v-if="choice.gce_description">@{{ choice.gce_description }}</p>
                            <div class="modal-ad-dis" v-bind:class="{'row':choice.gce_cons!==null && choice.gce_cons.length > 0 && choice.gce_pros!==null && choice.gce_pros.length > 0}">
                                <div v-bind:class="{'col-md-6':choice.gce_cons!==null && choice.gce_cons.length > 0}" v-if="choice.gce_pros!==null && choice.gce_pros.length > 0">
                                    <h5>{{__('titles.pros')}}:</h5>
                                    <ul class="list-pros">
                                        <li v-for="pro in choice.gce_pros">@{{ pro.item }}</li>
                                    </ul>
                                </div>
                                <div v-bind:class="{'col-md-6':choice.gce_pros!==null && choice.gce_pros.length > 0}" v-if="choice.gce_cons!==null && choice.gce_cons.length > 0">
                                    <h5>{{__('titles.cons')}}:</h5>
                                    <ul class="list-cons">
                                        <li v-for="con in choice.gce_cons">@{{ con.item }}</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="text-center">
                                <img v-if="choice.image!==null" v-bind:src="'/storage/'+choice.image" alt="">
                                <div class="custom-control custom-radio" v-if="item.body.step_choice==0">
                                    <input v-model="item.choice_model" v-bind:value="choice.gse_id" type="radio" name="choice" class="custom-control-input" v-bind:id="'choice'+index">
                                    <label class="custom-control-label" v-bind:for="'choice'+index">@{{ choice.gce_title }}</label>
                                </div>
                                <div class="custom-control custom-checkbox" v-else-if="item.body.step_choice==1">
                                    <input v-model="choice.model" v-bind:value="choice.gse_id" type="checkbox" name="choice" class="custom-control-input" v-bind:id="'choice'+index">
                                    <label class="custom-control-label" v-bind:for="'choice'+index">@{{ choice.gce_title }}</label>
                                </div>
                                <div v-else>
                                    <h5>@{{ choice.gce_title}}</h5>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label v-bind:for="'choicemin'+index">{{__('label.from')}}:</label>
                                            <input v-model="choice.gse_min" v-bind:id="'choicemin'+index" type="text" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-6 col-md-12">
                                            <label v-bind:for="'choicemax'+index">{{__('label.to')}}:</label>
                                            <input v-model="choice.gse_max" v-bind:id="'choicemax'+index" type="text" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" v-if="item.state==0">{{__('buttons.close')}}</button>
                <button type="button" class="btn btn-secondary" :disabled="loading" @click="back()" v-if="item.state==1&&(item.start==0||list==1)">{{__('buttons.back')}}</button>
                <button type="button" class="btn btn-primary" :disabled="loading" @click="next()" v-if="item.state==1">{{__('buttons.accept')}}</button>
                <button type="button" class="btn btn-primary" :disabled="loading" @click="acceptCategory()" v-if="item.state==0">{{__('buttons.accept')}}</button>
            </div>
        </div>
    </div>
</div>
<script>
    var guide = new Vue({
        el: '#guide',
        data() {
            return {
                item: null,
                loading: true,
                list: 0,
                error: false,
                selectGuide: null,
                itemList: null,
                paramsHistory: [],
                itemHistory: [],
                params: [],
                urlStepByChoice: "{{action('GuideController@getStepByChoice', '')}}",
                urlStepByGuide: "{{action('GuideController@getStepByGuide', '')}}",
                urlValuesByPrrIdAndMinMax: "{{action('ParameterValueLanguageController@getValuesByPrrIdAndMinMax', ['prrId' => '', 'min' => '', 'max' => ''])}}"
            }
        },
        mounted() {
            axios
                .get("{{action('GuideController@getGuideByPage', strcmp(Request::path(),'/')==0?"notfound":Request::path())}}")
                .then(response => {
                    this.item = response.data;
                    this.list = response.data.list === null ? 0 : response.data.list;
                    if (this.item.state == 0 && this.item.body !== null && this.item.body.length > 0) {
                        this.selectGuide = this.item.body[0].gde_id;
                        this.itemList = this.item;
                    }
                })
                .catch(() => {
                    this.error = true
                })
                .finally(() =>
                    this.loading = false
                )
        },
        methods: {
            back() {
                if (this.itemHistory.length == 0 && this.itemList !== null) {
                    this.item = this.itemList;
                    this.paramsHistory = [];
                    this.params = [];
                } else {
                    this.item = this.itemHistory[this.itemHistory.length-1];
                    this.itemHistory.pop();
                    this.paramsHistory.pop();
                    this.params = this.paramsHistory.length > 0 ? Object.assign([], this.paramsHistory[this.paramsHistory.length-1]) : [];
                }
            },
            next() {
                this.loading = true;
                let index = -1;
                let prrId = this.item.body.prr_id;
                this.params.forEach(function(x, idx) {
                    if (x.prr_id === prrId) {
                        index = idx;
                    }
                });
                if (index == -1) {
                    if (this.item.body.step_choice == 0) {
                        this.params.push({
                            prr_id: this.item.body.prr_id,
                            pve_ids: this.item.body.choices.filter(x => x.gse_id === this.item.choice_model)[0].pve_ids
                        });
                        this.loading = false;
                    } else if (this.item.body.step_choice == 1) {
                        this.item.body.choices.forEach((value, key) => {
                            index = -1;
                            this.params.forEach(function(x, idx) {
                               if (x.prr_id === prrId) {
                                   index = idx;
                               }
                            });
                            if (value.model) {
                                if (index == -1) {
                                    this.params.push({
                                        prr_id: prrId,
                                        pve_ids: value.pve_ids
                                    });
                                } else {
                                    this.params[index].pve_ids = this.params[index].pve_ids.concat(value.pve_ids);
                                }
                            }
                        });
                        this.loading = false;
                    } else {
                        this.item.body.choices.forEach((value, key) => {
                            this.$http.get(this.urlValuesByPrrIdAndMinMax+'/'+this.item.body.prr_id+'/'+value.gse_min+'/'+value.gse_max).then(function(response) {
                                index = -1;
                                this.params.forEach(function(x, idx) {
                                    if (x.prr_id === prrId) {
                                        index = idx;
                                    }
                                });
                                if (index == -1) {
                                    this.params.push({
                                        prr_id: prrId,
                                        pve_ids: response.body.pve_ids
                                    });
                                } else {
                                    this.params[index].pve_ids = this.params[index].pve_ids.concat(response.body.pve_ids);
                                }
                                if (key == this.item.body.choices.length-1) {
                                    this.loading = false;
                                }
                            });
                        });
                    }
                } else {
                    if (this.item.body.step_choice == 0) {
                        this.params[index].pve_ids = this.params[index].pve_ids.concat(this.item.body.choices.filter(x => x.gse_id === this.item.choice_model)[0].pve_ids);
                        this.loading = false;
                    } else if (this.item.body.step_choice == 1) {
                        this.item.body.choices.forEach((value, key) => {
                          if (value.model) {
                              this.params[index].pve_ids=this.params[index].pve_ids.concat(value.pve_ids);
                          }
                        });
                        this.loading=false;
                    } else {
                        this.item.body.choices.forEach((value, key) => {
                            this.$http.get(this.urlValuesByPrrIdAndMinMax+'/'+this.item.body.prr_id+'/'+value.gse_min+'/'+value.gse_max).then(function(response) {
                                this.params[index].pve_ids=this.params[index].pve_ids.concat(response.body.pve_ids);
                                if (key == this.item.body.choices.length-1) {
                                    this.loading = false;
                                }
                            });
                        });
                    }
                }
                this.paramsHistory.push(Object.assign([], this.params));
                this.$http.get(this.urlStepByChoice+'/'+this.item.choice_model).then(function (response) {
                    if (response.data.finished) {
                        this.finishGuide();
                    } else {
                        this.itemHistory.push(this.item);
                        this.item = response.data;
                    }
                });
            },
            finishGuide() {
                let url = "/category/" + this.item.category + "?f:";

                for (let index = 0; index < this.params.length; index++) {
                    this.params[index].pve_ids = this.params[index].pve_ids.filter((e, i, arr) => arr.indexOf(e) === i);
                    url += this.params[index].prr_id + ":" + this.params[index].pve_ids.join() + ";";
                }
                window.location = url;
            },
            acceptCategory() {
                this.$http.get(this.urlStepByGuide+'/'+this.selectGuide).then(function (response) {
                    if (response.data.error) {

                    } else {
                        this.item = response.data;
                    }
                    this.loading = false;
                });
            }
        }
    });
</script>