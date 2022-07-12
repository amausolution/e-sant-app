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
            </div>
        </page-header>
        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input v-model="form.search" type="text" class="form-control floating">
                    <label class="focus-label">{{__('Number analyse')}}</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input v-model="form.matricule" type="text" class="form-control floating">
                    <label class="focus-label">{{__('Identifier Patient')}}</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <button @click="resetForm" :title="__('Reset')"
                    class="px-6 py-2 rounded bg-red-400 hover:bg-red-500 text-red-100"
                >
                    <i class="fa-regular fa-rotate-right"></i>
                </button>
            </div>
        </div>
        <!-- Search Filter -->

        <div class="row" v-if="form.matricule  || form.search ">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table class="table table-striped custom-table">
                        <thead>
                        <tr>
                            <th>{{__('Analyse Id')}}</th>
                            <th>{{__('Analyse')}}</th>
                            <th>{{__('Doctor')}}</th>
                            <th>{{__('Patient')}}</th>
                            <th>{{__('Date')}}</th>
                            <th>{{__('Status')}}</th>
                            <th class="text-right">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>

                        <tr v-for="analyse in analyses.data" >
                            <template v-if="type_partner.includes(analyse.id_type)">
                                <td>{{analyse.analyse_id}}</td>
                                <td>
                                    {{analyse.analyse}}
                                </td>
                                <td>
                                    {{ analyse.doctor}}
                                </td>
                                <td>{{analyse.name}}</td>
                                <td>{{analyse.date}}</td>

                                <td>
                                    <template v-if="type_partner.includes(analyse.id_type)">
                                        <span v-if="analyse.status" class="px-2 py-1 shadow-sm rounded bg-emerald-100 hover:bg-emerald-500 text-emerald-500">{{__('Done')}}</span>
                                        <span v-else class="px-6 py-1 shadow-sm rounded bg-sky-200 hover:bg-sky-500 text-sky-600">{{__('New')}}</span>

                                    </template>
                                </td>
                                <td class="text-right">
                                    <Link v-if="type_partner.includes(analyse.id_type)"  class="px-3 mr-2 py-1 text-base rounded shadow-md bg-sky-300 hover:bg-sky-500 text-slate-800" :href="route('analyse.show',{id: analyse.id})">
                                        <i class="fa-regular fa-eye"></i>
                                    </Link>
                                    <Link v-if="type_partner.includes(analyse.id_type)"  class="px-3 py-1 text-base rounded shadow-md bg-cyan-100 hover:bg-cyan-200 text-cyan-500" :href="route('analyse.edit',{id: analyse.id})">
                                        <i class="fa-duotone fa-microscope"></i>
                                    </Link>
                                </td>
                            </template>
                            <template v-else>
                                <td colspan="5">{{__('Test Not Available Here')}}</td>
                            </template>


                        </tr>
                        <tr v-if="analyses.data.length === 0">
                            <td class="px-6 py-18 border-t" colspan="5">
                                <p>
                                    {{__('No Analyses found.')}}
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <pagination class="mt-6" :links="analyses.links" />
            </div>
        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3"
    import throttle from "lodash/throttle";
    import pickBy from "lodash/pickBy";
    import mapValues from "lodash/mapValues";
    import Pagination from "@/Shared/Pagination";
    export default {
        name: "Index",
        components: {Pagination, PageHeader, HeaderTitle, Link},
        props: {
            title:String,
            analyses: Object,
            filters: Object,
            type_partner:Array,
        },
        data() {
            return {
                form: {
                    search: this.filters.search,
                    identifier: this.filters.matricule,
                    // trashed: this.filters.gender,
                },
            }
        },
        watch: {
            form: {
                deep: true,
                handler: throttle(function () {
                    this.$inertia.get(route('analyse.index'), pickBy(this.form), { preserveState: true })
                }, 150),
            },
        },
        methods: {
            resetForm() {
                this.form = mapValues(this.form, () => null)
            },

        },
    }
</script>

<style scoped>

</style>
