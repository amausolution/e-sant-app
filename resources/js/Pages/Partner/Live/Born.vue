<template>
    <HeaderTitle :title="title" />
    <div class="content container-fluid">
        <page-header>
            <div class="row">
                <div class="col">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('partner.home')">{{__('Dashboard')}}</Link>
                        </li>
                        <li class="breadcrumb-item active">{{title}}</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto space-x-6">
                    <Link as="button" type="button" :href="route('live.index')" class="bg-sky-400 shadow-md hover:bg-sky-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-arrow-left-long"></i>
                        {{__('Go Back')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white p-2 p-sm-2 p-lg-4 p-md-4 rounded-md shadow-md">

            <form @submit.prevent="submitBorn">
                <Transition duration="550" name="nested">
                    <div v-if="formStep==1">
                        <div class="border-b mb-4 ">
                            <h4>{{__('Info Father')}}</h4>
                           <div class="">
                               <label for="f">{{__('Father is Godparent')}}</label>
                               <input id="f" type="radio" class="w-6 h-6 ml-2" v-model="form.is_godfather" value="father">
                           </div>
                        </div>
                        <div  class="row">
                            <div class="form-group col-12 col-sm-12 col-md-12 col-lg-3 flex flex-col">
                                <div class="d-flex flex-col with-switch">
                                    <span class="form-label">{{ __('Father Have Matricule')}}</span>
                                    <div class="onoffswitch" style="margin-left: 2px">
                                        <input v-model="form.fatherMat" type="checkbox"
                                                :true-value="true"
                                                :false-value="false"
                                                @change="handleSwitchF($event)"
                                                class="onoffswitch-checkbox" id="switch_annual">
                                        <label class="onoffswitch-label" for="switch_annual">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-12 col-sm-6 col-md-6 col-lg-3" v-if="form.fatherMat">
                                <label for="">{{__('Father Matricule')}}</label>
                                <input type="text" class="form-control" v-model="form.father_matricule"
                                       name="father_matricule"
                                       required
                                       pattern=".*\S+.*"
                                >
                                <span class="help-block text-sm" v-if="form.errors.father_matricule">{{__(form.errors.father_matricule)}}</span>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3 order-3" v-if="form.is_godfather==='father'">
                                <label for="cin">{{__('Father Piece')}}</label>
                                <input id="cin" type="text" class="form-control" v-model="form.cin" >
                                <span class="help-block text-sm" v-if="form.errors.cin">{{__(form.errors.cin)}}</span>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3" v-if="!form.fatherMat">
                                <label for="">{{__('Father First Name')}}</label>
                                <input type="text" class="form-control" v-model="form.father_fname" >
                                <span class="help-block text-sm" v-if="form.errors.father_fname">{{__(form.errors.father_fname)}}</span>
                            </div>
                            <div class="form-group col-12 col-md-6 col-lg-3" v-if="!form.fatherMat">
                                <label for="">{{__('Father Last Name')}}</label>
                                <input type="text" class="form-control" v-model="form.father_lname" >
                                <span class="help-block text-sm" v-if="form.errors.father_lname">{{__(form.errors.father_lname)}}</span>
                            </div>
                        </div>
                    </div>
                </Transition>
                <Transition duration="550" name="nested">
                <div v-if="formStep==2">
                    <div class="border-b mb-4 ">
                        <h4>{{__('Info Mather')}}</h4>
                        <div class="">
                        <label for="m">{{__('Mather is Godparent')}}</label>
                        <input id="m" type="radio" class="w-6 h-6 ml-2" v-model="form.is_godfather" value="mather">
                        </div>
                    </div>
                    <div  class="row">
                        <div class="form-group col-12 col-sm-12 col-md-12 col-lg-3 flex flex-col">
                            <div class="d-flex flex-col with-switch">
                                <span class="form-label">{{ __('Mather Have Matricule')}}</span>
                                <div class="onoffswitch" style="margin-left: 2px">
                                    <input v-model="form.matherMat" type="checkbox"
                                           :true-value="true"
                                           :false-value="false"
                                           @change="handleSwitchF($event)"
                                           class="onoffswitch-checkbox" id="switch_mather">
                                    <label class="onoffswitch-label" for="switch_mather">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-3" v-if="form.matherMat">
                            <label for="">{{__('Mather Matricule')}}</label>
                            <input type="text" class="form-control" v-model="form.mather_matricule">
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-3 order-3" v-if="form.is_godfather==='mather'">
                            <label for="cinm">{{__('Mather Piece')}}</label>
                            <input id="cinm" type="text" class="form-control" v-model="form.cin" >
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-3" v-if="!form.matherMat">
                            <label for="">{{__('Mather First Name')}}</label>
                            <input type="text" class="form-control" v-model="form.mather_fname">
                        </div>
                        <div class="form-group col-12 col-md-6 col-lg-3" v-if="!form.matherMat">
                            <label for="">{{__('Mather Last Name')}}</label>
                            <input type="text" class="form-control" v-model="form.mather_lname">
                        </div>
                </div>
                </div>
                </Transition>
                <Transition duration="550" name="nested">
                     <div v-if="formStep==3">
                     <div class="row">
                    <div class="form-group col-6">
                        <label for="">{{__('Godfather Phone')}}</label>
                        <input type="text" class="form-control" v-model="form.godfather_phone">
                    </div>
                    <div class="form-group col-6 col-sm-6 col-md-3">
                        <label for="">{{__('Dob')}}</label>
                        <v-date-picker v-model="date" locale="fr">
                            <template v-slot="{ inputValue, inputEvents }">
                                <input id="dt"
                                       class="bg-white border px-2 py-1 rounded form-control"
                                       :value="inputValue"
                                       v-on="inputEvents"
                                />
                            </template>
                        </v-date-picker>
                    </div>
                    <div class="form-group col-6 col-sm-6 col-md-3">
                        <div class="flex ">
                            <label class="text-gray-600 font-medium">{{__('Time of Born')}}</label>
                        </div>
                        <v-date-picker mode="time" v-model="date" :timezone="timezone"/>
                    </div>
                    <div class="form-group col-6">
                        <label for="">{{__('Type Childbirth')}}</label>
                        <multiselect v-model="form.type_childbirth" :options="type_childbirth"
                                     :select-label="__('Press enter to select')"
                                     :custom-label="nameWithLang" :placeholder="__('Select one')"
                                     label="title" track-by="id">
                        </multiselect>

                    </div>
                    <div class="form-group col-6">
                        <label for="">{{__('Number Baby')}}</label>
                        <input type="text" class="form-control" v-model="form.nbr_baby">
                    </div>
                    <div class="form-group col-6">
                        <label for="">{{__('Baby health status')}}</label>
                        <multiselect v-model="form.state_baby" :options="state_baby"
                                     track-by="id" label="title"
                                     :select-label="__('Press enter to select')"
                                     :show-labels="true" :placeholder="__('Baby health status')">
                        </multiselect>
                    </div>
                    <div class="form-group col-6">
                        <label for="">{{__('Gender')}}</label>
                        <multiselect v-model="form.gender" :options="gender"
                                     :custom-label="nameWithLang"
                                     track-by="id" label="title"
                                     :select-label="__('Press enter to select\'')"
                                     :show-labels="true" :placeholder="__('Select Gender')">
                        </multiselect>

                    </div>
                    <div class="form-group col-12">
                        <label for="">{{__('Address')}}</label>
                        <textarea name="" id="" cols="3" rows="3" class="form-control" v-model="form.address"></textarea>
                    </div>
                    <div class="form-group col-12">
                        <label for="">{{__('Note')}}</label>
                        <div ref="editorWrapper">
                            <ckeditor
                                v-model="editorData"
                                :editor="editor"
                                :config="editorConfig"
                                :disabled="loading"
                                @ready="onReady"
                            ></ckeditor>
                        </div>
                    </div>
                </div>
                </div>
                </Transition>
                <div class="flex justify-end space-x-4 items-center">
                    <div class="flex justify-start">
                        <ul>
                            <li v-for="error in error1">
                                <span class="help-block text-xs">{{__(error)}}</span>
                            </li>
                        </ul>
                    </div>
                    <loading-button :loading="form.processing" v-if="formStep==2 || formStep==3" @click="previous"
                            class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300"
                            type="button">
                        <i class="fa-duotone fa-arrow-left-long mr-2"></i>
                        {{__('Previous')}}
                    </loading-button>
                    <loading-button :loading="form.processing" v-if="formStep==1 || formStep==2" @click="step"
                             class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300"
                             type="button">
                         {{__('Next')}}
                         <i class="fa-duotone fa-arrow-right-long ml-2"></i>
                    </loading-button>
                    <loading-button :loading="form.processing" v-if="formStep==3"
                                    class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300"
                                    type="submit">
                        {{__('Submit form')}}
                    </loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import DecoupledEditor from "@ckeditor/ckeditor5-build-decoupled-document";
    import VCalendar from "v-calendar";
    import LoadingButton from "@/Shared/LoadingButton";
    import { ref } from "vue";
    import { Switch, SwitchGroup, SwitchLabel } from '@headlessui/vue';
    import {Multiselect} from "vue-multiselect";
    import moment from "moment";
    export default {
        name: "Born",
        created: function () {
            this.moment = moment;
        },
        components: {LoadingButton, PageHeader, HeaderTitle, Link, VCalendar, Switch, SwitchLabel, SwitchGroup, Multiselect},
        props: {
            title: String,
            gender: Array,
            type_childbirth: Array,
            state_baby: Array,
        },
        data(){
            return {
                error1: [],
                fatherMat: ref(false),
                matherMat: ref(false),
                formStep: ref(1),
                editor: DecoupledEditor,
                editorData: '',
                editorConfig: {
                    // Run the editor with the German UI.
                    language: 'fr'
                },
                date: new Date(),
                time: new Date(),
                timezone: 'UTC',
                form :  useForm({
                    'father_fname':null,
                    'father_lname':null,
                    'father_matricule':null,
                    'mather_matricule':null,
                    'mather_fname':null,
                    'mather_lname':null,
                    'dob':null,
                    'godfather_phone':null,
                    'address':null,
                    'type_childbirth':null,
                    'matricule_doc':null,
                    'state_baby':null,
                    'gender':null,
                    'nbr_baby':null,
                    'note':null,
                    'cin':null,
                    'is_godfather':'',
                    'fatherMat': ref(false),
                    'matherMat': ref(false),
                })
            }

        },

        methods:{
            submitBorn: function () {
                this.form.transform((data) => ({
                    ...data,
                    note: this.editorData,
                    dob: this.date,
                    gender: this.form.gender.id,
                    type_childbirth:this.form.type_childbirth.id,
                    state_baby:this.form.state_baby.id
                })).post(route('live.born.store'),{})
            },
            onReady (editor) {
                this.$refs.editorWrapper.prepend(editor.ui.view.toolbar.element)
            },
            handleSwitchF (event) {
             // console.log(event.target.checked)
                if (event.target.checked){
                    this.form.father_lname=''
                    this.form.father_fname=''
                    this.error1 = []
                }else{
                    this.form.father_matricule= ''
                    this.error1 = []
                }
            },
            handleSwitchM (event) {
                // console.log(event.target.checked)
                if (event.target.checked){
                    this.form.mather_lname=''
                    this.form.mather_fname=''
                    this.error1 = []
                }else{
                    this.form.mather_matricule= ''
                    this.error1 = []
                }
            },
            step: function () {
                // step 1
                if (this.formStep == 1){
                    if (!this.fatherMat){
                        if (this.form.father_lname && this.form.father_fname) {
                            this.formStep ++
                            this.form.clearErrors()
                            return true;
                        }
                    }else {
                        if (this.form.father_matricule) {
                            this.formStep ++
                            this.form.clearErrors()
                            return true;
                        }
                    }

                    if (this.fatherMat && !this.form.father_matricule) {
                       // this.error1.push('Matricule required.');
                        this.form.setError('father_matricule', 'Matricule of father required.');
                    }
                    if (!this.fatherMat && !this.form.father_lname) {
                        this.form.setError('father_lname', 'Last name father required.');
                    }
                    if (!this.fatherMat && !this.form.father_fname) {
                        this.form.setError('father_fname', 'First name father required.');
                    }
                }

                //step 2
                if (this.formStep == 2){
                    if (!this.fatherMat){
                        if (this.form.mather_lname && this.form.mather_fname) {
                            this.formStep ++
                            this.form.clearErrors()
                            return true;
                        }
                    }else {
                        if (this.form.mather_matricule) {
                            this.formStep ++
                            this.form.clearErrors()
                            return true;
                        }
                    }


                    if (this.matherMat && !this.form.mather_matricule) {
                        this.form.setError('mather_matricule', 'Matricule of mather required.');
                    }
                    if (!this.matherMat && !this.form.mather_lname) {
                        this.form.setError('mather_lname', 'Last name mather required.');
                    }
                    if (!this.matherMat && !this.form.mather_fname) {
                        this.form.setError('mather_fname', 'First name mather required.');
                    }
                }
                // step 3


            },

            previous: function () {
                this.formStep --
            }
        },

    }
</script>

<style scoped>
    .ck-rounded-corners .ck.ck-editor__editable:not(.ck-editor__nested-editable), .ck.ck-editor__editable.ck-rounded-corners:not(.ck-editor__nested-editable) {
        border-bottom-right-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        height: 300px;
        /*border: solid 1px #A3A3A3 !important;*/
        border: 1px solid var(--ck-color-toolbar-border);
    }

    .slide-fade-enter-active {
        transition: all 0.1s ease-out;
    }

    .slide-fade-leave-active {
        transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateX(20px);
        opacity: 0;
    }

    .onoffswitch-inner::before {
        background-color: #134E4A;
        color: #fff;
        content: "YES";
        padding-left: 14px;
    }
    .onoffswitch-inner::after {
        background-color: #94A3B8;
        color: #fff;
        content: "NO";
        padding-left: 14px;
    }
</style>
<style>
    .outer, .inner {
        background: #eee;
        padding: 30px;
        min-height: 100px;
    }

    .inner {
        background: #ccc;
    }

    .nested-enter-active, .nested-leave-active {
        transition: all 0.05s ease-in-out;
    }
    /* delay leave of parent element */
    .nested-leave-active {
        transition-delay: 0.05s;
    }

    .nested-enter-from,
    .nested-leave-to {
        transform: translateY(30px);
        opacity: 0;
    }

    /* we can also transition nested elements using nested selectors */
    .nested-enter-active .inner,
    .nested-leave-active .inner {
        transition: all 0.05s ease-in-out;
    }
    /* delay enter of nested element */
    .nested-enter-active .inner {
        transition-delay: 0.05s;
    }

    .nested-enter-from .inner,
    .nested-leave-to .inner {
        /* transform: translateX(30px);

            Hack around a Chrome 96 bug in handling nested opacity transitions.
          This is not needed in other browsers or Chrome 99+ where the bug
          has been fixed.

        opacity: 0.001;*/
    }
</style>
