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
            </div>
        </page-header>
        <div class="bg-white rounded-md shadow-md p-4">
            <div v-html="a"></div>
            <form @submit.prevent="submitForm" class="">
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="" class="form-label">{{__('Name')}} <span class="required">*</span> </label>
                        <input type="text" class="form-control" v-model="form.name">
                        <span class="help-block text-xs" v-if="form.errors.name">{{form.errors.name}}</span>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="" class="form-label">{{__('Select Category')}}</label>
                        <multiselect  tag-placeholder="Add this as new tag"
                                      :placeholder="__('Select one or more')" label="name" track-by="id"
                                      :select-label="__('type enter to select')"
                                      :options="categories" :multiple="true"
                                      v-model="form.category"
                                      :taggable="true" @tag="addTag">
                        </multiselect>
                        <span class="help-block text-xs" v-if="form.errors.category">{{form.errors.category}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-sm-6">
                        <label for="" class="form-label">{{__('Brand')}}</label>
                        <multiselect v-model="form.brand" :options="brands"
                                     :custom-label="nameWithLang"
                                     :select-label="__('type enter to select')"
                                     :placeholder="__('Select one')"
                                     label="name" track-by="id">
                        </multiselect>
                        <span class="help-block text-xs" v-if="form.errors.brand">{{form.errors.brand}}</span>
                    </div>
                    <div class="form-group col-sm-6">
                        <label for="" class="form-label">{{ __('Quantity') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" v-model="form.quantity">
                        <span class="help-block text-xs" v-if="form.errors.quantity">{{form.errors.quantity}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-3 col-lg-3">
                        <label for="" class="form-label">{{ __('Price') }} <span class="required">*</span></label>
                        <input type="text" class="form-control" v-model="form.price">
                        <span class="help-block text-xs" v-if="form.errors.price">{{form.errors.price}}</span>
                    </div>
                    <div class="form-group col-md-3 col-lg-3">
                        <label for="" class="form-label">{{ __('Cost') }}</label>
                        <input type="text" class="form-control" v-model="form.cost">
                        <span class="help-block text-xs" v-if="form.errors.cost">{{form.errors.cost}}</span>
                    </div>
                    <div class="form-group col-md-2">
                        <label for="" class="form-label">{{ __('Can Sale Detail') }}</label>
                        <input type="checkbox" class="w-6 h-6 bg-cyan-400" v-model="form.can_detail">
                    </div>
                    <div class="form-group col-md-3 col-lg-3" v-if="form.can_detail">
                        <label for="" class="form-label">{{ __('Detail Price') }} <span class="required">*</span></label>
                        <input :type="form.can_detail?'text':'hidden'" class="form-control" v-model="form.detail_price">
                        <span class="help-block text-xs" v-if="form.errors.detail_price">{{form.errors.detail_price}}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="" class="form-label">{{__('Description')}}</label>
                        <textarea cols="3" rows="3" class="form-control" v-model="form.description"></textarea>
                        <span class="help-block text-xs" v-if="form.errors.description">{{form.errors.description}}</span>
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="" class="form-label">{{__('Side Effects')}}</label>
                        <textarea cols="3" rows="3" class="form-control" v-model="form.side_effects"></textarea>
                        <span class="help-block text-xs" v-if="form.errors.side_effects">{{form.errors.side_effects}}</span>
                    </div>
                </div>
                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">{{__('Submit form')}}</loading-button>
            </form>

        </div>
    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import Multiselect from 'vue-multiselect';
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "Create",
        components: {LoadingButton, HeaderTitle, PageHeader, Link, Multiselect },
        props: {
            title: String,
            categories: Object,
            brands: Object,
            a: SVGAElement
        },
        setup(){
          const form = useForm({
              category:null,
              brand:null,
              name: null,
              description: null,
              quantity: null,
              price: null,
              detail_price: null,
              cost: null,
              status: null,
              supplier: null,
              bar_code: null,
              side_effects: null,
              can_detail: null,
          })
            return { form}
        },
        methods: {
            submitForm: function(){
                 this.form.post(route('medicine.store'))
            }
        }
    }
</script>

<style scoped>

</style>
