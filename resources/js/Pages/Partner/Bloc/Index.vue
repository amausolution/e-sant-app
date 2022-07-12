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
                    <button type="button" @click="openModal" class="btn add-btn"><i class="fa fa-plus"></i> Add Room</button>
                </div>
            </div>
        </page-header>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-2">{{__('Room')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Availablity')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                    <th class="pb-2 pt-3 px-2">{{__('Action')}}</th>
                </tr>
                <tr v-for="room in blocs" :key="room.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <div class="flex px-2 py-2 flex-col text-black">
                         <span class="flex space-x-2">
                            <span>{{__('Bloc')}}</span>
                            <span>{{room.number}}</span>
                         </span>
                         <span>{{room.name}}</span>
                        </div>
                    </td>
                    <td class="border-t">
                        <div class="flex items-center px-6 py-2">
                            <span v-if="room.availablity">{{__('Available')}}</span>
                            <span v-else>
                                    <!--   {{room.duration}}-->
                                   <span>{{moment(room.duration).endOf('hour').fromNow() }}</span>
                            </span>

                        </div>
                    </td>
                    <td class="border-t ">
                        <div class="flex items-center px-6 py-2">
                            <span v-if="room.status" class="text-green-400 text-lg"><i class="fa-duotone fa-circle-check"></i></span>
                            <span v-else class="text-red-400 text-lg"><i class="fa-duotone fa-circle-xmark"></i></span>
                        </div>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex flex-row space-x-3 justify-end mr-2">
                            <button class="flex items-center py-2 text-2xl text-orange-400" tabindex="-1" @click="openModalEdit(room)"
                                    type="button" :title="__('Edit')"
                            >
                                <i class="fa-duotone fa-file-pen"></i>
                            </button>
                            <button @click="openConfirm(room.id)" class="flex items-center py-2 text-2xl text-red-400" tabindex="-1"
                                 type="button" :title="__('Destroy')"
                            >
                                <i class="fa-duotone fa-trash"></i>
                            </button>
                        </div>

                    </td>
                </tr>
                <tr v-if="blocs.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No Bloc Room found.')}}</td>
                </tr>
            </table>
        </div>
        <Modal v-if="bloc.length ==0" :openModal="openModal" :isOpen="isOpen" :closeModal="closeModal" :maxWidth="xl">
            <template v-slot:close>
                <button class="absolute text-sky-200 text-xl right-2 top-1 hover:text-sky-900" type="button" @click="closeModal">
                    <i class="fa-duotone fa-circle-xmark"></i>
                </button>
            </template>
            <template v-slot:title>
                <span>{{__('Add Ambulance')}}</span>
            </template>
            <template v-slot:body>
                <form @submit.prevent="addRoom">
                    <div class="mt-4 row filter-row">
                        <div class="col-12">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" v-model="form.number">
                                <label class="focus-label">{{__('Bloc Number')}}</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group form-focus">
                                <input type="text" class="form-control floating" v-model.trim="form.name">
                                <label class="focus-label">{{__('Bloc Name')}}</label>
                            </div>
                        </div>
                        <div class="col-12">
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
                              focus-visible:ring-blue-500 focus-visible:ring-offset-2">   {{__('Add Bloc Room')}}</loading-button>
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
                <span>{{__('Edit Bloc Room')}}</span>
            </template>
            <template v-slot:body>
                <form @submit.prevent="editRoom">
                    <div class="mt-2 row">
                        <div class="form-group col-sm-6 mb-3">
                            <label for="">{{__('Name of bloc')}}</label>
                            <input type="text" class="form-control" v-model.lazy="bloc.name">
                        </div>
                        <div class="form-group col-sm-6 mb-3">
                            <label for="">{{__('Number of room')}}</label>
                            <input type="text" class="form-control" v-model.lazy="bloc.number">
                        </div>

                        <div class="form-group col-sm-6 mt-3">
                            <div class="d-flex flex-col with-switch">
                                <span class="form-label">{{ __('Status')}}</span>
                                <div class="onoffswitch" style="margin-left: 2px">
                                    <input v-model="bloc.status" type="checkbox"
                                           :value="bloc.status"
                                           :true-value="1"
                                           :false-value="0"
                                           class="onoffswitch-checkbox" id="bloc">
                                    <label class="onoffswitch-label" for="bloc">
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
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3"
    import Pagination from "@/Shared/Pagination";
    import Modal from "../../../Components/Modal";
    import {ref} from "vue";
    import LoadingButton from "@/Shared/LoadingButton";
    import ModalConfirmation from "../../../Components/ModalConfirmation";
    import {Inertia} from "@inertiajs/inertia";
    import moment from 'moment'
    export default {
        name: "index",
        components: {LoadingButton, ModalConfirmation, Modal, Pagination, PageHeader, HeaderTitle, Link},
        computed: { moment: () => moment },
        props: {
            title: String,
            blocs: Object
        },
        data(){
            return {
                bloc:[],
                idR:'',
                isOpen: ref(false),
                isConfirm: ref(false),
                xl:'xl',
                sm:'sm',
                form: useForm({
                    name:null,
                    number: null,
                    status: null
                })
            }
        },
        methods:{
            openConfirm(a) {
                this.idR = a
                this.isConfirm= true
            },
            closeConfirm() {
                this.isConfirm = false
                this.idR='';
                this.$inertia.get(route('bloc.index'))
            },
            confirm (){
                Inertia.delete(route('bloc.destroy',{id:this.idR}), {
                    onSuccess: () => this.closeConfirm(),
                })
            },
            updateStatus (event, idR) {
                Inertia.put(route('ambulance.update.status',{id: idR}),{
                    status:event.target.checked
                })
            },
            openModal() {
                this.isOpen= true
            },
            closeModal() {
                this.isOpen = false
                Inertia.reload(
                    {only: ['blocs']}
                   )
            },
            modalEdit(e){
                this.bloc=e
            },
            openModalEdit(e) {
                this.bloc=e
                this.isOpen= true
            },
            closeModalEdit() {
                this.bloc=[]
                this.isOpen = false
            },
            addRoom (){
                this.form.post(route('bloc.store'),{
                    onSuccess:()=> {this.closeModal()}
                })
            },
            editRoom (){
                this.form.transform( (data) => ({
                    ...data,
                    status: this.bloc.status,
                    name: this.bloc.name,
                    number: this.bloc.number,
                })).put(route('bloc.update',{id: this.bloc.id}),{
                    onSuccess:()=> {
                        this.closeModalEdit()
                        this.bloc= []
                    }
                })
            }
        }
    }
</script>

<style scoped>
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
        content: "ON";
        padding-left: 14px;
        font-size: small;
        height: 25px;
        line-height: 25px;
    }
    .onoffswitch-inner::after {
        background-color: #94A3B8;
        color: #fff;
        content: "OFF";
        padding-left: 14px;
        font-size: small;
        line-height: 25px;
        height: 25px;
    }
</style>
