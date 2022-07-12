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
                    <Link as="button" type="button" :href="route('live.index')" class="bg-sky-400 shadow-md hover:bg-sky-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-arrow-left-long"></i>
                        {{__('Go Back')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white p-2 p-sm-2 p-lg-4 p-md-4 rounded-md shadow-md">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">{{__('Patient ID')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating">
                        <label class="focus-label">{{__('P')}}</label>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3";
    export default {
        name: "Create",
        components: {PageHeader, HeaderTitle, Link}
    }
</script>

<style scoped>

</style>
