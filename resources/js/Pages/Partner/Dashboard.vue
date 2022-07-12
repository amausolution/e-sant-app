<template>
 <HeaderTitle title="Dashboard" />
        <div class="content container-fluid">
        <page-header>
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="page-title">{{__('Welcome')}} {{user.username}}!</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active">{{__('Dashboard')}}</li>
                    </ul>
                </div>
            </div>
        </page-header>

            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-duotone fa-users-medical"></i></span>
                            <div class="dash-widget-info">
                                <h3>112</h3>
                                <span>{{__('Consultation')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-duotone fa-bed-pulse"></i></span>
                            <div class="dash-widget-info">
                                <h3>44</h3>
                                <span>{{__('Hospitalisation')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa fa-diamond"></i></span>
                            <div class="dash-widget-info">
                                <h3>37</h3>
                                <span>{{__('Birth')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
                    <div class="card dash-widget">
                        <div class="card-body">
                            <span class="dash-widget-icon"><i class="fa-duotone fa-user-doctor"></i></span>
                            <div class="dash-widget-info">
                                <h3>218</h3>
                                <span>{{__('Employees')}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Total Revenue</h3>
                                    <BarChart />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title">Sales Overview</h3>
                                    <LineChart />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card-group m-b-30">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">{{__('New Patient')}}</span>
                                    </div>
                                    <div>
                                        <span class="text-success">+10%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">10</h3>
                                <div class="progress mb-2" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">{{__('Overall Patients')}} 218</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">{{__('Earnings')}}</span>
                                    </div>
                                    <div>
                                        <span class="text-success">+12.5%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">142 300 Fcfa</h3>
                                <div class="progress mb-2" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">{{__('Previous Month')}} <span class="text-muted">115 852 Fcfa</span></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">{{__('Expenses')}}</span>
                                    </div>
                                    <div>
                                        <span class="text-danger">-2.8%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">8 500 Fcfa</h3>
                                <div class="progress mb-2" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">{{__('Previous Month')}} <span class="text-muted">7 500 Fcfa</span></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <span class="d-block">{{__('Profit')}}</span>
                                    </div>
                                    <div>
                                        <span class="text-danger">-75%</span>
                                    </div>
                                </div>
                                <h3 class="mb-3">112 000 Fcfa</h3>
                                <div class="progress mb-2" style="height: 5px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: 70%;" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mb-0">{{__('Previous Month ')}}<span class="text-muted">142 000 Fcfa</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Statistics Widget -->
            <div class="row">
                <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
                    <div class="card flex-fill dash-statistics">
                        <div class="card-body">
                            <h5 class="card-title">{{__('Operations')}}</h5>
                            <div class="stats-list">
                                <div class="stats-info">
                                    <p>{{__('Caesarean')}} <strong>4 <small>/ 65</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>{{__('neurosurgery')}} <strong>15 <small>/ 92</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>{{__('heart surgery')}} <strong>85 <small>/ 112</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>{{__('Osteosynthesy')}} <strong>190 <small>/ 212</small></strong></p>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
                    <div class="card flex-fill">
                        <div class="card-body">
                            <h4 class="card-title">{{__('Live Cycle')}}</h4>
                            <div class="statistics">
                                <div class="row">
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box mb-4">
                                            <p>{{__('Birth Rate')}}</p>
                                            <h3>120</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-6 text-center">
                                        <div class="stats-box mb-4">
                                            <p>{{__('Death Rate')}}</p>
                                            <h3>19</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="progress mb-4">
                                <div class="progress-bar bg-purple" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">30%</div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 22%" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">22%</div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: 24%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100">24%</div>
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 26%" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">21%</div>
                                <div class="progress-bar bg-info" role="progressbar" style="width: 10%" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">10%</div>
                            </div>
                            <div>
                                <p><i class="fa fa-dot-circle-o text-purple mr-2"></i>{{__('infant mortality')}} <span class="float-right">6</span></p>
                                <p><i class="fa fa-dot-circle-o text-warning mr-2"></i>{{__('adult mortality')}} <span class="float-right">115</span></p>
                                <p><i class="fa fa-dot-circle-o text-success mr-2"></i>{{__('female mortality')}} <span class="float-right">31</span></p>
                                <p><i class="fa fa-dot-circle-o text-danger mr-2"></i>{{__('male mortality')}} <span class="float-right">47</span></p>
                                <p class="mb-0"><i class="fa fa-dot-circle-o text-info mr-2"></i>{{__('pandemic death')}}<span class="float-right">5</span></p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <!-- /Statistics Widget -->
        </div>
</template>

<script>
    import HeaderTitle from "../../Shared/HeaderTitle";
    import PageHeader from "../../Shared/PageHeader";
    import {computed} from "vue";
    import {usePage} from "@inertiajs/inertia-vue3";
    import BarChart from "@/Components/BarChart.ts";
    import LineChart from "@/Components/LineChart.ts";
    export default {
        name: "Dashboard",
        components: {LineChart, BarChart, PageHeader, HeaderTitle},
        setup() {
            const partner = computed(() => usePage().props.value.partner)
            const getPartner = computed(() => usePage().props.value.getPartner)
            const user = computed(() => usePage().props.value.auth.user)
            return { partner, getPartner, user }
        },
    }
</script>

<style scoped>

</style>
