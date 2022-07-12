<template>
    <HeaderTitle title="List Patient" />
    <div class="content container-fluid">
        <page-header>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">
                        {{user.username}}
                        <span class="text-sm ml-6 font-light">
                             <span>{{ departmentDoctor}} </span>
                        <span>
                           ( <span>{{ __('Room')}}</span>
                            {{ room}} )
                        </span>
                        </span>
                    </h3>

                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('partner.home')">{{__('Dashboard')}}</Link>
                        </li>
                        <li class="breadcrumb-item active">{{title}}</li>
                    </ul>
                </div>
            </div>
        </page-header>


        <div class="flex items-center justify-between mb-6">
            <search-filter v-model="form.search" class="mr-4 w-full max-w-lg" @reset="reset">
                <label class="block mt-4 text-gray-700">{{__('Gender')}}:</label>
                <select v-model="form.gender" class="form-select mt-1 w-full">
                    <option :value="null" />
                    <option value="1">{{__('Male')}}</option>
                    <option value="0">{{ __('Female')}}</option>
                </select>
            </search-filter>
        </div>

        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Patient Name')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Patient ID')}}</th>
                    <th class="pb-2 pt-3 px-6" colspan="2">{{__('N° Ticket')}}</th>
                </tr>
                <tr v-for="consultation in consultations.data" :key="consultation.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                       <Link class="flex items-center px-6 py-2 focus:text-indigo-500" :href="route('consultation.edit',{id: consultation.id})">
                            <img v-if="consultation.patient.avatar" class="block -my-2 mr-2 w-5 h-5 rounded-full" :src="consultation.patient.getAvatar()"  alt=""/>
                            {{ consultation.patient.name }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('consultation.edit',{id: consultation.id})">
                            {{ consultation.patient.doc_number }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" :href="route('consultation.edit',{id: consultation.id})" tabindex="-1">
                            {{ consultation.ticket }}
                        </Link>
                    </td>
                    <td class="w-px border-t">
                        <Link class="flex items-center px-2" :href="route('consultation.edit',{id: consultation.id})" tabindex="-1">
                            <icon name="cheveron-right" class="block w-6 h-6 fill-gray-400" />
                        </Link>
                    </td>
                </tr>
                <tr v-if="consultations.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="4">{{__('No Patients found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="consultations.links" />
    </div>
</template>

<script>
    import HeaderTitle from "../../../Shared/HeaderTitle";
    import PageHeader from "../../../Shared/PageHeader";
    import {Head, Link, usePage} from '@inertiajs/inertia-vue3'
    import Icon from '@/Shared/Icon'
    import pickBy from 'lodash/pickBy'
    import throttle from 'lodash/throttle'
    import mapValues from 'lodash/mapValues'
    import Pagination from '@/Shared/Pagination'
    import SearchFilter from '@/Shared/SearchFilter'
    import {computed} from "vue";
    export default {
        name: "Index",
        components: {
            SearchFilter,
            Pagination,
            PageHeader,
            HeaderTitle,
            Link,
            Icon
        },
        // layout: Layout,
        props: {
            filters: Object,

            consultations: Array,
            title: String,
            departmentDoctor: String,
            room: String
        },
        setup(){
            const user = computed(() => usePage().props.value.auth.user)
            return {user}
        },
        data() {
            return {
                form: {
                    search: this.filters.search,
                    matricule: this.filters.matricule,
                    trashed: this.filters.gender,
                },
            }
        },
        watch: {
            form: {
                deep: true,
                handler: throttle(function () {
                    this.$inertia.get(route('consultation.index'), pickBy(this.form), { preserveState: true })
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
