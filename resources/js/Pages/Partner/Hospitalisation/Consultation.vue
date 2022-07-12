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
                <div class="col-auto float-right ml-auto">
                    <Link :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})" class="btn add-btn"><i class="la la-long-arrow-left"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>
        <div >
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{__('Do Consultation For Patient')}} <span class="text-muted ml-2">({{ hospitalisation.patient}})</span>    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm">
                            <form class="needs-validation" novalidate="" @submit.prevent="handleConsultation">
                                <div class="form-row ">
                                    <div class="col-12 col-md-4 col-lg-4">
                                        <div class="col-12 mb-3">
                                            <label for="sugar">{{__('Sugar')}}</label>
                                            <input v-model="form.sugar" type="text" class="form-control" id="sugar">
                                            <div class="valid-feedback" v-if="form.errors.sugar"> {{form.errors.sugar}}</div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="tension">{{__('Tension')}}</label>
                                            <input v-model="form.tension" type="text" class="form-control" id="tension">
                                            <div class="valid-feedback" v-if="form.errors.tension">{{form.errors.tension}} </div>
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label for="temperature">{{__('Temperature')}}</label>
                                            <input v-model="form.temperature" type="text" class="form-control" id="temperature">
                                            <div class="invalid-feedback" v-if="form.errors.temperature">{{form.errors.temperature}} </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-8 col-lg-8">
                                        <div class="form-group">
                                            <label for="">{{__('Diagnostic')}}</label>
                                            <ckeditor :editor="editor" v-model="form.diagnostic" :config="editorConfig"></ckeditor>
                                        </div>
                                        <div class="form-group">
                                            <label for="result">{{ __('Result')}}</label>
                                            <textarea class="form-control" name="result" id="result" cols="30" rows="4" v-model="form.result"></textarea>
                                        </div>
                                        <div class="flex flex-col space-y-3 mb-4" v-for="(analyse,a) in form.analyses" :key="a">
                                            <label class="typo__label">{{__('Category Analysis')}}</label>
                                            <vue-multiselect v-model="analyse.analyse_id" :select-label="__('type enter to select')" :options="category_analyses"  v-bind:placeholder="__('Select one')" label="title" track-by="id" tag-placeholder="Add this as new tag">
                                                <template>
                                                    <span slot="noResult">{{__('Consider changing the search query.')}}</span>
                                                </template>
                                            </vue-multiselect>
                                            <div class="flex flex-row space-x-4 justify-start items-baseline">
                                                <span>{{__('Emergency:')}}</span>
                                                <label for="yes" class="flex flex-row space-x-2">
                                                    <span>{{__('Yes')}}</span>
                                                    <input type="radio" v-model="analyse.emergency" id="yes" value="1" class="w-5 h-5">
                                                </label>
                                                <label for="no" class="flex flex-row space-x-2">
                                                    <span>{{ __('No')}}</span>
                                                    <input type="radio" v-model="analyse.emergency" id="no" value="0" class="w-5 h-5">
                                                </label>
                                            </div>
                                            <div>
                                                <label for="note">{{__('Note')}}</label>
                                                <textarea class="form-control" v-model="analyse.note" cols="6" id="note"></textarea>
                                            </div>
                                            <span class="flex flex-row space-x-3 justify-end">
                    <i class="fas fa-minus-circle text-danger" @click="removeAnalyse(a)" v-show="a || ( !k && form.analyses.length > 1)"></i>
                    <i class="fas fa-plus-circle text-success" @click="addAnalyse(a)" v-show="a === form.analyses.length-1"></i>
            </span>

                                        </div>

                                        <div class="flex justify-end justify-between mb-3">
                                            <button @click="add(0)" type="button" v-if="form.prescriptions.length === 0"
                                                    class="px-6 py-2 text-purple-100 bg-purple-400 rounded hover:bg-purple-500 ">
                                                <i class="fa-duotone fa-tablets mr-2"></i>
                                                <span>{{__('Add Prescription')}}</span>
                                            </button>
                                            <i class="fa-duotone fa-flask-vial"></i>
                                            <button @click="addAnalyse(0)" type="button" v-if="form.analyses.length === 0"
                                                    class="px-6 py-2 rounded bg-rose-400 hover:bg-rose-500 text-rose-100">
                                                <i class="fa-solid fa-vial-virus"></i>
                                                <span>{{__('Add Analyse')}}</span>
                                            </button>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-row border p-2 mb-1" v-for="(prescription,k) in form.prescriptions" :key="k" >
                                    <div class="col-md-1 mb-3 ">
                                        <label for="quantity">{{__('Quantity')}}</label>
                                        <input type="text" class="form-control" id="quantity" v-model="prescription.quantity" required="">
                                        <div class="help-block text-xs" v-if="form.errors.quantity">
                                            {{form.errors.quantity}}
                                        </div>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="label">{{__('Label')}}</label>
                                        <input type="text" class="form-control" v-model="prescription.label" id="label"  required="">
                                        <div class="help-block text-xs" v-if="form.errors.label">
                                            {{form.errors.label}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="dosage">{{__('Dosage')}}</label>
                                        <input type="text" class="form-control" id="dosage" v-model="prescription.dosage" >
                                        <div class="help-block text-xs" v-if="form.errors.dosage">
                                            {{form.errors.dosage}}
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="">{{__('Dosage Text')}}</label>
                                        <div class="flex flex-row space-x-4">
                                            <div>
                                                <input value="morning" name="morning" type="checkbox" class="checkbox-custom" :id="`ma+${k}`" v-model="prescription.dosageText">
                                                <label :for="`ma+${k}`" class="checkbox-custom-label px-2 py-1">
                                                    <i class="fa-solid fa-sunrise"></i>
                                                </label>
                                            </div>
                                            <div>
                                                <input value="noon" name="noon" type="checkbox" class="checkbox-custom" :id="`m+${k}`" v-model="prescription.dosageText">
                                                <label :for="`m+${k}`" class="checkbox-custom-label px-2 py-1">
                                                    <i class="fa-solid fa-moon-over-sun"></i>
                                                </label>
                                            </div>
                                            <div>
                                                <input value="evening" name="evening" type="checkbox" class="checkbox-custom" :id="`s+${k}`" v-model="prescription.dosageText" >
                                                <label :for="`s+${k}`" class="checkbox-custom-label px-2 py-1">
                                                    <i class="fa-solid fa-sunset"></i>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label for="duration">{{__('Duration')}} </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" v-model="prescription.duration" id="duration" :placeholder="__('in day')">
                                            <span class="flex flex-col space-y-2">
                                            <i class="fas fa-minus-circle text-danger" @click="remove(k)" v-show="k || ( !k && form.prescriptions.length > 1)"></i>
                                            <i class="fas fa-plus-circle text-success" @click="add(k)" v-show="k === form.prescriptions.length-1"></i>
                                        </span>
                                        </div>

                                    </div>

                                </div>
                                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">{{__('Submit form')}}</loading-button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3"
    import { useForm } from "@inertiajs/inertia-vue3"
    import Editor from '@ckeditor/ckeditor5-build-classic';
    import LoadingButton from "@/Shared/LoadingButton";
    import VueMultiselect from 'vue-multiselect'
    export default {
        name: "Consultation",
        components: {LoadingButton, PageHeader, HeaderTitle, Link, VueMultiselect},
        props: {
            hospitalisation: Object,
            title: String,
            category_analyses:Array
        },
        data(){
            return {
                editor: Editor,
                editorConfig: {
                    // Run the editor with the German UI.
                    language: 'fr',
                    height: '500px',
                    ui: {
                        width: '500px',
                        height: '300px'
                    }
                }
            }
        },
        setup(props){
            const form = useForm({
                sugar: '',
                tension: '',
                temperature:'',
                diagnostic: '',
                result:'',
                prescriptions:[],
                analyses: []
            })
            return { form }
        },
        methods:{
            add(index) {
                this.form.prescriptions.push({
                    qte: '',
                    duration: '',
                    label: '',
                    dosage: '',
                    dosageText: [],
                });
            },
            remove(index) {
                this.form.prescriptions.splice(index, 1);
            },

            addAnalyse(index) {
                this.form.analyses.push({
                    analyse_id:'',
                    emergency:'',
                    note:'',
                });
            },
            removeAnalyse(index) {
                this.form.analyses.splice(index, 1);
            },
            handleConsultation (){
                this.form.post(route('hospitalisation.PostConsultation', {id: this.hospitalisation.id}),{

                })
            }
        }
    }
</script>

<style scoped>
    .ck-editor__editable {

    }
    .ck-editor {
        margin: 1em 0;
        border: 1px solid hsla(0, 0%, 0%, 0.1);
        border-radius: 4px;
        height: 500px;
    }

    .checkbox-custom {
        opacity: 0;
        position: absolute;
    }

    .checkbox-custom-label {
        border: 2px solid #f87171;
        /*padding: 4px;*/
        cursor: pointer;
        color: #f87171;
    }

    .checkbox-custom:checked + .checkbox-custom-label {
     color: #059669;
     border: 2px solid #059669;
     /*padding: 2px 4px;*/
    }
</style>
