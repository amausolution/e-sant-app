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
        <!-- Search Filter -->
        <div class="row filter-row">
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.identifier">
                    <label class="focus-label">{{__('Patient ID')}}</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group form-focus">
                    <input type="text" class="form-control floating" v-model="form.search">
                    <label class="focus-label">{{__('Patient First Name')}}</label>
                </div>
            </div>
            <div class="col-sm-6 col-md-3">
                    <select class="form-control" v-model="form.gender">
                        <option selected disabled value="">{{__('Select Gender')}}</option>
                        <option v-for="gender in genders" :value="gender.id">{{gender.title}}</option>
                    </select>
            </div>
        </div>

        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Name')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Patient Id')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Phone urgency')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Total Consultation')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Gender')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Lif')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Declared dead')}}</th>
                </tr>
                <tr v-for="patient in patients.data" :key="patient.id" class="hover:bg-gray-100 focus-within:bg-gray-100 text-sm text-black">
                    <td class="border-t w">
                        <Link class="flex items-center px-2 py-2 flex-row text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})">
                            <span class="avatar">
                                <img class="" :src="patient.avatar" :alt="patient.name" />
                            </span>
                              <span class="flex flex-col text-sm">
                                    <span>{{ patient.name }}</span>
                                   <span>{{ patient.phone }}</span>
                              </span>
                        </Link>
                    </td>

                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})">
                            {{ patient.doc_number }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})">
                            {{ patient.phone_urgency }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})">
                            {{ patient.count}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})">
                            {{ patient.gender}}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})">
                            <span v-if="patient.live_cycle==1">
                                <i class="fa-duotone fa-wave-pulse"></i>
                            </span>
                            <span v-else>
                                <i class="fa-duotone fa-skull"></i>
                            </span>
                        </Link>
                    </td>
                    <td class="border-t text-right w-px">
                        <Link as="button" type="button" v-if="patient.live_cycle==0"
                              class="text-xl px-3 py-2 rounded-md mr-3
                         shadow-md text-red-600 text-black bg-red-300 hover:red-500
                          transition duration-200" tabindex="-1" :href="route('patient.death',{id: patient.id})">
                            <i class="fa-duotone fa-coffin text-red-600"></i>
                        </Link>
                    </td>

                    <td class="border-t w-px">
                        <div class="flex flex-row space-x-2">
                            <Link class="flex items-center py-2 px-2 text-2xl text-black" tabindex="-1" :href="route('patients.show',{id: patient.id})"
                                  >
                                <i class="fa-duotone fa-chevron-right"></i>
                            </Link>
                        </div>

                    </td>
                </tr>
                <tr v-if="patients.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No patient found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="patients.links" />
        <!-- Search Filter -->
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link} from "@inertiajs/inertia-vue3";
    import Pagination from "@/Shared/Pagination";
    import throttle from "lodash/throttle";
    import pickBy from "lodash/pickBy";
    import mapValues from "lodash/mapValues";
    export default {
        name: "Index",
        components: {Pagination, PageHeader, HeaderTitle, Link},
        props:{
            patients: Object,
            title: String,
            genders: Array,
            filters: Object,
        },
        data() {
            return {
                form: {
                    search: this.filters.search,
                    identifier: this.filters.identifier,
                    gender: this.filters.gender,
                    // trashed: this.filters.gender,
                },
            }
        },
        watch: {
            form: {
                deep: true,
                handler: throttle(function () {
                    this.$inertia.get(route('patients.index'), pickBy(this.form), { preserveState: true })
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
