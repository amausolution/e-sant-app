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
            <div class="col-sm-6">
                <div class="card">
                    <div class="card-body">

                        <table class="table table-striped table-border">
                            <tbody>
                            <tr>
                                <td>{{__('Active Laboratory')}}</td>
                                <td class="text-right">
                                    <form>
                                        <input type="checkbox" v-model="form.value"
                                               true-value="1"
                                               false-value="0"
                                               @change="updateConfig($event,'LABO')"
                                        >
                                    </form>

                                </td>
                            </tr>
                            <tr>
                                <td>{{__('Number Ambulance')}}</td>
                                <td class="text-right">
                                    {{total.nbr_ambulance}}
                                </td>
                            </tr>
                            <tr>
                                <td>{{__('Number Hospitalization Rooms')}}</td>
                                <td class="text-right">
                                    {{total.rooms}}
                                </td>
                            </tr>
                            <tr>
                                <td>{{__('Number Operating Room')}}</td>
                                <td class="text-right">
                                    {{total.operatingRooms}}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="grid col-auto gap-3">
                    <button
                        class="px-6 py-2 text-sm rounded shadow bg-slate-100 hover:bg-slate-200 text-slate-500"
                    >
                        {{__('Add Ambulance')}}
                    </button>
                    <button
                        class="px-6 py-2 text-sm text-orange-500 bg-orange-100 rounded shadow hover:bg-orange-200"
                    >
                        {{__('Add Operating Room')}}
                    </button>
                    <button
                        class="px-6 py-2 text-sm rounded shadow bg-emerald-100 hover:bg-emerald-200 text-emerald-500"
                    >
                        Button
                    </button>
                    <button
                        class="px-6 py-2 text-sm rounded shadow bg-cyan-100 hover:bg-cyan-200 text-cyan-500"
                    >
                        Button
                    </button>
                    <button
                        class="px-6 py-2 text-sm rounded shadow bg-violet-100 hover:bg-violet-200 text-violet-500"
                    >
                        Button
                    </button>
                    <button
                        class="px-6 py-2 text-sm rounded shadow bg-rose-100 hover:bg-rose-200 text-rose-500"
                    >
                        Button
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm, usePage} from "@inertiajs/inertia-vue3";
    import {computed} from "vue";
    export default {
        name: "Settings",
        components: {PageHeader, HeaderTitle, Link},
        props: {
            setting: Object,
            total: Object,
            title: String
        },
        setup(props){
            return{
                config:  computed(() => usePage().props.value.config),
                form:  useForm({
                    key:'',
                    value:computed(() => usePage().props.value.config.LABO)
                    //'mail_encrup'
                })
            }
        },
        methods: {
            updateConfig (event, key) {
                 this.form.transform((data) => ({
                     ...data,
                     value:event.target.checked?1:0,
                     key:key
                 })).post(route('update.config'))
            }
        }
    }
</script>

<style scoped>

</style>
