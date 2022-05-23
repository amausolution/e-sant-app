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
                <div class="col-auto float-right ml-auto">
                    <Link :href="route('hospitalized.index')" class="btn add-btn"><i class="la la-long-arrow-left"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>
        <div class="card mb-0">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="profile-view">
                            <div class="profile-img-wrap">
                                <div class="profile-img">
                                    <a href="">
                                        <img :src="hospitalisation.patient.avatar" :alt="hospitalisation.patient.name">
                                    </a>
                                </div>
                            </div>
                            <div class="profile-basic">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="profile-info-left">
                                            <h3 class="user-name m-t-0">{{hospitalisation.patient.name}}</h3>
                                            <h5 class="company-role m-t-0 mb-0">{{__('Blood Group')}}</h5>
                                            <small class="text-muted">{{hospitalisation.patient.blood ? hospitalisation.patient.blood: 'NEAN'}}</small>
                                            <div class="staff-id">{{__('Employee ID')}} : {{hospitalisation.patient.patientID}}</div>
                                            <div class="staff-msg"><a href="chat.html" class="btn btn-custom">{{__('Info Accompanying')}}</a></div>
                                        </div>
                                    </div>
                                    <div class="col-md-7">
                                        <ul class="personal-info">
                                            <li>
                                                <span class="title">{{__('Phone')}}:</span>
                                                <span class="text"><a href="">{{hospitalisation.patient.phone}}</a></span>
                                            </li>
                                            <li>
                                                <span class="title">{{__('Email')}}:</span>
                                                <span class="text">{{hospitalisation.patient.email}}</span>
                                            </li>
                                            <li>
                                                <span class="title">{{__('Birthday')}}:</span>
                                                <span class="text">{{hospitalisation.patient.birthday}}</span>
                                            </li>
                                            <li>
                                                <span class="title">{{__('Address')}}:</span>
                                                <span class="text">{{hospitalisation.patient.address}}</span>
                                            </li>
                                            <li>
                                                <span class="title">{{__('Gender')}}:</span>
                                                <span class="text">{{__(hospitalisation.patient.gender)}}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card tab-box">
            <div class="row user-tabs">
                <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                    <ul class="nav nav-tabs nav-tabs-bottom">
                        <li class="nav-item col-sm-3"><a class="nav-link active" data-toggle="tab" href="#consultations">{{__('Consultations')}}</a></li>
                        <li class="nav-item col-sm-3"><a class="nav-link " data-toggle="tab" href="#analyses">{{__('Analyses')}}</a></li>
                        <li class="nav-item col-sm-3"><a class="nav-link " data-toggle="tab" href="#prescriptions">{{__('Prescriptions')}}</a></li>
                        <li class="nav-item col-sm-3"><a class="nav-link" data-toggle="tab" href="#tasks">{{__('Tasks')}}</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="tab-content profile-tab-content">

                    <!-- Consultations Tab -->
                    <div id="consultations" class="tab-pane fade show active">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card recent-activity">
                                    <div class="card-body relative">
                                        <h5 class="text-base font-semibold">{{__('Histories Consultations')}}</h5>
                                        <button class="bg-green-400 px-2 py-1 flex flex-end
                                         text-white absolute top-1 right-1 rounded shadow-md hover:bg-slate-700
                                          hover:shadow-sm">{{__('Add New')}}</button>
                                        <ul class="res-activity-list">
                                            <li @click="changeConsultation(consultation)"  v-for="(consultation, index) in hospitalisation.consultation" :key="consultation.id">
                                                <p class="mb-0">{{consultation.result}}</p>
                                                <p class="res-activity-time">
                                                    <i class="fa fa-clock-o"></i>
                                                     <span class="text-black font-semibold mr-2">{{__('Consultation Of')}}: </span>   <span>{{consultation.date}}</span>
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 card recent-activity">
                               <Consultation :consultation="consult"/>
                            </div>
                        </div>
                    </div>
                    <!-- /Consultations Tab -->

                    <!-- Analyses Tab -->
                    <div id="analyses" class="tab-pane fade">
                        <div class="row">
                           analyse
                        </div>
                    </div>
                    <!-- /Analyses Tab -->

                    <!-- Prescriptions Tab -->
                    <div id="prescriptions" class="tab-pane fade">
                        <div class="row">
                           prescriptions
                        </div>
                    </div>
                    <!-- /Prescriptions Tab -->
                    <!-- Task Tab -->
                    <div id="tasks" class="tab-pane fade">
                        <div class="project-task">
                            <ul class="nav nav-tabs nav-tabs-top nav-justified mb-0">
                                <li class="nav-item"><a class="nav-link active" href="#all_tasks" data-toggle="tab" aria-expanded="true">All Tasks</a></li>
                                <li class="nav-item"><a class="nav-link" href="#pending_tasks" data-toggle="tab" aria-expanded="false">Pending Tasks</a></li>
                                <li class="nav-item"><a class="nav-link" href="#completed_tasks" data-toggle="tab" aria-expanded="false">Completed Tasks</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane show active" id="all_tasks">
                                    <div class="task-wrapper">
                                        <div class="task-list-container">
                                            <div class="task-list-body">
                                                <ul id="task-list">
                                                    <li class="task">
                                                        <div class="task-container">
																		<span class="task-action-btn task-check">
																			<span class="action-circle large complete-btn" title="Mark Complete">
																				<i class="material-icons">check</i>
																			</span>
																		</span>
                                                            <span class="task-label" contenteditable="true">Patient appointment booking</span>
                                                            <span class="task-action-btn task-btn-right">
																			<span class="action-circle large" title="Assign">
																				<i class="material-icons">person_add</i>
																			</span>
																			<span class="action-circle large delete-btn" title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span>
																		</span>
                                                        </div>
                                                    </li>
                                                    <li class="task">
                                                        <div class="task-container">
																		<span class="task-action-btn task-check">
																			<span class="action-circle large complete-btn" title="Mark Complete">
																				<i class="material-icons">check</i>
																			</span>
																		</span>
                                                            <span class="task-label" contenteditable="true">Appointment booking with payment gateway</span>
                                                            <span class="task-action-btn task-btn-right">
																			<span class="action-circle large" title="Assign">
																				<i class="material-icons">person_add</i>
																			</span>
																			<span class="action-circle large delete-btn" title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span>
																		</span>
                                                        </div>
                                                    </li>
                                                    <li class="completed task">
                                                        <div class="task-container">
																		<span class="task-action-btn task-check">
																			<span class="action-circle large complete-btn" title="Mark Complete">
																				<i class="material-icons">check</i>
																			</span>
																		</span>
                                                            <span class="task-label">Doctor available module</span>
                                                            <span class="task-action-btn task-btn-right">
																			<span class="action-circle large" title="Assign">
																				<i class="material-icons">person_add</i>
																			</span>
																			<span class="action-circle large delete-btn" title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span>
																		</span>
                                                        </div>
                                                    </li>
                                                    <li class="task">
                                                        <div class="task-container">
																		<span class="task-action-btn task-check">
																			<span class="action-circle large complete-btn" title="Mark Complete">
																				<i class="material-icons">check</i>
																			</span>
																		</span>
                                                            <span class="task-label" contenteditable="true">Patient and Doctor video conferencing</span>
                                                            <span class="task-action-btn task-btn-right">
																			<span class="action-circle large" title="Assign">
																				<i class="material-icons">person_add</i>
																			</span>
																			<span class="action-circle large delete-btn" title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span>
																		</span>
                                                        </div>
                                                    </li>
                                                    <li class="task">
                                                        <div class="task-container">
																		<span class="task-action-btn task-check">
																			<span class="action-circle large complete-btn" title="Mark Complete">
																				<i class="material-icons">check</i>
																			</span>
																		</span>
                                                            <span class="task-label" contenteditable="true">Private chat module</span>
                                                            <span class="task-action-btn task-btn-right">
																			<span class="action-circle large" title="Assign">
																				<i class="material-icons">person_add</i>
																			</span>
																			<span class="action-circle large delete-btn" title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span>
																		</span>
                                                        </div>
                                                    </li>
                                                    <li class="task">
                                                        <div class="task-container">
																		<span class="task-action-btn task-check">
																			<span class="action-circle large complete-btn" title="Mark Complete">
																				<i class="material-icons">check</i>
																			</span>
																		</span>
                                                            <span class="task-label" contenteditable="true">Patient Profile add</span>
                                                            <span class="task-action-btn task-btn-right">
																			<span class="action-circle large" title="Assign">
																				<i class="material-icons">person_add</i>
																			</span>
																			<span class="action-circle large delete-btn" title="Delete Task">
																				<i class="material-icons">delete</i>
																			</span>
																		</span>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="task-list-footer">
                                                <div class="new-task-wrapper">
                                                    <textarea  id="new-task" placeholder="Enter new task here. . ."></textarea>
                                                    <span class="error-message hidden">You need to enter a task first</span>
                                                    <span class="add-new-task-btn btn" id="add-task">Add Task</span>
                                                    <span class="btn" id="close-task-panel">Close</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="pending_tasks"></div>
                                <div class="tab-pane" id="completed_tasks"></div>
                            </div>
                        </div>
                    </div>
                    <!-- /Task Tab -->

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link} from "@inertiajs/inertia-vue3"
    import { useForm } from '@inertiajs/inertia-vue3'
    import LoadingButton from "@/Shared/LoadingButton";
    import Consultation from "@/Shared/Consultation";
    import { reactive, computed } from 'vue'
    export default {
        name: "hospitalized",
        components: {Consultation, LoadingButton, HeaderTitle, PageHeader, Link},
        props: {
            title: String,
            hospitalisation: Array,
        },
        mounted(props) {

        },
        data() {
            return {
                consult: this.hospitalisation.consultation[0]
            }
        },
        setup (props){
            const form = useForm({
                accompanying:'',
                accompanying_phone:'',
                piece_guarantor:'',
                type_piece:'',
                bed:'',
                slug: props.hospitalisation.slug,
                indemnification:''
            })
            return {form}
        },
        methods: {
            submitHospi () {
                this.form.post(route('hospitalisation.store'),{})
            },
           changeConsultation (data) {
                this.consult = data
            }
        }
    }
</script>

<style scoped>

</style>
