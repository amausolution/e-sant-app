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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
            <div class="bg-white rounded-md shadow overflow-x-auto p-2">
                <form @submit.prevent="submitForm">
                    <div class="form-group">
                        <label class="typo__label">{{__('Select Analyse')}}</label>
                        <multiselect v-model="form.analyse" deselect-label="Can't remove this value" track-by="id" label="title"
                                     placeholder="Select one" :options="categories" :searchable="true" :allow-empty="false">
                            <template slot="singleLabel" slot-scope="{ category }"><strong>{{ category.title }}</strong></template>
                        </multiselect>
                        <!-- <select name="" id="" v-model="form.analyse" class="form-control">
                             <option :value="category.id" v-for="(category) in categories">{{category.title}}</option>
                         </select>-->
                        <span v-if="form.errors.analyse">{{form.errors.analyse}}</span>
                    </div>
                    <div class="form-group">
                        <label class="typo__label">{{__('Select Department')}}</label>
                        <multiselect v-model="form.department" deselect-label="Can't remove this value" track-by="id" label="department"
                                     placeholder="Select one" :options="departments" :searchable="true" :allow-empty="false">
                            <template slot="singleLabel" slot-scope="{ department }"><strong>{{ department.title }}</strong></template>
                        </multiselect>
                        <!-- <select name="" id="" v-model="form.analyse" class="form-control">
                             <option :value="category.id" v-for="(category) in categories">{{category.title}}</option>
                         </select>-->
                        <span v-if="form.errors.analyse">{{form.errors.analyse}}</span>
                    </div>
                    <div class="form-group">
                        <label for="price">{{__('Price')}}</label>
                        <input id="price" type="text" class="form-control" v-model.number="form.price">
                    </div>
                    <div class="form-group">
                        <label for="duration">{{__('Duration')}}</label>
                        <input id="duration" type="text" class="form-control" v-model="form.duration">
                    </div>
                    <div class="form-group flex flex-row space-x-4 items-baseline">
                        <label for="insurer">{{__('Take Assurance')}}</label>
                        <input id="insurer" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.take_assurance">
                    </div>
                    <div class="form-group flex flex-row space-x-4 items-baseline">
                        <label for="status">{{__('Status')}}</label>
                        <input id="status" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.status" checked>
                    </div>
                    <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">{{__('Add Analysis')}}</loading-button>
                </form>
            </div>
            <div class="col-span-2">
              <div class="bg-white rounded-md shadow overflow-x-auto">
                <table class="w-full whitespace-nowrap text-base">
                    <tr class="text-left font-bold">
                        <th class="pb-2 pt-3 px-6">{{__('Analyse')}}</th>
                        <th class="pb-2 pt-3 px-6">{{__('Price')}}</th>
                        <th class="pb-2 pt-3 px-6" >{{__('Take Assurance')}}</th>
                        <th class="pb-2 pt-3 px-6" >{{__('Duration')}}</th>
                        <th class="pb-2 pt-3 px-6" >{{__('Status')}}</th>

                    </tr>
                    <tr v-for="analyse in analyses.data" :key="analyse.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <Link class="flex items-center px-6 py-2 focus:text-indigo-500" :href="route('lab.edit',{id: analyse.id})">
                                {{ analyse.analyse }}
                            </Link>
                        </td>
                        <td class="border-t">
                            <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('lab.edit',{id: analyse.id})">
                                {{ analyse.price }}
                            </Link>
                        </td>
                        <td class="border-t">
                            <Link class="flex items-center px-6 py-2"  tabindex="-1" :href="route('lab.edit',{id: analyse.id})">
                                {{ analyse.take_assurance }}
                            </Link>
                        </td>
                        <td class="border-t">
                            <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('lab.edit',{id: analyse.id})">
                                {{ analyse.duration }}
                            </Link>
                        </td>
                        <td class="border-t">
                            <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('lab.edit',{id: analyse.id})">
                                {{ analyse.status }}
                            </Link>
                        </td>
                        <td class="w-px border-t">
                            <button type="button" class="flex items-center px-2 text-red-300 hover:text-red-500" tabindex="-1" @click="toggleModal(analyse.id)">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="analyses.data.length === 0">
                        <td class="px-6 py-4 border-t" colspan="6">{{__('No Category Analyses found.')}}</td>
                    </tr>
                </table>
            </div>
            </div>
        </div>
    </div>
    <ModalConfirmation :showModal="showModal" :modalConfirmation="toggleModal" :idA="idA" >
        <template v-slot:message>
            <div class="flex flex-col items-center justify-center">
                <i class="fa-duotone fa-triangle-exclamation fa-3x text-warning"></i>
                <p class="text-sm">{{__('Message Confirmation')}}</p>
            </div>

        </template>
        <template v-slot:buttons>
            <button @click="toggleModal"
                class="px-6 py-2 mr-4 text-base text-orange-500 bg-orange-100 rounded-md shadow-sm hover:bg-orange-200"
            >
                {{__('Cancel')}}
            </button>
            <Link  type="button" :href="route('analyse.delete')" :data="{'id': idA}" method="delete" @click="toggleModal"
                class="inline-flex items-center hove:text-white shadow-sm px-6 py-2 text-base font-medium text-center rounded
                 text-rose-100 bg-rose-500 hover:bg-rose-600"
            >
                {{__('Yes Delete')}}
            </Link>
        </template>
    </ModalConfirmation>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3"
    import Multiselect from 'vue-multiselect'
    import LoadingButton from "@/Shared/LoadingButton";
    import FormAnalyse from "@/Pages/components/FormAnalyse";
    import ModalConfirmation from "@/Pages/components/ModalConfirmation";
    export default {
        name: "Lab",
        components: {ModalConfirmation, FormAnalyse, LoadingButton, PageHeader, HeaderTitle, Link, Multiselect, useForm},
        props: {
            title: String,
            categories: Object,
            analyses: Object,
            departments: Object
        },
        data(){

            return {
                showModal: false,
                idA: ''
            }
        },
        setup(analy) {
            const form = useForm({
                analyse:analy.analyse??'',
                price:analy.price??'',
                take_assurance:analy.take_assurance??'',
                status:analy.status??'',
                duration:analy.duration??'',
                department:''
            })

            return {form}
        },
        methods:{
            submitForm(){
                this.form.transform((data) => ({
                    ...data,
                    analyse: data.analyse.id??'',
                })).post(route('analyse.store'),{
                    onSuccess:()=> this.form.reset()
                })
            },
            toggleModal(ida){
                this.showModal = !this.showModal,
                this.idA = ida
            }
        }
    }
</script>

<style scoped>

</style>
