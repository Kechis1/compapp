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
                        {{__('pages.guides').' - '.__('pages.create')}}
                    </div>
                    <div class="card-body">
                        <ul class="list-inline">
                            @foreach(\App\Models\Language::all() as $lang)
                                <li class="list-inline-item">
                                    @if($lang->lge_id == $lang_active)
                                        <a class="btn btn-primary"
                                           href="?lang={{$lang->lge_id}}">{{$lang->lge_abbreviation}}</a>
                                    @else
                                        <a class="btn btn-outline-primary"
                                           href="?lang={{$lang->lge_id}}">{{$lang->lge_abbreviation}}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <form @submit.prevent="submitForm" id="guide_admin"
                              class="needs-validation"
                              novalidate>
                            @php
                                $cats = \App\Models\CategoryLanguage::where('lge_id', $lang_active)->orderBy('cey_id')->get();
                            @endphp

                            <input type="hidden" name="lang" value="{{ $lang_active }}">
                            <section class="mb-4">
                                <h2>{{__('titles.main_details')}}</h2>
                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label"
                                           for="gle_name">{{__('label.name')}}: <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input v-model="gle_name" type="text" name="gle_name"
                                               class="form-control"
                                               id="gle_name" required>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-2 text-right col-form-label"
                                           for="cey_id">{{__('alerts.category')}}: <span
                                                class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select @change="onChangeCategory" v-model="cey_id" id="cey_id"
                                                class="form-control" name="cey_id">
                                            <option value="0" selected>--</option>
                                            @foreach ($cats === NULL ? [] : $cats as $cat)
                                                <option value="{{ $cat->cey_id }}">{{ $cat->cle_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                    <div class="form-group row">
                                        <div class="col-sm-2"></div>
                                        <div class="col-sm-10 custom-control custom-checkbox">
                                            <input v-model="gle_active" type="checkbox" name="gle_active"
                                                   class="custom-control-input" value="1"
                                                   id="gle_active" checked>
                                            <label class="custom-control-label"
                                                   for="gle_active">{{__('label.active')}}</label>
                                        </div>
                                    </div>
                            </section>
                            <section>
                                <div class="row">
                                    <div class="col">
                                        <h2>{{__('titles.control_panel')}}</h2>
                                        <h4>{{__('titles.layers')}}</h4>
                                        <div class="control-panel">
                                            <div class="item-wrapper">
                                                <div class="item-inner" v-for="step in list">
                                                    <div v-bind:class="{selected: (selected.selectedStep !== null && step.id === selected.selectedStep.id)}"
                                                         class="item"><span @click="selectGuide(step)">@{{ '('+step.type+') '+step.title }} </span>
                                                        <button @click="addChoice(step)" v-if="step.type=='S'"
                                                                type="button"
                                                                class="btn btn-sm btn-outline-secondary">{{__('buttons.add')}}</button>
                                                    </div>
                                                    <guide-item :selected="selected" :button="'{{__('buttons.add')}}'"
                                                                :key="index" v-for="(item,index) in step.items"
                                                                :item="item"></guide-item>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <button v-on:click="addStep()" type="button"
                                                    class="btn btn-primary">{{__('buttons.add')}}</button>
                                            <button @click="deleteLayer(selected.selectedStep, selected.selectedChoice)"
                                                    type="button"
                                                    class="btn btn-danger">{{__('buttons.delete')}}</button>
                                        </div>
                                    </div>

                                    <div class="col">
                                        <h2>{{__('titles.step_options')}}</h2>
                                        <section v-if="selected.selectedStep!==null">
                                            <h4>{{__('titles.step_details')}}</h4>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gss_title">{{__('label.title')}}: <span class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <input v-model="selected.selectedStep.title" type="text"
                                                           name="gss_title"
                                                           class="form-control"
                                                           value="{{ old('gss_title') }}" id="gss_title" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label @change="onChangeParameter"
                                                       class="col-sm-2 text-right col-form-label"
                                                       for="prr_id">{{__('label.parameter')}}: <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <select @change="onChangeParameter"
                                                            v-model="selected.selectedStep.prr_id" id="prr_id"
                                                            class="form-control" name="prr_id">
                                                        <option value="0" selected>--</option>
                                                        <option v-bind:value="prr.prr_id" v-for="prr in parameters">
                                                            @{{ prr.pls_name }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gss_description">{{__('inputs.description')}}:</label>
                                                <div class="col-sm-10">
                                                <textarea name="gss_description"
                                                          class="form-control"
                                                          v-model="selected.selectedStep.description"
                                                          id="gss_description"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gsp_choice">{{__('label.method_of_selection')}}: <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="gsp_choice"
                                                               class="custom-control-input form-control"
                                                               v-model="selected.selectedStep.choice" value="0"
                                                               id="gsp_choice0" checked>
                                                        <label class="custom-control-label" for="gsp_choice0"
                                                        >Radiobox</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="gsp_choice"
                                                               class="custom-control-input form-control"
                                                               v-model="selected.selectedStep.choice" value="1"
                                                               id="gsp_choice1">
                                                        <label class="custom-control-label" for="gsp_choice1"
                                                        >Checkbox</label>
                                                    </div>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" name="gsp_choice"
                                                               class="custom-control-input form-control"
                                                               v-model="selected.selectedStep.choice" value="2"
                                                               id="gsp_choice2">
                                                        <label class="custom-control-label" for="gsp_choice2"
                                                        >Slider</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <section v-if="selected.selectedChoice!==null">
                                            <h4>{{__('titles.choice_details')}}</h4>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gce_title">{{__('label.title')}}: <span
                                                            class="text-danger">*</span></label>
                                                <div class="col-sm-10">
                                                    <input v-model="selected.selectedChoice.title" type="text"
                                                           name="gce_title"
                                                           class="form-control"
                                                           id="gce_title">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="pve_id">{{__('label.values')}}: </label>
                                                <div class="col-sm-10">
                                                    <select multiple v-model="selected.selectedChoice.pve_id"
                                                            id="pve_id" class="form-control" name="pve_id">
                                                        <option value="0" selected>--</option>
                                                        <option v-bind:value="pve.pve_id"
                                                                v-for="pve in selected.selectedChoice.values">@{{
                                                            pve.pvs_value }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gse_min">Min:</label>
                                                <div class="col-sm-10">
                                                    <input v-model="selected.selectedChoice.min" type="text"
                                                           name="gse_min" class="form-control"
                                                           value="{{ old('gse_min') }}" id="gse_min">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gse_max">Max: </label>
                                                <div class="col-sm-10">
                                                    <input v-model="selected.selectedChoice.max" type="text"
                                                           name="gse_max" class="form-control"
                                                           value="{{ old('gse_max') }}" id="gse_max">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gce_description">{{__('inputs.description')}}:</label>
                                                <div class="col-sm-10">
                                                <textarea
                                                        v-model="selected.selectedChoice.description"
                                                        name="gce_description" class="form-control"
                                                        id="gce_description"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <div class="custom-file">
                                                        <label class="custom-file-label"
                                                               for="image">{{__('alerts.image')}}</label>

                                                        <input v-on:change="onFileChange" type="file" name="iae_image"
                                                               class="custom-file-input"
                                                               id="image">
                                                    </div>

                                                    <div class="guide-img">
                                                        <img alt="" :src="selected.selectedChoice.image">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gce_pros">{{__('inputs.pros')}}: </label>
                                                <div class="col-sm-10">
                                                    <textarea v-model="selected.selectedChoice.advantages"
                                                              name="gce_pros" class="form-control"
                                                              id="gce_pros"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 text-right col-form-label"
                                                       for="gce_cons">{{__('inputs.cons')}}:</label>
                                                <div class="col-sm-10">
                                                    <textarea v-model="selected.selectedChoice.disadvantages"
                                                              name="gce_cons" class="form-control"
                                                              id="gce_cons"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-2"></div>
                                                <div class="col-sm-10">
                                                    <div class="custom-control custom-checkbox">

                                                        <input v-model="selected.selectedChoice.default" type="checkbox"
                                                               name="gse_default"
                                                               class="custom-control-input" value="1" id="gse_default"
                                                               checked>
                                                        <label class="custom-control-label"
                                                               for="gse_default">{{__('label.default_value')}}</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                        <div v-if="successMessage" class="alert alert-success mt-3">
                                            {{__('alerts.created', ['object' => __('alerts.guide'), 'created' => __('alerts.successfully_created')])}}
                                        </div>
                                        <div v-if="errors" class="alert alert-danger mt-3">
                                            <span v-if="!errorMessage">{{__('alerts.unknown_error')}}</span>
                                            <span v-else>@{{ errorMessage }}</span>
                                        </div>

                                        <div v-if="errors" class="alert alert-danger mt-3">
                                            <ul>
                                                <li v-if="errors && errors.choice">
                                                    @{{ errors.choice[0] }}
                                                </li>
                                                <li v-if="errors && errors.gss_title">
                                                    @{{ errors.gss_title[0] }}
                                                </li>
                                                <li v-if="errors && errors.gle_name">
                                                    @{{ errors.gle_name[0] }}
                                                </li>
                                                <li v-if="errors && errors.cey_id">
                                                    @{{ errors.cey_id[0] }}
                                                </li>
                                                <li v-if="errors && errors.prr_id">
                                                    @{{ errors.prr_id[0] }}
                                                </li>
                                                <li v-if="errors && errors.gce_title">
                                                    @{{ errors.gce_title[0] }}
                                                </li>
                                                <li v-if="errors && errors.pve_id">
                                                    @{{ errors.pve_id[0] }}
                                                </li>
                                                <li v-if="errors && errors.min">
                                                    @{{ errors.min[0] }}
                                                </li>
                                                <li v-if="errors && errors.max">
                                                    @{{ errors.max[0] }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </section>
                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">{{__('buttons.add')}}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection