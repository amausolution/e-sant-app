<template>
    <HeaderTitle :title="title" />
    <div class="content container-fluid">
        <page-header>
            <div class="row">
                <div class="col-sm-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('partner.home')">{{__('Dashboard')}}</Link>
                        </li>
                        <li class="breadcrumb-item active">{{title}}</li>
                    </ul>
                </div>
            </div>
        </page-header>

<!--
        <div class="flex items-center justify-between mb-6">
            <search-filter v-model="form.search" class="mr-4 w-full max-w-lg" @reset="reset">
                <label class="block mt-4 text-gray-700">{{__('Gender')}}:</label>
                <select v-model="form.gender" class="form-select mt-1 w-full">
                    <option :value="null" />
                    <option value="1">{{__('Male')}}</option>
                    <option value="0">{{ __('Female')}}</option>
                </select>
            </search-filter>
        </div>-->
        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.identifier">
                    <label class="focus-label">Employee ID</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.search">
                    <label class="focus-label">Employee Name</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus select-focus">
                    <select class="form-control floating" v-model="form.job">
                        <option disabled>Select Profession</option>
                        <option v-for="option in professions" v-bind:value="option.id">
                            {{ option.title }}
                        </option>
                    </select>
                    <label class="focus-label">Profession</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3 d-flex items-baseline justify-content-between">
                <button @click="reset()" class="btn btn-warning text-sm d-flex items-center" :title="__('Reset Search')">
                    <span class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </span>
                   <span class="d-flex d-md-none d-lg-flex">{{__('Reset')}}</span>
                </button>
                <Link :href="route('doctor.create')" class="btn btn-success pull-right text-sm d-flex items-center" :title="__('Add new')">
                    <span class="">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 md:h-5 md:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                    <span class="d-flex d-md-none d-lg-flex"> {{ __('Add New')}}</span>
                </Link>
            </div>
        </div>
        <!-- Search Filter -->
        <div class="row staff-grid-row">
            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3" v-for="doctor in doctors.data" :key="doctor.id">

              <div class="profile-widget">
            <div class="profile-img">
                <Link :href="route('doctor.show',{id: doctor.id})" class="avatar">
                    <img :src="doctor.avatar" :alt="doctor.name" class="">
                </Link>
            </div>
            <div class="dropdown profile-action">
                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <Link class="dropdown-item" :href="route('doctor.edit',{id: doctor.id})"><i class="fa fa-pencil m-r-5"></i>
                        {{__('Edit')}}</Link>
                    <Link :href="route('doctor.destroy',{id:doctor.id})" method="DELETE" as="button" type="button" class="dropdown-item delete-btn"><i class="fa fa-trash-o m-r-5"></i>
                        {{__('Delete')}}
                    </Link>
                </div>
            </div>
            <h4 class="user-name m-t-10 mb-0 text-ellipsis">
                <Link :href="route('doctor.show',{id: doctor.id})">{{doctor.name}}</Link>
            </h4>
           <div class="small text-muted">{{doctor.job}}</div>
           <div class="small text-muted">{{doctor.matricule}}</div>
        </div>

           </div>

        </div>
        <pagination class="mt-6" :links="doctors.links" />
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import SearchFilter from "@/Shared/SearchFilter";
    import throttle from "lodash/throttle";
    import pickBy from "lodash/pickBy";
    import mapValues from "lodash/mapValues";
    import { Head, Link } from '@inertiajs/inertia-vue3'
    import Pagination from "@/Shared/Pagination";

    //   import Layout from "@/Shared/Layout";
    export default {
        name: "Index",
        components: {Pagination, SearchFilter, PageHeader, HeaderTitle,Link},
        props: {
            doctors: Array,
            title: String,
            asset: String,
            filters: Object,
            professions:Object
        },
      //  layout: Layout,
        data() {
            return {
                form: {
                    search: this.filters.search,
                    identifier: this.filters.matricule,
                    job: this.filters.job,
                   // trashed: this.filters.gender,
                },
            }
        },
        watch: {
            form: {
                deep: true,
                handler: throttle(function () {
                    this.$inertia.get(route('doctor.index'), pickBy(this.form), { preserveState: true })
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
