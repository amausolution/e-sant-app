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
                            </ul>
                        </div>
                    </div>
                    <div class="chat-contents">
                        <div class="chat-content-wrap">
                            <div class="chat-wrap-inner">
                                <div class="chat-box">
                                    <div class="chats">
                                        <div class="flex flex-col md:flex-row justify-center space-y-2 md:space-x-3 ">
                                            <button class="px-6 py-2 shadow-md rounded text-white bg-slate-500 text-lg
                                             font-semibold hover:bg-slate-700 justify-center flex flex-row items-center justify-between"
                                                    v-on:click="toggleModalConsultation" v-if="consultation.status!==1">
                                                <span>{{ __('Start The Consultation')}}</span>
                                                <i class="fa-solid fa-stethoscope"></i>
                                            </button>
                                            <button class="px-6 py-2 shadow-md rounded text-white bg-orange-500 text-base
                                             font-semibold hover:bg-orange-700 justify-center flex flex-row items-center justify-between space-x-2"
                                                    v-on:click="toggleModalAnalyse" v-if="consultation.diagnostic.length !==0">
                                                <i class="fa-solid fa-vial"></i>
                                                <span>{{ __('Prescribe Analyse')}}</span>
                                            </button>
                                            <form v-on:submit.prevent="hospitalized"  v-if="consultation.diagnostic.length !==0 && consultation.hospitalisation.length === 0">
                                                <button class="px-6 py-2 shadow-md rounded text-white bg-rose-500 text-base space-x-2
                                                 font-semibold hover:bg-rose-700 justify-center flex flex-row items-center justify-between"
                                                       >
                                                    <i class="fa-solid fa-bed-pulse"></i>
                                                    <span>{{ __('Hospitalized This Patient')}}</span>
                                                </button>
                                            </form>
                                            <div v-else>
                                                <span class="px-6 py-2 shadow-md rounded text-white bg-rose-500 text-base space-x-2
                                                 font-semibold hover:bg-rose-700 justify-center flex flex-row items-center" v-if="consultation.hospitalisation.status===0">
                                                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 50 50" fill="currentColor">
                                                      <path d="M3 9C1.3545455 9 0 10.354545 0 12L0 42L6 42L6 37L44 37L44 42L50 42L50 29L50 28L50 27C50 24.254545 47.745455 22 45 22L21 22 A 1.0001 1.0001 0 0 0 20 23L20 28L19.990234 28 A 1.0001 1.0001 0 0 0 19.962891 27.859375C19.962891 27.859375 18.686818 22.784509 14.527344 20.820312C13.489567 20.330251 12.413269 20.096215 11.382812 20.021484C8.9348293 19.843953 6.8055853 20.542355 6 20.849609L6 12C6 10.354545 4.6454545 9 3 9 z M 3 11C3.5545455 11 4 11.445455 4 12L4 30L5 30L13.96875 30C14.378184 30.021114 14.777885 30.021522 15.164062 30L21 30L48 30L48 40L46 40L46 35L4 35L4 40L2 40L2 12C2 11.445455 2.4454545 11 3 11 z M 11.248047 22.056641C12.073841 22.103781 12.911605 22.268967 13.673828 22.628906C16.108224 23.778482 17.186298 26.305558 17.621094 27.457031C16.950797 27.670474 15.789901 27.987024 14.466797 28L14.037109 28C13.16748 27.961536 12.243376 27.777938 11.347656 27.330078 A 1.0001 1.0001 0 0 0 11.326172 27.320312C8.8947024 26.172155 7.8245508 23.727704 7.3847656 22.587891C8.1959031 22.328179 9.4291133 21.952797 11.248047 22.056641 z M 22 24L45 24C46.654545 24 48 25.345455 48 27L48 28L22 28L22 24 z M 6 24.53125C6.5802025 25.64641 7.4487205 26.945229 8.7265625 28L6 28L6 24.53125 z" fill="currentColor" />
                                                    </svg>
                                                   <span>
                                                       {{ __('Patient Hospitalization Asked ')}}
                                                   </span>

                                                </span>
                                                <span class="px-6 py-2 shadow-md rounded text-white bg-rose-500 text-base space-x-2
                                                 font-semibold hover:bg-rose-700 justify-center flex flex-row items-center" v-else-if="consultation.hospitalisation.status===1"> {{ __('Patient Hospitalized ')}}</span>
                                            </div>
                                        </div>
                                        <div v-if="consultation.status === 1">
                                            <div class="mt-3">
                                                <div class="card flex-fill dash-statistics ">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{__('First Diagnostics')}}</h5>
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
                                                        <ul id="task-list">
                                                            <li class="task" v-for="diag in consultation.diagnostic" :key="index">
                                                                <div class="task-container">
                                                                    <span class="task-label">{{diag}}</span>
                                                                </div>
                                                            </li>
                                                        </ul>
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
                                                            <th class="pb-2 pt-3 px-6">{{__('Dosage')}}</th>
                                                            <th class="pb-2 pt-3 px-6">{{__('Dosage Text')}}</th>
                                                            <th class="pb-2 pt-3 px-6">{{__('Duration')}}</th>
                                                        </tr>
                                                        <tr v-for="prescrit in consultation.prescriptions" :key="prescrit.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                                                            <td class="border-t px-6 py-2 w-px">
                                                                {{ prescrit.quantity }}
                                                            </td>
                                                            <td class="border-t px-6 py-2">
                                                                {{ prescrit.label }}

                                                            </td>
                                                            <td class="border-t px-6 py-2">
                                                                {{ prescrit.dosage }}
                                                            </td>
                                                            <td class="border-t px-2  w-auto items-center">
                        <span v-for="item in prescrit.dosage_text" :key="index" class="p-1 bg-cyan-400 max-w-sm  rounded shadow-sm">
                            {{ __(item) }}
                        </span>
                                                            </td>
                                                            <td class="border-t px-6 py-2 w-px">
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
                                                                    {{ analyse.analyse.title }}
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
                                        <ModalConsultation v-bind:modalMc="modalMa" :toggleModalConsultation="toggleModalAnalyse">
                                            <template v-slot:header>
                                                {{ __('Add Analysis')}}
                                                <button type="button"
                                                        class="btn-close"
                                                        v-on:click="toggleModalAnalyse"
                                                        aria-label="Close modal"
                                                >
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
                                                    <div class="shadow-sm rounded col-span-2 p-2 bg-cyan-100 h-36 overflow-y-auto" v-if="consultation.diagnostic.length>0">
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
                                     <!--<FirstDiag :consultation="consultation" />
                                     <Diagnostics :consultation="consultation" />
                                     <Prescription :consulta="consultation" />-->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Chats View -->
            <!-- Chat Right Sidebar -->
            <div class="col-lg-2 message-view chat-profile-view chat-sidebar" id="task_window" >
                <detail-patient :patient="{ consultation }"/>
            </div>

            <!-- /Chat Right Sidebar -->
        </div>
        <!-- /Chat Main Wrapper -->
    </div>
    <!-- /Chat Main Row -->
</template>

<script>
    import PageHeader from "../../../Shared/PageHeader";
    import HeaderTitle from "../../../Shared/HeaderTitle";
    import { Link } from '@inertiajs/inertia-vue3'
    import Sugar from "../../../Shared/Sugar";
    import FirstDiag from "./FirstDiag";
    import Diagnostics from "./Diagnostics";
    import DetailPatient from "./DetailPatient";
    import Prescription from "@/Pages/Partner/Consultation/Prescription";
    import ModalConsultation from "@/Shared/ModalConsultation";
    import LoadingButton from "@/Shared/LoadingButton";
    import Analyse from "@/Pages/Partner/Consultation/Analyse";
    export default {
        name: "Consult",
        components: {
            Analyse,
            LoadingButton,
            ModalConsultation, Prescription, DetailPatient, Diagnostics, FirstDiag, Sugar, HeaderTitle, PageHeader,Link},
        props : {
            consultation: Object,
            title: String,
            statusConsultation:Array,
            category_analyses:Array
        },
        data: function(){
            return{
                show: false,
                modalMc: false,
                modalMa: false,
                diag:false,
                pres:false,
                fdiag:true,
                form: this.$inertia.form({
                    _method: 'put',
                    idconsultation: this.consultation.slug
                }),
                formH: this.$inertia.form({
                    _method: 'post',
                    idco: this.consultation.slug,
                    type:1
                }),

             //   options: [this.consultation.category_analyses]
            }
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
            endConsult () {
                this.form.post(route('end.cons'),{
                    onSuccess: () =>  this.modalMc=false

                })
            },

            hospitalized () {
                this.formH.post(route('hospitalisation.add'),{
                    onSuccess: () => {}
                })
            },
            endPres:function(){
               this.pres = false
            },
            endDiag:function(){
               this.diag = false
               this.pres=true;
            },
            endFdiag: function(){
                this.fdiag = false
                this.diag = true
            }
        },
    }
    //task_window
</script>

<style scoped>

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
