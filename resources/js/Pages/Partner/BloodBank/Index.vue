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
                <div class="col-auto float-right ml-auto space-x-6">
                    <Link as="button" type="button" :href="route('blood_bank.create')" class="bg-orange-400 shadow-md hover:bg-orange-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-syringe"></i>
                        {{__('Blood Donation')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white p-2 p-sm-2 p-lg-4 p-md-4 rounded-md shadow-md mb-3">
            <div class="row filter-row">
                <div class="col-12 col-md-7">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">{{__('Search By Type Blood')}}</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Blood ID')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Type')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                </tr>
                <tr v-for="blood in bloods.data" :key="blood.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('blood_bank.edit',{id: blood.id})">
                            {{ blood.id }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('blood_bank.edit',{id: blood.id})">
                            {{ blood.type }}
                        </Link>
                    </td>

                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('blood_bank.edit',{id: blood.id})">
                            <template v-if="blood.status">
                                <i class="fa fa-check text-success"></i>
                            </template>
                            <template v-else>
                                <i class="fa fa-times text-danger"></i>
                            </template>
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-2xl" tabindex="-1" :href="route('blood_bank.destroy',{id: blood.id})"
                              method="delete" as="button" type="button" :title="__('Destroy')"
                        >
                            <i class="la la-trash text-red-400"></i>
                        </Link>
                    </td>
                </tr>
                <tr v-if="bloods.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No Blood found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="bloods.links" />
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3";
    import Pagination from "@/Shared/Pagination";
    export default {
        name: "Index",
        components: {Pagination, PageHeader, HeaderTitle,Link},
        props:{
            title:String,
            bloods:Object
        }
    }
</script>

<style scoped>

</style>
