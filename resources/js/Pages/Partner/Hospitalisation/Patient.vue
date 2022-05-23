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
                    <Link :href="route('hospitalized.index')" class="btn add-btn"><i class="fa-solid fa-hospital-user"></i> {{__('List Hospitalized')}}</Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Avatar')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Name')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Patient Id')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Phone urgency')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Doctor')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Gender')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                </tr>
                <tr v-for="hospitalisation in hospitalisations.data" :key="hospitalisation.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t w-px">
                        <Link class="flex items-center px-6 py-2 w-20" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            <img class="w-20 object-fill" :src="hospitalisation.avatar" :alt="hospitalisation.name" />
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            {{ hospitalisation.name }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            {{ hospitalisation.patientId }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            {{ hospitalisation.phone_urgency }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            {{ hospitalisation.doctor}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            {{ hospitalisation.gender}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})">
                            <span class="badge bg-inverse-success">{{__('New')}}</span>
                        </Link>
                    </td>
                    <td class="border-t">
                        <div class="flex flex-row space-x-2">
                            <Link class="flex items-center py-2 px-2 text-2xl" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})"
                                  as="button" type="button" :title="__('Hospitalized')">
                                <i class="fa-duotone fa-bed-pulse text-orange-500"></i>
                            </Link>
                            <Link class="flex items-center py-2 text-2xl" tabindex="-1" :href="route('hospitalisation.hospitalized.edit',{id: hospitalisation.id})"
                                  method="delete" as="button" type="button" :title="__('End Hospitalisation')">
                                <i class="fa-duotone fa-arrow-right-from-bracket text-sky-500"></i>
                            </Link>
                        </div>

                    </td>
                </tr>
                <tr v-if="hospitalisations.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No hospitalisation found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="hospitalisations.links" />
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import Pagination from "@/Shared/Pagination";
    import {Link} from "@inertiajs/inertia-vue3"
    export default {
        name: "patient",
        components: {Pagination, PageHeader, HeaderTitle, Link},
        props: {
            hospitalisations:Object,
            title:String
        }
    }
</script>

<style scoped>

</style>
