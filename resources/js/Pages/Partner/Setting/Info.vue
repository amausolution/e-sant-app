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
    <form @submit.prevent="updateInfo">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{__('Company Name')}} <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" v-model="form.title">
                    <span class="help-block text-xs" v-if="form.errors.title">{{form.errors.title}}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{__('Contact Person')}}  <span class="text-danger">*</span></label>
                    <input class="form-control " type="text" v-model="form.responsible">
                    <span class="help-block text-xs" v-if="form.errors.responsible">{{form.errors.responsible}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{__('Keyword')}}</label>
                    <input class="form-control" type="text" v-model="form.keyword">
                    <span class="help-block text-xs" v-if="form.errors.keyword">{{form.errors.keyword}}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{__('Description')}}</label>
                    <input class="form-control " type="text" v-model="form.description">
                    <span class="help-block text-xs" v-if="form.errors.description">{{form.errors.description}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label>{{__('Address')}}  <span class="text-danger">*</span></label>
                    <input class="form-control " v-model="form.address" type="text">
                    <span class="help-block text-xs" v-if="form.errors.address">{{form.errors.address}}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="form-group">
                    <label>{{__('Country')}}  <span class="text-danger">*</span></label>
                    <select class="form-control select" v-model="form.country">
                        <option v-for="(country,index) in countries" :value="index">{{country}}</option>
                    </select>
                    <span class="help-block text-xs" v-if="form.errors.country">{{form.errors.country}}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="form-group">
                    <label>{{__('City')}}  <span class="text-danger">*</span></label>
                    <select class="form-control select" v-model="form.region">
                        <option v-for="(city,index) in regions" :value="index">{{city}}</option>
                    </select>
                    <span class="help-block text-xs" v-if="form.errors.region">{{form.errors.region}}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="form-group">
                    <label>{{__('Department')}}  <span class="text-danger">*</span></label>
                    <select class="form-control select" v-model="form.department">
                        <option v-for="(department,index) in departments" :value="index">{{department}}</option>
                    </select>
                    <span class="help-block text-xs" v-if="form.errors.department">{{form.errors.department}}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <div class="form-group">
                    <label>{{__('Postal Code')}}</label>
                    <input class="form-control"  type="text" v-model="form.post_code">
                    <span class="help-block text-xs" v-if="form.errors.post_code">{{form.errors.post_code}}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="form-group">
                    <label>{{__('Phone Number')}}  <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" v-model="form.office_phone">
                    <span class="help-block text-xs" v-if="form.errors.office_phone">{{form.errors.office_phone}}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="form-group">
                    <label>{{('Mobile Number')}}</label>
                    <input class="form-control" v-model="form.phone" type="text">
                    <span class="help-block text-xs" v-if="form.errors.phone">{{form.errors.phone}}</span>
                </div>
            </div>
            <div class="col-sm-6 col-md-6 col-lg-4">
                <div class="form-group">
                    <label>{{__('Fax')}}</label>
                    <input class="form-control" v-model="form.fax" type="text">
                    <span class="help-block text-xs" v-if="form.errors.fax">{{form.errors.fax}}</span>
                </div>
            </div>
        </div>
        <div class="row" v-if="setting.type===2">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="wh">{{__('Warehouse')}}</label>
                    <textarea class="form-control" name="" id="wh"  rows="3" v-model="form.warehouse"></textarea>
                    <span class="help-block textxs" v-if="form.errors.warehouse">{{form.errors.warehouse}}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{__('Email')}}  <span class="text-danger">*</span></label>
                    <input class="form-control" v-model="form.email" type="text">
                    <span class="help-block textxs" v-if="form.errors.email">{{form.errors.email}}</span>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{__('Website Url')}}</label>
                    <span class="form-control">{{ form.domain}}</span>
                </div>
            </div>
        </div>
        <div class="submit-section">
            <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">{{__('Update Info')}}</loading-button>
        </div>
    </form>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import LoadingButton from "@/Shared/LoadingButton";
    import NaveBar from "@/Shared/NaveBar";
    export default {
        name: "Info",
        components: {NaveBar, LoadingButton, PageHeader, HeaderTitle, Link},
        props: {
            setting: Object,
            countries: Object,
            regions: Object,
            departments: Object,
            timezones: Object,
            currencies: Object,
            title: String
        },
        setup(props){
         const form = useForm({
             'title':props.setting.title??'',
             'description': props.setting.description,
             'keyword': props.setting.keyword,
             'phone': props.setting.phone,
             'email': props.setting.email,
             'fax': props.setting.fax,
             'address': props.setting.address,
             'region': props.setting.region,
             'department': props.setting.department,
             'district': props.setting.district,
             'warehouse': props.setting.warehouse,
             'type': props.setting.type,
             'country': props.setting.country,
             'responsible': props.setting.responsible,
             'office_phone': props.setting.office_phone,
             'post_code': props.setting.post_code,
             'domain': props.setting.domain,
         })
            return {form}
        },
        methods: {
            updateInfo(){
                this.form.put(route('partner.update'),{})
            }
        }

    }
</script>

<style scoped>

</style>
