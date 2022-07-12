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
                    <Link :href="route('hospitalized.index')" class="btn add-btn"><i class="fa-solid fa-hospital-user"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white p-4 rounded-md shadow-md">
            <div>
                <span>{{__('Date In')}}</span>
                <span>{{moment(hospitalisation.date).locale('fr').format('DD MMM YYYY, h:mm')}}</span>
            </div>
            <div>
                <span>{{__('Room')}}</span>
                <span>{{hospitalisation.room}}</span>
            </div>
            <div>
                <span>{{__('Bed')}}</span>
                <span>{{hospitalisation.bed}}</span>
            </div>
            <div>
                <span>{{__('Price')}}</span>
                <span>{{hospitalisation.price}}</span>
            </div>
            <div>
                <span>{{__('Have indemnification')}}</span>
                <span v-if="hospitalisation.indemnification">
                <i class="fa-duotone fa-badge-check text-green-400 fa-1x"></i>
                </span>
                <span v-else>
                    <i class="fa-duotone fa-shield-xmark text-red-400"></i>
                </span>
            </div>
            <div>
                <span>Accompanying</span>
                <span>{{hospitalisation.accompanying.name}}</span>
            </div>
            <div>
                <span>Accompanying Phone</span>
                <span>{{hospitalisation.accompanying.phone}}</span>
            </div>
            <div>
                <span>Accompanying Piece</span>
                <span>{{hospitalisation.accompanying.piece}}</span>
            </div>
        </div>
        <div class="bg-white p-4 rounded-md shadow-md mt-4">
            <h4>{{ __('Prescriptions')}}</h4>
            <div class="bg-white overflow-x-auto" v-for="consultation in hospitalisation.consultations">
                <div class="flex flex-col md:flex-row space-x-3 md:items-center">
                    <span class="flex flex-row items-center space-x-3">
                        <span> {{ __('Consultation')}}</span>
                        <span class="text-muted text-sm">
                              ({{consultation.identifier}})
                        </span>
                    </span>
                    <span class="flex flex-row items-center space-x-3">
                            <span class="">
                                {{__('Of')}}
                            </span>
                           <span>{{ moment(consultation.created_at).locale('fr').format("D MMM YYYY")}}</span>
                    </span>
                </div>

                <table class="w-full whitespace-nowrap text-base">
                    <tr class="text-left font-bold">
                        <th class="pb-2 pt-3 px-6">{{__('Label')}}</th>
                        <th class="pb-2 pt-3 px-6">{{__('Quantity')}}</th>
                        <th class="pb-2 pt-3 px-6">{{__('Price')}}</th>
                        <th class="pb-2 pt-3 px-6">{{__('Status Payment')}}</th>
                    </tr>
                    <tr v-for="prescription in consultation.prescriptions" :key="prescription.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm text-black">
                        <td class="border-t ">
                            <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" >
                              <span>{{ prescription.label}}</span>
                            </Link>
                        </td>
                        <td class="border-t w-px">
                            <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" >
                                {{ prescription.quantity }}
                            </Link>
                        </td>
                        <td class="border-t w-px">
                            <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" >
                                {{ prescription.price }}
                            </Link>
                        </td>
                        <td class="border-t w-px">
                            <Link class="flex items-center justify-center px-6 py-2 text-black" >
                                <i class="fa-duotone fa-circle-check text-green-500 text-xl" v-if="prescription.status_payment"></i>
                                <i class="fa-duotone fa-circle-xmark text-red-500 text-xl" v-else></i>
                            </Link>
                        </td>
                    </tr>
                    <tr v-if="consultation.prescriptions.length === 0">
                        <td class="px-6 py-4 border-t" colspan="9">{{__('No prescription found.')}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3";
    import moment from 'moment';
    export default {
        name: "End",
        components: {PageHeader, HeaderTitle, Link,moment},
        computed: { moment: () => moment },
        props: {
            title:String,
            hospitalisation:Object
        },
        methods: {
        },

    }
</script>

<style scoped>

</style>
