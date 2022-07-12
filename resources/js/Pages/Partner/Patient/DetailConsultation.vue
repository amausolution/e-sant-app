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
                    <Link :href="route('patients.show',{id: consultation.patient})" class="btn add-btn" data-toggle="modal" data-target="#add_client">
                        <i class="fa-duotone fa-arrow-left"></i> {{__('Go Back')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <section class="review-section information">
            <div class="review-header text-center">
                <h3 class="review-title">{{__('Consultation Information')}}</h3>
                <p class="text-muted"><span>{{consultation.identifier}}</span>{{consultation.date}}</p>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12">
                        <div class="mb-2 grid grid-cols-1
                        md:grid-cols-3 bg-white">
                                <div class="p-2 md:border-r border-b md:border-l">
                                    <div>
                                        <div class="grid grid-cols-2 ">
                                            <span class="text-muted-light">{{__('Doctor Name')}}</span>
                                            <span  class="">
                                                {{consultation.doctor_name}}
                                            </span>
                                        </div>
                                        <div class="form-group">
                                            <span class="font-semibold ">{{__('First diagnostic')}}</span>
                                            <div v-for="(diag , title) in consultation.first_diag" class="grid grid-cols-2">
                                                <div class="text-muted-light">{{title}}:</div>
                                                <div>{{diag}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 md:border-r border-b col-span-1 md:col-span-2">
                                    <div class="flex flex-col space-y-3 mb-3">
                                        <span class="font-semibold">{{__('Diagnostic')}}</span>
                                        <div v-html="consultation.diagnostic"></div>
                                    </div>
                                    <div class="flex flex-col space-y-3">
                                        <span class="font-semibold">{{__('Result Diagnostic')}}</span>
                                        <div>{{consultation.result}}</div>
                                    </div>
                                </div>

                        </div>
                    </div>
            </div>
        </section>

        <section class="review-section professional-excellence" v-if="consultation.prescriptions.length >0">
            <div class="review-header text-center">
                <h3 class="review-title">{{__('Prescription')}}</h3>

            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered review-table mb-0">
                            <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>{{__('Label')}}</th>
                                <th class="w-px">{{__('Quantity')}}</th>
                                <th class="w-px">{{__('Dosage')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(prescription, index) in consultation.prescriptions" :key="prescription.id">
                                <td rowspan="2">{{index+1}}</td>
                                <td rowspan="2">{{prescription.label}}</td>
                                <td>{{prescription.quantity}}</td>
                                <td>
                                   <div class="flex flex-col items-center">
                                       <span>{{prescription.dosage}}</span>
                                       <div>
                                            <span class="text-muted-light" v-if="prescription.dosage_text">( {{prescription.dosage_text}} )</span>
                                           <span v-if="prescription.duration">{{ __('In')}}{{prescription.duration}}</span>
                                       </div>
                                   </div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        <section class="review-section personal-excellence" v-if="consultation.analyses.length>0">
            <div class="review-header text-center">
                <h3 class="review-title">{{__('Analyses')}}</h3>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered review-table mb-0">
                            <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>{{__('Analyse')}}</th>
                                <th>{{__('Note')}}</th>
                                <th>{{__('Emergency')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(analyse,index) in consultation.analyses">
                                <td rowspan="2">{{index+1}}</td>
                                <td rowspan="2">{{analyse.analyse}}</td>
                                <td><p>{{analyse.note}}</p></td>
                                <td>
                                    <span class="text-xs rounded-md shadow-sm px-2 py-1 bg-red-300" v-if="analyse.emergency ==1">{{__('emergency')}}</span>
                                    <span v-else></span>
                                </td>
                                <td>
                                    <span class="text-xs rounded-md shadow-sm px-2 py-1 bg-green-300" v-if="analyse.status ==1">{{__('Done')}}</span>
                                    <span class="text-xs rounded-md shadow-sm px-2 py-1 bg-sky-300" v-else-if="analyse.status ==0">{{__('New')}}</span>
                                    <span class="text-xs rounded-md shadow-sm px-2 py-1 bg-red-300" v-else>{{__('Cancel')}}</span>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link} from "@inertiajs/inertia-vue3";
    export default {
        name: "DetailConsultation",
        components: {HeaderTitle, PageHeader, Link},
        props: {
            title: String,
            consultation: Object,
        }
    }
</script>

<style scoped>

</style>
