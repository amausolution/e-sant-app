<template>
    <HeaderTitle :title="`${ title }`" />
    <!-- Chat Main Row -->
    <div class="chat-main-row">
        <!-- Chat Main Wrapper -->
        <div class="chat-main-wrapper">
            <!-- Chats View -->
            <div class="col-lg-9 message-view task-view">
                <div class="chat-window">
                    <div class="fixed-header">
                        <div class="navbar">
                            <div class="user-details mr-auto">
                                <div class="float-left user-img">
                                    <Link class="avatar" :href="consultation.avatar" :title="`${consultation.name}`">
                                        <img :src="consultation.avatar" alt="" class="rounded-circle">
                                        <span class="status online"></span>
                                    </Link>
                                </div>
                                <div class="user-info float-left">
                                    <Link :href="route('patient.profile',{id:consultation.patient_id})" :title="`${consultation.name}`">
                                        <span>{{consultation.name}}</span>
                                        <i class="typing-text ml-2">{{statusConsultation[consultation.status]}}</i>
                                    </Link>
                                    <span class="last-seen">{{__('Patient n°')}}&nbsp; {{consultation.ticket}}</span>
                                </div>
                            </div>
                            <ul class="nav custom-menu">
                                <li class="nav-item">
                                    <a class="nav-link task-chat profile-rightbar float-right" id="task_chat" href="#task_window" @click="show = !show">
                                        <i class="fa fa-user"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a  href="javascript:void(0)" class="nav-link" v-on:click="callVoice()"><i class="fa fa-microphone"></i></a>
                                </li>
                                <li class="nav-item d-none d-lg-flex">
                                    <a  href="javascript:void(0)" class="nav-link" @click="show = !show"><i class="fa fa-user"></i></a>
                                </li>
                                <li class="nav-item dropdown dropdown-action">
                                    <a aria-expanded="false" data-toggle="dropdown" class="nav-link dropdown-toggle" href=""><i class="fa fa-cog"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="javascript:void(0)" class="dropdown-item">{{__('Delete Conversations')}}</a>
                                        <a href="javascript:void(0)" class="dropdown-item">{{__('Transfer')}}</a>
                                    </div>
                                </li>
                                <detail-patient :patient="{ consultation }"/>
                            </ul>
                        </div>
                    </div>
                    <div class="chat-contents">
                        <div class="chat-content-wrap">
                            <div class="chat-wrap-inner">
                                <div class="chat-box">
                                    <div class="chats">
                                        <div class=" ">
                                            <div class="col-sm" v-if="consultation.status !== 2">
                                                <form class="needs-validation" novalidate="" @submit.prevent="handleConsultation">
                                                    <div class="form-row ">
                                                        <div class="col-12 row">
                                                            <div class="col-4 mb-3">
                                                                <label for="sugar">{{__('Sugar')}}</label>
                                                                <input v-model="form.sugar" type="text" class="form-control" id="sugar">
                                                                <div class="valid-feedback" v-if="form.errors.sugar"> {{form.errors.sugar}}</div>
                                                            </div>
                                                            <div class="col-4 mb-3">
                                                                <label for="tension">{{__('Tension')}}</label>
                                                                <input v-model="form.tension" type="text" class="form-control" id="tension">
                                                                <div class="valid-feedback" v-if="form.errors.tension">{{form.errors.tension}} </div>
                                                            </div>
                                                            <div class="col-4 mb-3">
                                                                <label for="temperature">{{__('Temperature')}}</label>
                                                                <input v-model="form.temperature" type="text" class="form-control" id="temperature">
                                                                <div class="invalid-feedback" v-if="form.errors.temperature">{{form.errors.temperature}} </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label for="">{{__('Diagnostic')}}</label>
                                                                <ckeditor :editor="editor" v-model="form.diagnostic" :config="editorConfig"></ckeditor>
                                                                <span class="help-block text-sm" v-if="form.errors.diagnostic">{{form.errors.diagnostic}}</span>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="result">{{ __('Result')}}</label>
                                                                <textarea class="form-control" name="result" id="result" cols="30" rows="4" v-model="form.result"></textarea>
                                                                <span class="help-block text-sm" v-if="form.errors.result">{{form.errors.result}}</span>
                                                            </div>
                                                            <div class="flex flex-col space-y-3 mb-4 bg-white rounded-md shadow-sm p-3 relative" v-for="(analyse,a) in form.analyses" :key="a">
                                                                <i class="fas fa-minus-circle fa-2x text-danger absolute right-2 top-0" @click="removeAnalyse(a)" v-show="a || ( !k && form.analyses.length > 1)"></i>
                                                                <label class="typo__label">{{__('Category Analysis')}}</label>
                                                                <vue-multiselect v-model="analyse.analyse_id" :select-label="__('type enter to select')" :options="category_analyses"  v-bind:placeholder="__('Select one')" label="title" track-by="id" tag-placeholder="Add this as new tag">
                                                                    <template>
                                                                        <span slot="noResult">{{__('Consider changing the search query.')}}</span>
                                                                    </template>
                                                                </vue-multiselect>
                                                                <div>
                                                                    <label for="note">{{__('Note')}}</label>
                                                                    <textarea class="form-control" v-model="analyse.note" cols="6" id="note"></textarea>
                                                                </div>
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
                                                                <span class="flex flex-row space-x-3 justify-end">
<!--                                                                        <i class="fas fa-minus-circle fa-2x text-danger" @click="removeAnalyse(a)" v-show="a || ( !k && form.analyses.length > 1)"></i>-->
                                                                        <i class="fas fa-plus-circle fa-2x text-success" @click="addAnalyse(a)" v-show="a === form.analyses.length-1"></i>
                                                                </span>
                                                            </div>
                                                            <div class="form-row border p-2 mb-1 bg-white shadow-sm rounded-md" v-for="(prescription,k) in form.prescriptions" :key="k" >
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
                                                                                <i class="fa-duotone fa-sunrise"></i>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <input value="noon" name="noon" type="checkbox" class="checkbox-custom" :id="`m+${k}`" v-model="prescription.dosageText">
                                                                            <label :for="`m+${k}`" class="checkbox-custom-label px-2 py-1">
                                                                                <i class="fa-duotone fa-sun"></i>
                                                                            </label>
                                                                        </div>
                                                                        <div>
                                                                            <input value="evening" name="evening" type="checkbox" class="checkbox-custom" :id="`s+${k}`" v-model="prescription.dosageText" >
                                                                            <label :for="`s+${k}`" class="checkbox-custom-label px-2 py-1">
                                                                                <i class="fa-duotone fa-sunset"></i>
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-2 mb-3">
                                                                    <label for="duration">{{__('Duration')}} </label>
                                                                    <div class="input-group flex flex-row space-x-2">
                                                                        <input type="text" class="form-control" v-model="prescription.duration" id="duration" :placeholder="__('in day')">
                                                                        <span class="flex flex-col text-lg">
                                                                            <i class="fas fa-minus-circle text-danger fa-x" @click="remove(k)" v-show="k || ( !k && form.prescriptions.length > 1)"></i>
                                                                            <i class="fas fa-plus-circle text-success fa-x" @click="add(k)" v-show="k === form.prescriptions.length-1"></i>
                                                                        </span>
                                                                    </div>

                                                                </div>

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
                                                    <input type="hidden" v-model="form.idC" class="bg-opacity-0 hidden" >
                                                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">{{__('Submit form')}}</loading-button>
                                                </form>
                                            </div>
                                            <div class="flex flex-row justify-evenly">
                                                <form @submit.prevent="hospitalized"  v-if="consultation.diagnostic !==null && consultation.hospitalisation.length == 0">
                                                    <button class="px-6 py-2 shadow-md rounded text-white bg-rose-500 text-base space-x-2
                                                      font-semibold hover:bg-rose-700 justify-center flex flex-row items-baseline"
                                                    >
                                                        <i class="fa-duotone fa-bed text-xl font-semibold"></i>
                                                        <span>{{ __('Hospitalized This Patient')}}</span>
                                                    </button>
                                                </form>
                                                <div v-else>

                                                        <span v-if="consultation.hospitalisation.status==0" class="px-4 space-x-2 py-2 shadow-md rounded text-white
                                                              bg-teal-500 text-base
                                                              font-semibold hover:bg-teal-700 justify-center flex flex-row items-baseline" >
                                                               <i class="fa-duotone fa-bed-empty text-xl font-semibold"></i>
                                                             <span> {{ __('Patient Hospitalization Asked ')}}</span>
                                                        </span>


                                                        <span v-else-if="consultation.hospitalisation.status==1" class="px-4 text-sm py-2 shadow-md rounded text-white bg-rose-500 text-base space-x-2
                                                              font-semibold hover:bg-rose-700 justify-center flex flex-row items-baseline space-x-2"
                                                        >
                                                            <i class="fa-duotone fa-bed-pulse text-xl font-semibold"></i>
                                                            <span>{{ __('Patient Hospitalized ')}}</span>
                                                        </span>
                                                    <!--    <span v-else class="px-2 md:px-2 text-sm py-2 shadow-md rounded text-white bg-rose-500 text-base space-x-2
                                                              font-semibold hover:bg-rose-700 justify-center flex flex-row items-center"
                                                        > {{ __('Patient Hospitalized Out')}}
                                                        </span>-->
                                                </div>
                                            </div>

                                        </div>
                                        <div v-if="consultation.status == 2">
                                            <div class="mt-3">
                                                <div class="card flex-fill dash-statistics ">
                                                    <div class="card-body">
                                                        <h4 class="card-title">{{__('First Diagnostics')}}</h4>
                                                        <div class="stats-list grid grid-cols-3 gap-2">
                                                            <div class="stats-info" v-for="(fdiagnostic, val) in consultation.first_diag" :key="val">
                                                                <p><span>{{ val }}</span>{{fdiagnostic}} <strong>4 <small>/65</small></strong></p>
                                                                <div class="progress">
                                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card flex-fill dash-statistics ">
                                                <div class="card-body">
                                                    <div class="task-wrapper mb-4 mt-3" style="padding: 0">
                                                <div class="task-list-container">
                                                    <div class="task-list-body">
                                                        <h4>{{__('Diagnostics')}}</h4>
                                                       <div v-html="consultation.diagnostic"></div>
                                                    </div>
                                                </div>
                                            </div>
                                                </div>
                                            </div>
                                            <div class="card flex-fill dash-statistics ">
                                                <div class="card-body">
                                                    <h4>{{__('Prescriptions')}}</h4>
                                                    <div class="bg-white rounded-md  overflow-x-auto">
                                                    <table class="w-full whitespace-nowrap text-base">
                                                        <tr class="text-left font-semibold">
                                                            <th class="pb-2 pt-3 px-6 w-px">{{__('Qte')}}</th>
                                                            <th class="pb-2 pt-3 px-6">{{__('Label')}}</th>
                                                            <th class="pb-2 pt-3 px-6 w-px">{{__('Dosage')}}</th>
                                                            <th class="pb-2 pt-3 px-6">{{__('Dosage Text')}}</th>
                                                            <th class="pb-2 pt-3 px-6 w-px">{{__('Duration')}}</th>
                                                        </tr>
                                                        <tr v-for="prescrit in consultation.prescriptions" :key="prescrit.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                                            <td class="border-t px-6 py-2 w-px">
                                                                {{ prescrit.quantity }}
                                                            </td>
                                                            <td class="border-t px-6 py-2">
                                                                {{ prescrit.label }}
                                                            </td>
                                                            <td class="border-t py-2 w-px">
                                                                {{ prescrit.dosage }}
                                                            </td>
                                                            <td class="border-t px-2 items-center">
                                                                <span v-for="item in prescrit.dosage_text" :key="index" class="p-1 bg-cyan-400 w-px rounded shadow-sm mr-1">
                                                                    {{ __(item) }}
                                                                </span>
                                                            </td>
                                                            <td class="border-t py-2 w-px">
                                                                {{ prescrit.duration }} (<span>{{__('Day')}}</span>)
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="card flex-fill dash-statistics " v-if="consultation.analyses.length !==0">
                                                <div class="card-body">
                                                    <h4>{{__('Analyses')}}</h4>
                                                    <div class="bg-white rounded-md  overflow-x-auto">
                                                        <table class="w-full whitespace-nowrap text-base">
                                                            <tr class="text-left font-semibold">

                                                                <th class="pb-2 pt-3 px-6">{{__('Analyse')}}</th>
                                                                <th>{{__('Note')}}</th>
                                                                <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                                                            </tr>
                                                            <tr v-for="analyse in consultation.analyses" :key="analyse.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                                                <td class="border-t px-6 py-2 w-px">
                                                                    {{ analyse.title }}
                                                                </td>
                                                                <td class="border-t px-6 py-2">
                                                                    {{ analyse.note}}
                                                                </td>
                                                                <td class="border-t px-6 py-2">
                                                                    <span class="rounded-lg shadow-sm bg-slate-400 text-sm py-1 px-2" v-if=" analyse.status === 0">{{ __('New')}}</span>
                                                                    <span class="rounded-lg shadow-sm bg-green-400 text-sm py-1 px-2" v-else-if=" analyse.status === 1">{{ __('Done')}}</span>
                                                                    <span class="rounded-lg shadow-sm bg-yellow-400 text-sm py-1 px-2" v-else-if=" analyse.status === 2">{{__('In Process')}}</span>
                                                                    <span class="rounded-lg shadow-sm bg-red-400 text-sm py-1 px-2" v-else>{{ __('Cancel')}}</span>
                                                                </td>

                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
<!--                                        Modal Analyse-->
                                        <ModalConsultation v-bind:modalMc="modalMa" :toggleModalConsultation="toggleModalAnalyse">
                                            <template v-slot:header>
                                                {{ __('Add Analysis')}}
                                                <button type="button" class="btn-close" v-on:click="toggleModalAnalyse" aria-label="Close modal" >
                                                    x
                                                </button>
                                            </template>
                                            <template v-slot:body>
                                                <Analyse v-bind:category_analyses="category_analyses" v-bind:consultation="consultation" @closeModal="closeModal"/>
                                            </template>
                                            <template v-slot:footer></template>
                                        </ModalConsultation>
                                        <ModalConsultation v-bind:modalMc="modalMc" :toggleModalConsultation="toggleModalConsultation">
                                            <template v-slot:header> {{__('New Diagnostic')}}
                                                <button v-if="consultation.status ===1"
                                                    type="button"
                                                    class="btn-close"
                                                    v-on:click="toggleModalConsultation"
                                                    aria-label="Close modal"
                                                >
                                                    x
                                                </button>
                                            </template>
                                            <template v-slot:body>
                                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:gap-4 md:grid-cols-3 lg:grid-cols-5 mb-4">
                                                    <div class="shadow-sm rounded p-2 bg-slate-100" v-if="consultation.first_diag.length !==0">
                                                        <ul v-for="( item, index) in consultation.first_diag" :key="index">
                                                            <li class="flex items-center justify-between">
                                                                <span class="text-muted">{{__(index)}}</span>
                                                                <span>{{item}}</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="shadow-sm rounded col-span-2 p-2 bg-cyan-100 h-36 overflow-y-auto" v-if="consultation.diagnostic">
                                                        <ul v-for="diag in consultation.diagnostic">
                                                            <transition-group>
                                                                <li>{{ diag }}</li>
                                                            </transition-group>
                                                        </ul>
                                                    </div>
                                                    <div class="shadow-sm rounded col-span-2 p-2 bg-indigo-100" v-if="consultation.prescriptions.length > 0">
                                                            <TransitionGroup name="list" tag="ul">
                                                                <li class="flex flex-row space-x-2" v-for="prescript in consultation.prescriptions" :key="index">
                                                                    (<span>{{ prescript.quantity}}</span>)
                                                                    <span class="flex flex-col space-y-1">
                                                                        <span>{{ prescript.label }}</span>
                                                                        <span class="text-sm text-muted">
                                                                            <span>{{ prescript.dosage}}</span>
                                                                            (<span v-for="p in prescript.dosage_text" :key="index" class="p-1">
                                                                                {{ __(p) }}
                                                                            </span>)
                                                                            <span>{{ prescript.duration}}{{__('Day')}}</span>
                                                                        </span>
                                                                    </span>
                                                                </li>
                                                            </TransitionGroup>
                                                    </div>
                                                </div>
                                                <div v-if="consultation.status !==1">
                                                    <template v-if="fdiag">
                                                        <FirstDiag :consultation="consultation" v-bind:end-fdiag="endFdiag" />
                                                    </template>
                                                    <template v-if="diag">
                                                        <Diagnostics :consultation="consultation" v-bind:end-diag="endDiag" />
                                                    </template>
                                                    <template v-if="pres">
                                                        <Prescription :consulta="consultation" v-bind:end-pres="endPres" />
                                                    </template>
                                                </div>

                                            </template>
                                            <template v-slot:footer >
                                                <form @submit.prevent="endConsult" v-if="consultation.prescriptions.length !==0 && consultation.status!==1">
                                                    <input type="hidden" name="idconsultation" v-model="form.idconsultation">
                                                    <loading-button :loading="form.processing" class="btn-green btn px-3 ml-auto w-auto" type="submit"> {{__('End Consultation')}}</loading-button>
                                                </form>
                                            </template>
                                        </ModalConsultation>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Chat Main Wrapper -->
    </div>
    <!-- /Chat Main Row -->
</template>

<script>
    import PageHeader from "../../../Shared/PageHeader";
    import HeaderTitle from "../../../Shared/HeaderTitle";
    import {Link, useForm} from '@inertiajs/inertia-vue3'
    import Sugar from "../../../Shared/Sugar";
    import FirstDiag from "./FirstDiag";
    import Diagnostics from "./Diagnostics";
    import DetailPatient from "./DetailPatient";
    import Prescription from "@/Pages/Partner/Consultation/Prescription";
    import ModalConsultation from "@/Shared/ModalConsultation";
    import LoadingButton from "@/Shared/LoadingButton";
    import Analyse from "@/Pages/Partner/Consultation/Analyse";
    import Editor from '@ckeditor/ckeditor5-build-classic';
    import VueMultiselect from 'vue-multiselect'
    export default {
        name: "Consult",
        components: {
            Analyse,
            LoadingButton,
            ModalConsultation, Prescription, DetailPatient, Diagnostics, FirstDiag, Sugar, HeaderTitle, PageHeader,Link,VueMultiselect},
        props : {
            consultation: Object,
            title: String,
            statusConsultation:Array,
            category_analyses:Array
        },
        data: function(){
            return{
                editor: Editor,
                editorConfig: {
                    // Run the editor with the German UI.
                    language: 'fr',
                    height: '500px',
                    ui: {
                        width: '500px',
                        height: '300px'
                    }
                },
                show: false,
                modalMc: false,
                modalMa: false,
             //   options: [this.consultation.category_analyses]
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
                analyses: [],
                idC: props.consultation.id
            })
           const  formH = useForm({
                 idco: props.consultation.id,
                 type:1
             })
            return { form, formH  }
        },
        methods: {
            closeModal: function(){
                this.modalMa = false
            },
            callVoice: function (e) {
                let txt = `patient ${this.consultation.ticket}  ${this.consultation.room?'salle'+this.consultation.room :''}`;
                let parole = new SpeechSynthesisUtterance();
                parole.text = txt;
                parole.pitch = 1.3 //0 à 2 hauteur
                parole.rate = 0.70 //0.5 à 2 vitesse
                speechSynthesis.speak(parole)
            },
            toggleModalConsultation: function () {
                this.modalMc = !this.modalMc;
            },
            toggleModalAnalyse: function () {
                this.modalMa = !this.modalMa;
            },
            hospitalized () {
                this.formH.post(route('hospitalisation.add'),{
                    onSuccess: () => {}
                })
            },
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
            handleConsultation: function () {
                /*  alert(props.consultation.slug)*/
              this.form.put(route('consultation.store'),{
                   _method: 'put',
                    onSuccess: () => this.form.reset()
                })
            }

        },



    }
    //task_window
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

<style>
    /*
      Enter and leave animations can use different
      durations and timing functions.
    */
    .slide-fade-enter-active {
        transition: all 0.3s ease-out;
    }

    .slide-fade-leave-active {
        transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
    }

    .slide-fade-enter-from,
    .slide-fade-leave-to {
        transform: translateX(20px);
        opacity: 0;
    }

    .list-enter-active,
    .list-leave-active {
        transition: all 0.5s ease;
    }
    .list-enter-from,
    .list-leave-to {
        opacity: 0;
        transform: translateX(30px);
    }
</style>
