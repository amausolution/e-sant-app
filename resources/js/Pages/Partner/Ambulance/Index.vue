<template>

    <HeaderTitle :title="title" />

    <div class="content container-fluid">

        <page-header>
            <div class="row align-items-center">
                <div class="col">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('partner.home')">{{__('Dashboard')}}</Link>
                        </li>
                        <li class="breadcrumb-item active">{{title}}</li>
                    </ul>
                </div>
                <div class="col-auto float-right ml-auto">
                    <Button type="button" @click="openModal" class="bg-teal-400 px-6 py-2 text-white shadow-md rounded-md hover:bg-teal-600"><i class="fa fa-plus"></i> {{__('Add Ambulance')}}</Button>
                </div>
            </div>
        </page-header>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Car Id')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Brand')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Year')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Type')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Acquisition Date')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Giver')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Availablity')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Action')}}</th>
                </tr>
                <tr v-for="ambulance in ambulances" :key="ambulance.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm text-black">
                    <td class="border-t w">
                        <Link class="flex items-center px-2 py-2 flex-row text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                                   <span>{{ ambulance.matricule }}</span>
                        </Link>
                    </td>

                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                            {{ ambulance.brand }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                            {{ ambulance.year }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                            {{ ambulance.class}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                            {{ ambulance.acquisition_date}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                            {{ ambulance.giver}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <div class="flex items-center px-6 py-2 text-black">
                            <div class="d-flex flex-col with-switch">
                                <div class="onoffswitch" style="margin-left: 2px">
                                    <input type="checkbox" v-model="ambulance.status" :value="ambulance.status"
                                           :true-value="1"
                                           :false-value="0"
                                           @change="updateStatus($event,ambulance.id)"
                                           class="onoffswitch-checkbox" :id="`status+${ambulance.id}`">
                                    <label class="onoffswitch-label" :for="`status+${ambulance.id}`">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center justify-center px-6 py-2 text-black" tabindex="-1" :href="route('ambulance.edit',{id: ambulance.id})">
                            <span class="text-teal-500" v-if="ambulance.move"><i class="fa-duotone fa-circle-check fa-2x"></i></span>
                            <span class="text-red-500 " v-else><i class="fa-duotone fa-circle-xmark fa-2x"></i></span>
                        </Link>
                    </td>
                    <td class="border-t">
                        <div class="flex flex-row space-x-3 justify-end mr-2">
                            <button type="button" class="flex items-center text-orange-400 text-2xl text-black" @click="openModalEdit(ambulance)"
                            >
                                <i class="fa-duotone fa-file-pen"></i>
                            </button>
                            <button class="flex items-center text-2xl text-red-400" @click="openConfirm(ambulance.id)"
                            >
                                <i class="fa-duotone fa-trash-can"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr v-if="ambulances.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No ambulance found.')}}</td>
                </tr>
            </table>
        </div>
        <Modal v-if="car.length ==0" :openModal="openModal" :isOpen="isOpen" :closeModal="closeModal" :maxWidth="xl">
            <template v-slot:close>
                <button class="absolute text-sky-200 text-xl right-2 top-1 hover:text-sky-900" type="button" @click="closeModal">
                    <i class="fa-duotone fa-circle-xmark"></i>
                </button>
            </template>
              <template v-slot:title>
                 <span>{{__('Add Ambulance')}}</span>
              </template>
              <template v-slot:body>
                <form @submit.prevent="addAmbulance">
                    <div class="mt-2 row">
                        <div class="form-group col-sm-6 mb-3">
                            <label for="">{{__('Brand')}}</label>
                            <input type="text" class="form-control" v-model="form.brand">
                        </div>
                        <div class="form-group col-sm-6 mb-3">
                            <label for="">{{__('Car Id')}}</label>
                            <input type="text" class="form-control" v-model="form.matricule">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('Year')}}</label>
                            <input type="text" class="form-control" v-model="form.year">
                        </div>
                        <div class="form-group col-sm-6 mb-3 ">
                            <label for="">{{__('Acquisition Date')}}</label>
                            <v-date-picker v-model="date" :masks="masks"
                                           :attributes="attributes"
                                           datePicker.popover.placement="left"
                                           popoverDirection="left">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input class="bg-white border px-2 py-1 rounded form-control"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                    />
                                </template>
                            </v-date-picker>
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label for="type">{{__('Type Ambulance')}}</label>
                            <select name="" id="type" class="form-control" v-model="form.class">
                                <option disabled selected>{{__('Select One')}}</option>
                                <option value="option" v-for="option in options" :key="option">{{option}}</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label for="">{{__('Giver')}}</label>
                            <input type="text" class="form-control" v-model="form.giver">
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <div class="d-flex flex-col with-switch">
                                <span class="form-label">{{ __('Status')}}</span>
                                <div class="onoffswitch" style="margin-left: 2px">
                                    <input v-model="form.status" type="checkbox"
                                           :true-value="1"
                                           :false-value="0"
                                           class="onoffswitch-checkbox" id="switch_car">
                                    <label class="onoffswitch-label" for="switch_car">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end mb-6">
                        <loading-button :loading="form.processing"
                                        type="submit"
                                        class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4
                             py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2
                              focus-visible:ring-blue-500 focus-visible:ring-offset-2">   {{__('Add Ambulance')}}</loading-button>
                    </div>
                </form>
              </template>
        </Modal>
        <Modal v-else :openModal="openModalEdit" :isOpen="isOpen" :closeModal="closeModalEdit" :maxWidth="xl">
            <template v-slot:close>
                <button class="absolute text-sky-200 text-xl right-2 top-1 hover:text-sky-900" type="button" @click="closeModalEdit">
                    <i class="fa-duotone fa-circle-xmark"></i>
                </button>
            </template>
              <template v-slot:title>
                 <span>{{__('Edit Ambulance')}}</span>
              </template>
              <template v-slot:body>
                <form @submit.prevent="editAmbulance">
                    <div class="mt-2 row">
                        <div class="form-group col-sm-6 mb-3">
                            <label for="">{{__('Brand')}}</label>
                            <input type="text" class="form-control" v-model.lazy="car.brand">
                        </div>
                        <div class="form-group col-sm-6 mb-3">
                            <label for="">{{__('Car Id')}}</label>
                            <input type="text" class="form-control" v-model.lazy="car.matricule">
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('Year')}}</label>
                            <input type="text" class="form-control" v-model.lazy="car.year">
                        </div>
                        <div class="form-group col-sm-6 mb-3 ">
                            <label for="">{{__('Acquisition Date')}}</label>
                            <v-date-picker v-model="car.acquisition_date" :masks="masks"
                                           :attributes="attributes"
                                           datePicker.popover.placement="left"
                                           popoverDirection="left">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input class="bg-white border px-2 py-1 rounded form-control"
                                        :value="inputValue"
                                        v-on="inputEvents"
                                    />
                                </template>
                            </v-date-picker>
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label for="">{{__('Type Ambulance')}}</label>
                            <select name="" id="" class="form-control" v-model.lazy="car.class">
                                <option disabled selected>{{__('Select One')}}</option>
                                <option :value="option" v-for="option in options" :key="option">{{option}}</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label for="">{{__('Giver')}}</label>
                            <input type="text" class="form-control" v-model.lazy="car.giver">
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <div class="d-flex flex-col with-switch">
                                <span class="form-label">{{ __('Status')}}</span>
                                <div class="onoffswitch" style="margin-left: 2px">
                                    <input v-model="car.status" type="checkbox"
                                           :value="car.status"
                                           :true-value="1"
                                           :false-value="0"
                                           class="onoffswitch-checkbox" id="car">
                                    <label class="onoffswitch-label" for="car">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end mb-6">
                        <loading-button :loading="form.processing"
                             type="submit"
                             class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4
                             py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2
                             focus-visible:ring-blue-500 focus-visible:ring-offset-2"> {{__('Update Ambulance')}}</loading-button>
                    </div>
                </form>
              </template>
        </Modal>
        <ModalConfirmation :openConfirm="openConfirm" :confirm="confirm" :isConfirm="isConfirm" :closeConfirm="closeConfirm" :maxWidth="sm">

        </ModalConfirmation>
    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import Modal from "../../../Components/Modal";
    import {ref} from "vue";
    import LoadingButton from "@/Shared/LoadingButton";
    import ModalConfirmation from "../../../Components/ModalConfirmation";
    import { Inertia } from '@inertiajs/inertia'
    export default {
        name: "Index",
        components: {LoadingButton, Modal, HeaderTitle, PageHeader, Link, ModalConfirmation},
        props:{
            ambulances:Object,
            options:Array,
            title: String
        },
        computed: {
            attributes() {
                return [
                    {
                        popover: {
                            datePickerShowDayPopover: false,
                            popoverExpanded: true,
                            popoverDirection:"left",
                            isInteractive: false
                        },
                    }
                ]
            }
         },
        data(){
            return{
                car:[],
                idA:'',
                isOpen: ref(false),
                isConfirm: ref(false),
                xl:'xl',
                sm:'sm',
                form: useForm({
                    brand:'',
                    year:'',
                    matricule:'',
                    giver:'',
                    class:'',
                    status:'',
                    acquisition_date:''
                }),
                formEditor: useForm({
                    brand: '',
                    year: '',
                    matricule: '',
                    giver: '',
                    class: '',
                    status: '',
                    acquisition_date: ''
                }),
                date: new Date(),
                masks: {
                    input: 'DD-MMM-YYYY',
                },
            }
        },
        methods:{
            openConfirm(a) {
                this.idA = a
                this.isConfirm= true
            },
            closeConfirm() {
                this.isConfirm = false
                this.idA='';
            },
            confirm (){
                Inertia.delete(route('ambulance.destroy',{id:this.idA}), {
                    onSuccess: () => this.closeConfirm(),
                })
            },
            updateStatus (event, idA) {
                Inertia.put(route('ambulance.update.status',{id: idA}),{
                    status:event.target.checked
                })
            },
           openModal() {
                this.isOpen= true
            },
           closeModal() {
                this.isOpen = false
            },
            modalEdit(e){
               this.car=e
                this.formEditor.brand = e.brand

            },
            openModalEdit(e) {
                this.car=e
                this.isOpen= true
            },
            closeModalEdit() {
                this.car=[]
                this.isOpen = false
            },
            addAmbulance (){
                this.form.transform( (data) => ({
                    ...data,
                    acquisition_date: this.date
                })).post(route('ambulance.store'),{
                    onSuccess:()=> {this.closeModal()}
                })
            },
            editAmbulance (){
                this.form.transform( (data) => ({
                    ...data,
                    acquisition_date: this.car.acquisition_date,
                    brand: this.car.brand,
                    year: this.car.year,
                    giver: this.car.giver,
                    matricule: this.car.matricule,
                    class: this.car.class,
                    status: this.car.status,
                })).put(route('ambulance.update',{id: this.car.id}),{
                    onSuccess:()=> {
                        this.closeModalEdit()
                        this.car= []
                    }
                })
            }
        }
    }
</script>

<style scoped>
    .vc-popover-content-wrapper{
        font-size: 11px!important;
    }
    .vc-pane-container{
        font-size: 11px!important;

    }
    .onoffswitch-switch {
        background: #fff;
        border-radius: 20px;
        bottom: 0;
        display: block;
        height: 20px;
        margin: 3px 1px;
        position: absolute;
        right: 43px;
        top: 0;
        transition: all 0.3s ease-in 0s;
        width: 20px;
    }
    .onoffswitch {
        margin-left: auto;
        position: relative;
        width: 65px;
    }
    .onoffswitch-inner::before {
        background-color: #06B6D4;
        color: #fff;
        content: "YES";
        padding-left: 14px;
        font-size: small;
        height: 25px;
        line-height: 25px;
    }
    .onoffswitch-inner::after {
        background-color: #94A3B8;
        color: #fff;
        content: "NO";
        padding-left: 14px;
        font-size: small;
        line-height: 25px;
        height: 25px;
    }
</style>
