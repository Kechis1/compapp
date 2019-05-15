/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.randomstring = require('randomstring');
window.Vue = require('vue');
window.VueCookie = require('vue-cookies');
window.VueRouter = require('vue-router');
window.VueResource = require('vue-resource');
window.axios = require('axios');


Vue.component('guide-item', {
    template: '' +
        '<div class="item-wrapper">' +
        '<div class="item-inner"><div v-bind:class="{selected: (selected.selectedStep !== null && item.id === selected.selectedStep.id) || (selected.selectedChoice !== null && item.id === selected.selectedChoice.id)}" class="item"><span @click="selectGuide(item)">({{item.type}}) {{item.title}}</span> <button @click="addChoice(item)" v-if="item.type==\'S\'" type="button" class="btn btn-sm btn-outline-secondary">{{button}}</button></div>' +
        '<guide-item :button="button" :key="index" :selected="selected" v-for="(item,index) in item.items" :item="item"></guide-item>' +
        '</div>' +
        '<div class="item-wrapper" v-if="item.step">' +
        '<div class="item-inner"><div class="item" v-bind:class="{selected: selected.selectedStep !== null && item.step.id === selected.selectedStep.id}"><span @click="selectGuide(item.step)">({{item.step.type}}) {{item.step.title}}</span> <button @click="addChoice(item.step)" v-if="item.step.type==\'S\'" type="button" class="btn btn-sm btn-outline-secondary">{{button}}</button></div>' +
        '<guide-item :button="button" :key="index" v-for="(item,index) in item.step.items" :selected="selected" :item="item"></guide-item>' +
        '</div></div>' +
        '</div>',
    props: ['item', 'selected', 'button'],
    data() {
        return {
            guideId: null,
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
            newStep: {
                "type": "S",
                "id": null,
                "title": "Step",
                "prr_id": 0,
                "description": null,
                "choice": 0,
                "items": []
            },
        }
    },
    methods: {
        addChoice: function (step) {
            this.generateStrings();
            this.newChoice.id = this.guideId;
            step.items.push(Object.assign({}, this.newChoice));
            this.newStep.items = [];
        },
        selectGuide: function (item) {
            if (item.type === "V") {
                this.selected.selectedChoice = this.selected.selectedChoice !== null && this.selected.selectedChoice.id === item.id ? null : item;
            } else {
                this.selected.selectedStep = this.selected.selectedStep !== null && this.selected.selectedStep.id === item.id ? null : item;
            }
        },
        generateStrings: function () {
            this.guideId = randomstring.generate(20);
        }
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


