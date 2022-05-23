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
                    <Link :href="route('hospitalisation.index')" class="btn add-btn"><i class="la la-long-arrow-left"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>
        <form class="row" @submit.prevent="submitHospi">
            <input type="hidden" v-model="form.slug">
            <div class="col-12 col-md-6 ">
                <div class="shadow-sm rounded-lg bg-white p-2 sticky top-24">
                 <div class="flex flex-col mb-3">
                     <label for="">{{ __('Accompanying Name')}}</label>
                     <input type="text" class="form-control" v-model="form.accompanying">
                     <span class="help-block text-xs" v-if="form.errors.accompanying">{{ form.errors.accompanying}}</span>
                 </div>
                    <div class="flex flex-col mb-3">
                        <label for="">{{ __('Accompanying Phone')}}</label>
                        <input type="text" class="form-control" v-model="form.accompanying_phone">
                        <span class="help-block text-xs" v-if="form.errors.accompanying_phone">{{ form.errors.accompanying_phone}}</span>
                    </div>
                    <div class="flex flex-col mb-3">
                        <label for="p">{{ __('Type Piece Guarantor')}}</label>
                        <select name="type_piece" id="p" class="form-control" v-model="form.type_piece">
                            <option value="" selected disabled>{{ ('Select Piece')}}</option>
                            <option v-for="(piece, p) in type_piece" :value="p" :key="p">{{piece}}</option>
                        </select>
                        <span class="help-block text-xs" v-if="form.errors.type_piece">{{ form.errors.type_piece}}</span>
                    </div>
                    <div class="flex flex-col mb-3">
                        <label for="pg">{{ __('Number Piece Guarantor')}}</label>
                        <input id="pg" type="text" class="form-control" v-model="form.piece_guarantor">
                        <span class="help-block text-xs" v-if="form.errors.piece_guarantor">{{ form.errors.piece_guarantor}}</span>
                    </div>
                    <div class="flex flex-col mb-3">
                        <div class="flex flex-row space-x-2 items-center mt-3">
                            <label for="indemnification">{{ __('Have Indemnification')}}</label>
                            <input id="indemnification" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.indemnification">
                        </div>
                       <span class="help-block text-xs" v-if="form.errors.indemnification">{{ form.errors.indemnification}}</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 ">
                <div class="p-2">
                    <div v-for="room in rooms" class="flex flex-col space-y-4 shadow-md rounded-lg border p-2 mb-4">
                        <span class="text-lg font-semibold">{{__('Room')}}-{{ room.room_number}}</span>
                        <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4 radiobtn">
                            <div  v-for="(bed,b) in room.beds" class="flex flex-row space-x-2 radiobtn">
                                <input :disabled="bed.status" name="bed" :value="bed.id" :id="`be${bed.id}`" class="form-control" type="radio" v-model="form.bed" style="width: 1rem; height: 1rem">
                                <label :for="`be${bed.id}`" :class="bed.status? 'text-red-500 flex flex-row': 'text-green-500 flex flex-row' ">
                                    <template v-if="bed.status">
                                        <i class="fa-duotone fa-bed-pulse fa-xl"></i>
                                       <span>N° {{ bed.bed_number}}</span>
                                    </template>
                                    <template v-else>
                                        <i class="fa-duotone fa-bed-empty fa-xl"></i>
                                       <span>N° {{ bed.bed_number}}</span>
                                    </template>
                                </label>
                            </div>
                        </div>
                    </div>
                    <span class="help-block text-xs" v-if="form.errors.bed">{{ form.errors.bed}}</span>
                </div>
            </div>
            <div class="flex justify-center items-center">
                <loading-button :loading="form.processing" class="btn-indigo px-3 mt-3" type="submit">{{__('Register Hospitalisation')}}</loading-button>
            </div>
        </form>
    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link} from "@inertiajs/inertia-vue3"
    import { useForm } from '@inertiajs/inertia-vue3'
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "new",
        components: {LoadingButton, HeaderTitle, PageHeader, Link},
        props: {
            title: String,
            hospitalisation: Array,
            rooms: Object,
            type_piece: Array
        },
        data(){
            return {

            }
        },
        setup (props){
            const form = useForm({
                accompanying:'',
                accompanying_phone:'',
                piece_guarantor:'',
                type_piece:'',
                bed:'',
                slug: props.hospitalisation.slug,
                indemnification:''
            })
            return {form}
        },
        methods: {
            submitHospi () {
                this.form.post(route('hospitalisation.store'),{})
            }
        }
    }
</script>

<style scoped>
    .radiobtn label {
        display: block;
        background: #ffffff;
        /*color: #444;*/
        border-radius: 5px;
        padding: 10px 10px;
        border: 2px solid #fdd591;
        margin-bottom: 5px;
        cursor: pointer;
    }
    .radiobtn label:after, .radiobtn label:before {
        content: "";
        position: absolute;
        right: 11px;
        top: 11px;
        /*width: 20px;*/
        /*height: 20px;*/
        border-radius: 3px;
        /*background: #fdcb77;*/
    }
    .radiobtn label:before {
        transition: 0.1s width cubic-bezier(0.075, 0.82, 0.165, 1) 0s, 0.3s height cubic-bezier(0.075, 0.82, 0.165, 2) 0.1s;
        z-index: 2;
        overflow: hidden;
        background-size: 13px;
        width: 0;
        height: 0;
        background: transparent url(data:image/svg+xml; base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAxNS4zIDEzLjIiPiAgPHBhdGggZmlsbD0iI2ZmZiIgZD0iTTE0LjcuOGwtLjQtLjRhMS43IDEuNyAwIDAgMC0yLjMuMUw1LjIgOC4yIDMgNi40YTEuNyAxLjcgMCAwIDAtMi4zLjFMLjQgN2ExLjcgMS43IDAgMCAwIC4xIDIuM2wzLjggMy41YTEuNyAxLjcgMCAwIDAgMi40LS4xTDE1IDMuMWExLjcgMS43IDAgMCAwLS4yLTIuM3oiIGRhdGEtbmFtZT0iUGZhZCA0Ii8+PC9zdmc+ no-repeat center;});
    }
    .radiobtn input[type="radio"] {
        display: none;
        position: absolute;
        width: 100%;
        appearance: none;
    }
    .radiobtn input[type="radio"]:checked + label {
        color: #fdcb77;
        animation-name: blink;
        animation-duration: 1s;
        border-color: #fcae2c;
    }
    .radiobtn input[type="radio"]:disabled + label {
        color: #ef4444;
        cursor: not-allowed;
        animation-name: blink;
        animation-duration: 1s;
        border-color: #ef4444;
    }
  /*  .radiobtn input[type="radio"]:checked + label:after {
        background: #fcae2c;
    }
    .radiobtn input[type="radio"]:checked + label:before {
        width: 20px;
        height: 20px;
    }*/
    @keyframes blink {
        0% {
            background-color: #fdcb77;
        }
        10% {
            background-color: #fdcb77;
        }
        11% {
            background-color: #fdd591;
        }
        29% {
            background-color: #fdd591;
        }
        30% {
            background-color: #fdcb77;
        }
        50% {
            background-color: #fdd591;
        }
        45% {
            background-color: #fdcb77;
        }
        50% {
            background-color: #fdd591;
        }
        100% {
            background-color: #ffffff;
        }
    }

</style>
