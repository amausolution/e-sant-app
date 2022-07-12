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
                    <Link :href="route('patients.index')" class="btn add-btn" data-toggle="modal" data-target="#add_client">
                        <i class="fa-duotone fa-arrow-left"></i> {{__('Go Back')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white shadow-md rounded row p-2">

                <div class="col-md-4 col-lg-4 col-12  border-b md:border-r lg:border-r md:border-b-0 lg:border-b-0">
                    <span class="mb-4">{{__('Information Patient')}}</span>
                    <div class="flex flex-col space-y-2 mt-4">
                        <div class="flex flex-row space-x-4">
                            <span class="text-muted" v-if="patient.live_cycle==1">{{__('Name')}}</span>
                            <span class="text-muted" v-else>{{__('Late')}}</span>
                            <span>{{patient.name}}</span>
                        </div>
                        <div class="flex flex-row space-x-4">
                            <span class="text-muted">{{__('Phone')}}</span>
                            <span>{{patient.phone}}</span>
                        </div>
                        <div class="flex flex-row space-x-4">
                            <span class="text-muted">{{__('Address')}}</span>
                            <span>{{patient.address}}</span>
                        </div>
                        <div class="flex flex-row space-x-4">
                            <span class="text-muted">{{__('Identifier')}}</span>
                            <span>{{patient.doc_number}}</span>
                        </div>
                        <div class="flex flex-row space-x-4">
                        <span class="text-muted">{{__('Group Blood')}}</span>
                        <span>{{patient.group_blood}}</span>
                    </div>
                    </div>
                </div>
                <div class="col-md-4 col-lg-4 col-12 border-b md:border-r lg:border-r md:border-b-0 lg:border-b-0">
                    <span class="mb-4">{{__('Pathology')}}</span>
                    <ul class="mt-4">
                        <li v-for="pathology in patient.pathologies">
                            <div class="relative flex flex-row justify-between mb-2">
                                <span class="text-muted">{{ __('Allergy')}}</span>
                                <span>{{ pathology.pathology}}</span>
                                <span class="text-xs text-muted right-2">{{pathology.date}}</span>
                            </div>
                            <div class="flex flex-col space-y-2">
                                <div class="flex flex-row space-x-6">
                                    <span class="text-muted">{{__('Result')}}:</span>
                                    <span>{{pathology.level}}</span>
                                </div>
                                <div class="flex flex-row space-x-6">
                                    <span class="text-muted">{{__('Observation')}}:</span>
                                    <span>{{pathology.observation}}</span>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            <div class="col-md-4 col-lg-4 col-12">
                <span>{{__('Allergy')}}</span>
                <ul class="mt-4">
                    <li v-for="allergy in patient.allergies" class=" ">
                        <div class="relative flex flex-row justify-between mb-2">
                            <span class="text-muted">{{ __('Allergy')}}</span>
                            <span>{{ allergy.allergy}}</span>
                            <span class="text-xs text-muted right-2">{{allergy.date}}</span>
                        </div>
                        <div class="flex flex-col space-y-2">
                            <div class="flex flex-row space-x-6">
                                <span class="text-muted">{{__('Result')}}:</span>
                                <span>{{allergy.result}}</span>
                            </div>
                            <div class="flex flex-row space-x-6">
                                <span class="text-muted">{{__('Observation')}}:</span>
                                <span>{{allergy.observation}}</span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>

        <div class="bg-white rounded-md shadow overflow-x-auto row mt-4">
            <span class="p-3">{{__('List Consultations')}}</span>
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Identifier')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Date')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('View at')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Result')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                </tr>
                <tr v-for="consultation in consultations.data"  class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm text-black" :key="consultation.id">
                    <td class="border-t w-px">
                        <Link class="flex items-center px-2 py-2 flex-row text-black" tabindex="-1" :href="route('patient.consultation.show',{id: consultation.id})">
                         {{consultation.identifier}}
                        </Link>
                    </td>

                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patient.consultation.show',{id: consultation.id})">
                            {{ consultation.date }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patient.consultation.show',{id: consultation.id})">
                            {{ consultation.view }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patient.consultation.show',{id: consultation.id})">
                            {{ consultation.result}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patient.consultation.show',{id: consultation.id})">
                           <span class="px-2 py-1 rounded-md shadow-sm text-xs bg-green-300" v-if="consultation.status==1">{{ __('View')}}</span>
                           <span class="px-2 py-1 rounded-md shadow-sm text-xs bg-sky-300" v-else-if="consultation.status==0">{{__('New')}}</span>
                           <span class="px-2 py-1 rounded-md shadow-sm text-xs bg-orange-300" v-else>{{__('Cancel')}}</span>
                        </Link>
                    </td>

                    <td class="border-t w-px">
                        <div class="flex flex-row space-x-2">
                            <Link class="flex items-center py-2 px-2 text-2xl text-black" tabindex="-1" :href="route('patient.consultation.show',{id: consultation.id})"
                            >
                                <i class="fa-duotone fa-chevron-right"></i>
                            </Link>
                        </div>

                    </td>
                </tr>
                <tr v-if="consultations.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No consultation found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="consultations.links" />
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3";
    import Pagination from "@/Shared/Pagination";
    export default {
        name: "Show",
        components: {Pagination, PageHeader, HeaderTitle, Link},
        props: {
            title: String,
            patient: Object,
            consultations: Object
        }
    }
</script>

<style scoped>

</style>
