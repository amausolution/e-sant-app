<template>
   <HeaderTitle title="checkout reception" />
    <div class="content container-fluid">
        <page-header>
            <div class="row">
                <div class="col">
                    <h3 class="page-title">{{__('Registered Patient')}}</h3>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><Link :href="route('partner.home')">{{__('Dashboard')}}</Link></li>
                        <li class="breadcrumb-item active">{{__('Checkout Patient')}}</li>
                    </ul>
                </div>
                <div class="col-auto justify-content-evenly float-right ml-auto">
                    <button @click="switchKind('back')" class="btn btn-primary m-r-10" :disabled="activeKind=== 'back'">{{__('Return Patient')}}</button>
                    <button @click="switchKind('new')" class="btn btn-success" :disabled="activeKind=== 'new'">{{__('New Patient')}}</button>
                </div>
            </div>
        </page-header>

         <template v-if="activeKind === 'new'">
             <NewPatient ></NewPatient>
         </template>
        <template v-else-if="activeKind === 'back'">
            <!-- Search Filter -->
            <ReturnPatient patients="patients" />
          <!--  <form @submit.prevent="submit"  class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input name="patient_id" type="text" class="form-control floating" v-model="patient_id">
                        <label class="focus-label">Patient ID Or Phone </label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input name="phone" type="text" class="form-control floating" v-model="phone_number">
                        <label class="focus-label">Phone</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input name="first_name" type="text" class="form-control floating" v-model="first_name">
                        <label class="focus-label">Patient First Name</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input name="last_name" type="text" class="form-control floating" v-model="last_name">
                        <label class="focus-label">Patient Last Name</label>
                    </div>
                </div>

            </form>
            <div class="d-flex justify-content-between" v-if="filterPatient !== undefined">
                <div>
                    <span v-for="patient in filterPatient" v-text="patient.first_name" :key="patient.id"></span>
                    <span v-for="patient in filterPatient" v-text="patient.last_name" :key="patient.id"></span>
                    <span v-for="patient in filterPatient" v-text="patient.mobil" :key="patient.id"></span>
                    <span v-for="patient in filterPatient" v-text="patient.gender" :key="patient.id"></span>
                </div>

                <form>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">First Name <span class="text-danger">*</span></label>
                                <input class="form-control"  type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Last Name</label>
                                <input class="form-control"  type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Username <span class="text-danger">*</span></label>
                                <input class="form-control"  type="text">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-form-label">Email <span class="text-danger">*</span></label>
                                <input class="form-control floating"  type="email">
                            </div>
                        </div>

                    </div>
                </form>
            </div>
-->
            <!-- Search Filter -->
        </template>
        <template v-else></template>
    </div>


</template>

<script>
    import HeaderTitle from "../../../Shared/HeaderTitle";
    import PageHeader from "../../../Shared/PageHeader";
    import { Link } from '@inertiajs/inertia-vue3'
    import NewPatient from "./NewPatient";
    import ReturnPatient from "./ReturnPatient";
    export default {
        name: "Index",
        components: {ReturnPatient, NewPatient, PageHeader, HeaderTitle, Link},
        props : {
            patients: Object
        },
        data() {
            return {
                user:this.f,
                activeKind: 'back',
                patient_id: '',
                first_name: '' ,
                last_name: '',
                phone_number:''
            };
        },
        methods: {
            switchKind(kind) {
                switch (kind)
                {
                    case "new":
                        this.activeKind = 'new'
                        break
                    case "back":
                        this.activeKind = 'back'
                        break
                    default:
                        this.activeKind = 'back'
                }
            }
        },
        computed: {
            filterPatient: function(){
                if (this.patient_id.length > 2 || this.first_name.length >1 || this.last_name.length>1|| this.phone_number.length>8){
                    return  this.patients.filter((patient)=> {
                        return patient.doc_number.match(this.patient_id)
                            && patient.mobil.match(this.phone_number,true)
                            && patient.first_name.match(this.first_name)
                            && patient.last_name.match(this.last_name)

                    })
                }

            }
        }
    }
</script>
<script setup>
    import { reactive } from 'vue'
    import { Inertia } from '@inertiajs/inertia'

    const form = reactive({
        first_name: null,
        last_name: null,
        patient_id: null,
    })

    function submit() {
        Inertia.get(route('ticket.index'), form)
    }

</script>
<style scoped>

</style>
