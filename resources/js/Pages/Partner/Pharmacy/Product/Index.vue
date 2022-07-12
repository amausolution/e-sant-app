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
                    <Link :href="route('medicine.create')" class="bg-sky-400 px-6 py-2 text-white shadow-md rounded-md hover:bg-sky-600"><i class="fa fa-plus"></i> {{__('Add Medicines')}}</Link>
                </div>
            </div>
        </page-header>
        <div class="row filter-row">
            <div class="col-sm-6">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.search">
                    <label class="focus-label">{{__('Search by name')}}</label>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Name')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('category')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Brande')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Stock')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Sold')}}</th>
                </tr>
                <tr v-for="medicine in products.data" :key="medicine.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm text-black">
                    <td class="border-t w">
                        <Link class="flex items-center px-2 py-2 flex-row text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})">
                             <span>{{ medicine.name }}</span>
                             <span>{{medicine.bar_code}}</span>
                        </Link>
                    </td>

                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})">
                            {{ medicine.category }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})">
                            {{ medicine.brand }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})">
                            {{ medicine.stock}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})">
                            {{ medicine.sale }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})">
                            {{ medicine.status}}
                        </Link>
                    </td>
                    <td class="border-t w-px">
                        <div class="flex flex-row space-x-2">
                            <Link class="flex items-center py-2 px-2 text-2xl text-black" tabindex="-1" :href="route('medicine.edit',{id: medicine.id})"
                            >
                                <i class="fa-duotone fa-chevron-right"></i>
                            </Link>
                        </div>

                    </td>
                </tr>
                <tr v-if="products.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No medicine found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="products.links" />

    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3";
    import throttle from "lodash/throttle";
    import pickBy from "lodash/pickBy";
    import mapValues from "lodash/mapValues";
    import Pagination from "@/Shared/Pagination";
    export default {
        name: "Index",
        components: {Pagination, PageHeader, HeaderTitle, Link},
        props: {
            title: String,
            filters: Object,
            products: Object
        },
        data() {
            return {
                form: {
                    search: this.filters.search,
                },
            }
        },
        watch: {
            form: {
                deep: true,
                handler: throttle(function () {
                    this.$inertia.get(route('medicine.index'), pickBy(this.form), { preserveState: true })
                }, 150),
            },
        },
        methods: {
            reset() {
                this.form = mapValues(this.form, () => null)
            },

        },
    }
</script>

<style scoped>

</style>
