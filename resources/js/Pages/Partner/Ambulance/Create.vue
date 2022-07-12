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
                    <Link :href="route('ambulance.index')" class="bg-sky-400 px-6 py-2 text-white shadow-md rounded-md hover:bg-sky-600"><i class="fa-duotone fa-arrow-left-long"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>
        <form class="row" @submit.prevent="submitAmbulance">
            <div class="form-group col-sm-6">
                <label for="">{{__('Brand')}}</label>
                <input type="text" class="form-control" v-model="form.brand">
            </div>
            <div class="form-group col-sm-6">
                <label for="">{{__('Car Id')}}</label>
                <input type="text" class="form-control" v-model="form.matricule">
            </div>
            <div class="form-group col-sm-6">
                <label for="">{{__('Year')}}</label>
                <input type="text" class="form-control" v-model="form.year">

            </div>
            <div class="form-group col-sm-6">
                <label for="">{{__('Class')}}</label>
                <select name="" id="" v-model="form.class" class="form-control">
                    <option>{{__('Select One')}}</option>
                    <option :value="option" v-for="option in ['A','B','C']" :key="option">{{option}}</option>
                </select>
            </div>
            <div class="form-group col-sm-6">
                <label for="">{{__('Giver')}}</label>
                <input type="text" class="form-control" v-model="form.giver">
            </div>
            <div class="form-group col-sm-6">
                <label for="">{{__('acquisition_date')}}</label>
                <v-date-picker v-model="date">
                    <template v-slot="{ inputValue, inputEvents }">
                        <input
                            class="bg-white border px-2 py-1 rounded form-control"
                            :value="inputValue"
                            v-on="inputEvents"
                        />
                    </template>
                </v-date-picker>
            </div>
            <div class="flex justify-end">
                <loading-button :loading="form.processing"
                                class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300"
                                type="submit">
                    {{__('Submit form')}}
                </loading-button>
            </div>

        </form>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "Create",
        components: {LoadingButton, PageHeader, HeaderTitle, Link},
        data(){
            return{
                form: useForm({
                    brand:'',
                    year:'',
                    matricule:'',
                    giver:'',
                    class:'',
                    acquisition_date:''
                }),
                date: new Date(),
            }
        },
        methods: {
            submitAmbulance (){
                this.form.transform( (data) => ({
                    ...data,
                    acquisition_date: this.date
                })).post(route('ambulance.store'))
            }
        }
    }
</script>

<style scoped>

</style>
