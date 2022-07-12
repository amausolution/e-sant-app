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
                <div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-5 gap-2 float-right ml-auto">
                    <NaveBar />
                </div>
            </div>
        </page-header>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import NaveBar from "@/Shared/NaveBar";
    import {Link} from "@inertiajs/inertia-vue3";
    export default {
        name: "Localisation",
        components: {NaveBar, PageHeader, HeaderTitle, Link}
    }
</script>

<style scoped>

</style>
