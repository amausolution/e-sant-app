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

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Bordered Table</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                <tr>
                                    <th>{{__('Plugin Name')}}</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <i class="fa-duotone fa-microscope text-4xl mr-4"></i>{{__('Laboratory')}}</td>
                                    <td class="w-px">
                                        <template v-if="!$page.props.config.LABO">
                                            <Link :href="route('plugin.install')" method="post" as="button" type="button"  class="ml-1 px-3 py-1 text-lg rounded shadow-sm bg-sky-100 hover:bg-sky-200 text-sky-500"
                                            ><i class="fa-duotone fa-plus text-sky-800"></i>
                                            </Link>
                                        </template>
                                        <template v-else>
                                                <Link v-if="$page.props.config.LABO == 1" method="POST" as="button" type="button" :href="route('plugin.disable')" :data="{ key: 'LABO' }" class="ml-1 px-3 py-1 text-lg text-orange-500 bg-orange-100 rounded shadow-sm hover:bg-orange-200"
                                                ><i class="fa-duotone fa-power-off"></i>
                                                </Link>
                                                <Link v-else method="POST" as="button" type="button" :href="route('plugin.enable')" :data="{ key: 'LABO' }" class="ml-1 px-3 py-1 text-lg text-sky-500 bg-sky-100 rounded shadow-sm hover:bg-sky-200"
                                                ><i class="fa-duotone fa-paper-plane-top"></i>
                                                </Link>
                                                <Link as="button" type="button" :href="route('lab.setting')" class="ml-1 px-3 py-1 text-lg rounded shadow-sm bg-emerald-100 hover:bg-emerald-200 text-emerald-500"
                                                > <i class="fa-duotone fa-gear"></i>
                                                </Link>
                                                <Link as="button" type="button" href="" class="ml-1 px-3 py-1 text-lg rounded shadow-sm bg-rose-400 hover:bg-rose-500 text-rose-100"
                                                > <i class="fa-duotone fa-xmark text-rose-100"></i>
                                                </Link>
                                        </template>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link} from "@inertiajs/inertia-vue3";
    export default {
        name: "Plugins",
        components: {HeaderTitle, PageHeader, Link},
        props: {
            title: String
        }
    }
</script>

<style scoped>

</style>
