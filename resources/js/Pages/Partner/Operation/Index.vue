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
                    <Link as="button" type="button" :href="route('operation.create')" class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-clipboard-medical"></i>
                        {{__('Programming New')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.identifier">
                    <label class="focus-label">{{__('Patient ID')}}</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.search">
                    <label class="focus-label">{{__('Patient First Name')}}</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <select class="form-control" v-model="form.gender">
                    <option selected disabled value="">{{__('Select Gender')}}</option>
                    <option v-for="gender in genders" :value="gender.id">{{gender.title}}</option>
                </select>
            </div>
        </div>

        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Name')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Patient Id')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Gender')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Type Operation')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Programming')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Action')}}</th>
                </tr>
                <tr v-for="operation in operations.data" :key="operation.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm text-black">
                    <td class="border-t w">
                        <Link class="flex items-center px-2 py-2 flex-row text-black" tabindex="-1" :href="route('operation.edit',{id: operation.id})">
                            <span class="flex flex-col text-sm">
                                    <span>{{ operation.patient.name }}</span>
                                   <span>{{ operation.patient.phone }}</span>
                              </span>
                        </Link>
                    </td>

                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('operation.edit',{id: operation.id})">
                            {{ operation.patient.doc_number }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('operation.edit',{id: operation.id})">
                            {{ operation.patient.gender}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('operation.edit',{id: operation.id})">
                            {{ operation.type}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('operation.edit',{id: operation.id})">
                            {{ operation.date_programming}}
                        </Link>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex flex-row">
                            <Link class="flex items-center py-2 px-2 text-2xl text-orange-400 hover:text-orange-600"
                                  tabindex="-1" :href="route('operation.edit',{id: operation.id})"
                            >
                                <i class="fa-duotone fa-money-check-pen"></i>
                            </Link>
                            <button class="flex items-center py-2 px-2 text-2xl text-red-400 hover:text-red-600" tabindex="-1" >
                                <i class="fa-duotone fa-trash-can"></i>
                            </button>
                        </div>

                    </td>
                </tr>
                <tr v-if="operations.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No operation found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="operations.links" />
        <!-- Search Filter -->
    </div>
</template>

<script>
    import Pagination from "@/Shared/Pagination";
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    export default {
        name: "Index",
        components: {HeaderTitle, PageHeader, Pagination, Link},
        props:{
            title:String,
            operations:Object
        },
        data(){
            return {
                form: useForm({})
            }
        }
    }
</script>

<style scoped>

</style>
