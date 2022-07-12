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
                    <Link :href="route('room.index')" class="btn add-btn"><i class="fa fa-arrow-left-long"></i> Go Back</Link>
                </div>
            </div>
        </page-header>
        <form class="row" @submit.prevent="updateForm">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">{{__('Room Form')}}</h4>
                    </div>
                    <div class="card-body">
                        <div  class="row">
                            <div class="form-group col-6 col-md-3 col-lg-3">
                                <label>{{__('Room Number')}}</label>
                                <input type="text" class="form-control" v-model="form.room_number">
                                <span class="help-block text-xs" v-if="form.errors.room_number">{{form.errors.room_number}}</span>
                            </div>
                            <div class="form-group col-6 col-md-3 col-lg-3">
                                <label>{{__('Room Name')}} <span class="text-xs">({{__('optional')}})</span></label>
                                <input type="text" class="form-control" v-model="form.name">
                                <span class="help-block text-xs" v-if="form.errors.name">{{form.errors.name}}</span>
                            </div>
                            <div class="form-group col-6 col-md-3 col-lg-3">
                                <label for="cl">{{__('Class')}}</label>
                                <select name="class" id="cl" class="form-control" v-model="form.classRoom">
                                    <option value="null" selected disabled >{{ __('Select One')}}</option>
                                    <option v-for="(clas, c) in classRoom" :value="c" :selected="{'selected':room.class === c}">{{ clas}}</option>
                                </select>
                                <span class="help-block text-xs" v-if="form.errors.level">{{form.errors.classRoom}}</span>
                            </div>
                            <div class="form-group col-6 col-md-3 col-lg-3">
                                <label for="price">{{__('Price')}}</label>
                                <input id="price" type="text" class="form-control" v-model.number="form.price">
                                <span class="help-block text-xs" v-if="form.errors.price">{{form.errors.price}}</span>
                            </div>
                            <div class="form-group col-6 col-md-3 col-lg-3">
                                <label>{{__('Room level')}}</label>
                                <input type="text" class="form-control" v-model="form.level">
                                <span class="help-block text-xs" v-if="form.errors.level">{{form.errors.level}}</span>
                            </div>

                            <div class="form-group col-12 flex flex-wrap space-x-24 mt-3">
                                <div class="flex flex-row space-x-2 items-baseline">
                                    <label for="clim">{{__('Clim')}}</label>
                                    <input id="clim" :value="1" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.clim"
                                           true-value="1"
                                           false-value="0"
                                    >
                                    <span v-if="form.errors.clim" class="help-block">{{form.errors.clim}}</span>
                                </div>
                                <div class="flex flex-row space-x-2 items-baseline">
                                    <label for="wc">{{__('WC Intern')}}</label>
                                    <input id="wc" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.wc_in"
                                           true-value="1"
                                           false-value="0"
                                    >
                                    <span v-if="form.errors.wc_in" class="help-block">{{form.errors.wc_in}}</span>
                                </div>
                                <div class="flex flex-row space-x-2 items-baseline">
                                    <label for="ba">{{__('Bed Accompanying')}}</label>
                                    <input id="ba" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.bed_accompanying"
                                           true-value="1"
                                           false-value="0"
                                    >
                                    <span v-if="form.errors.bed_accompanying" class="help-block">{{form.errors.bed_accompanying}}</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="bg-white rounded-md shadow overflow-x-auto col-12 p-4">
                    <div>
                        <h3>{{__('Add Rooms')}}</h3>
                    </div>
                    <table class="w-full whitespace-nowrap text-base">
                        <tr v-for="(bed,k) in form.beds" :key="k" class="py-4">
                            <td class="border-t py-3 md:px-2 w-px md:w-auto">
                                <label>{{__('Bed Number')}}</label>
                                <input type="text" class="form-control" v-model.number="bed.bed_number">
                            </td>
                            <td class="border-t py-3 md:px-2">
                                <label for="type">{{__('Type')}}</label>
                                <select id="type" v-model="bed.type"  class="form-control" >
                                    <option v-for="(tpe,t) in typeBed" :value="t" :key="t" >{{tpe}}</option>
                                </select>
                            </td>
                            <td class="border-t py-3 w-px">
                                <div class="card-title with-switch flex flex-col ml-4">
                                    <span class="text-base mb-2">{{__('Status')}}</span>
                                    <div class="onoffswitch" style="margin-left: 0">
                                        <input type="checkbox" name="status[]" class="onoffswitch-checkbox" :id="`status${k}`" checked v-model="bed.status" value="1"
                                               true-value="1"
                                               false-value="0"
                                        >
                                        <label class="onoffswitch-label" :for="`status${k}`">
                                            <span class="onoffswitch-inner"></span>
                                            <span class="onoffswitch-switch"></span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                            <td class="border-t py-3 w-px">
                                <Button class="flex items-center px-2 py-2 text-xl font-semibold" tabindex="-1" type="button" :title="__('Remove')"
                                        @click="remove(k)"
                                        v-show="k || ( !k && form.beds.length > 1)"
                                >
                                    <i class="la la-trash text-red-400"></i>
                                </Button>
                                <Button class="flex items-center px-2 py-2 text-xl font-semibold" tabindex="-1" type="button" :title="__('Add')"
                                        @click="add(k)"
                                        v-show="k === form.beds.length-1"
                                >
                                    <i class="la la-plus text-green-400"></i>
                                </Button>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="text-right flex flex-row justify-end space-x-8 items-baseline mt-3">
                    <div class="flex flex-row space-x-3">
                        <label for="status">{{ __('Status')}}</label>
                        <input id="status" type="checkbox" class="form-control"
                               style="width: 1rem; height: 1rem"
                               true-value="1"
                               false-value="0"
                               value="1" v-model="form.statusRoom">
                    </div>
                    <loading-button :loading="form.processing" class="btn-indigo px-3 mt-3" type="submit">{{__('Update Room')}}</loading-button>
                </div>
            </div>
        </form>

    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link} from "@inertiajs/inertia-vue3"
    import LoadingButton from "@/Shared/LoadingButton";
    import Multiselect from 'vue-multiselect'

    export default {
        name: "Edit",
        components: {LoadingButton, HeaderTitle, PageHeader, Link, Multiselect},
        props: {
            title: String,
            classRoom: Array,
            typeBed: Array,
            room: Object,
            bedsRoom: Array
        },
        data(props){
            return {
                form: this.$inertia.form({
                    name: props.room.name,
                    wc_in:props.room.wc_in,
                    clim: props.room.clim,
                    level: props.room.level,
                    price: props.room.price,
                    statusRoom: props.room.status,
                    room_number: props.room.room_number,
                    bed_accompanying: props.room.bed_accompanying,
                    classRoom: props.room.class,
                    beds: props.bedsRoom.length ? props.bedsRoom : [{
                        bed_number: '',
                        type: null,
                        status: ''
                    }]
                })
            }
        },
        methods :{
            add(index) {
                this.form.beds.push({
                    bed_number: '',
                    type: '',
                    status: ''
                });
            },
            remove(index) {
                this.form.beds.splice(index, 1);
            },
            updateForm: function () {
                this.form.put(route('room.update',{id: this.room.id}),{
                    preserveScroll: true,
                    onSuccess: () => {
                       // this.form.reset();
                        // props.endPres()
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
