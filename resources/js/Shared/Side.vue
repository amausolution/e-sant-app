<template>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div class="sidebar-menu">
                <ul v-if="partner.type==1">
                    <li class="menu-title">
                        <span>{{__('Home')}}</span>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Dashboard'}">
                        <Link :href="route('partner.home')">
                            <i class="fa-duotone fa-gauge"></i>
                            <span>{{__('Back to Home')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Ticket/Index'}">
                        <a :href="route('ticket.index')">
                            <i class="fa-duotone fa-receipt"></i>
                            <span>{{__('Checkout Reception')}}</span>
                        </a>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Patient/Create'}">
                        <a :href="route('register.create')">
                            <i class="fa-duotone fa-user-lock"></i>
                            <span>{{__('Register User')}}</span>
                        </a>
                    </li>
                    <li class="py-2" :class="{'active': $page.component.startsWith('Partner/Consultation')}">
                        <Link  :href="route('consultation.index')">
                            <i class="fa-light fa-laptop-medical"></i>
                            <span>{{__('Consultation')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component.startsWith('Partner/Operation')}">
                        <Link  :href="route('operation.index')">
                            <i class="fa-duotone fa-scalpel-line-dashed"></i>
                            <span>{{__('Surgical Operation')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Doctor/Index'}">
                        <Link  :href="route('doctor.index')">
                            <i class="fa-duotone fa-user-doctor"></i> <span>{{__('Listing Doctors')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Patient/Index'}">
                        <Link  :href="route('patients.index')">
                            <i class="fa-duotone fa-users-medical"></i> <span>{{__('Listing Patients')}}</span>
                        </Link>
                    </li>
                    <li class="submenu py-2">
                        <a href="#" @click="toggleMenu" :class="{'sub-active':$page.component.startsWith('Partner/Setting') || $page.component.startsWith('Partner/Rooms')}"><i class="fa-duotone fa-gear"></i>
                            <span> {{__('Config Global')}}</span>
                            <i class="fa-light fa-angle-right menu-arrow"></i>
                        </a>
                        <ul v-bind:style="[(colspan || $page.component.startsWith('Partner/Setting')) ? 'display: block;':'display: none']" ref="config">
                            <li :class="{'active-child': $page.component.startsWith('Partner/Rooms')}"><Link :href="route('room.index')">{{__('Rooms')}}</Link></li>
                            <li :class="{'active-child': $page.component==='Partner/Setting/Settings'}"><Link :href="route('partner.config')">{{__('Config Global')}}</Link></li>
                            <li :class="{'active-child': $page.component==='Partner/Setting/Info'}"><Link :href="route('setting.index')">{{__('Settings')}}</Link></li>
                            <li :class="{'active-child': $page.component==='Partner/Bloc/Index'}"><Link :href="route('bloc.index')">{{__('Bloc Rooms')}}</Link></li>
                        </ul>
                    </li>
                    <li class="submenu py-2">
                        <a href="#" @click="toggleHospi" :class="{'sub-active':$page.component.startsWith('Partner/Hospitalisation')}">
                            <i class="fa-duotone fa-clipboard-medical"></i>
                            <span> {{__('Hospitalisations')}}</span>
                            <i class="fa-light fa-angle-right menu-arrow"></i>
                        </a>
                        <ul v-bind:style="[(hospitalisation || $page.component.startsWith('Partner/Hospitalisation')) ? 'display: block;':'display: none']" ref="config">
                            <li :class="{'active-child': $page.component==='Partner/Hospitalisation/Index'}"><Link :href="route('hospitalisation.index')">{{__('To Hospitalized')}}</Link></li>
                            <li :class="{'active-child': $page.component==='Partner/Hospitalisation/Patient'}"><Link :href="route('hospitalized.index')">{{__('Patients Hospitalized')}}</Link></li>
                        </ul>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/BloodBank/Index'}">
                        <Link  :href="route('blood_bank.index')">
                            <i class="fa-duotone fa-droplet"></i> <span>{{__('Blood Bank')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Live/Index'}">
                        <Link  :href="route('live.index')">
                            <i class="fa-duotone fa-monitor-waveform"></i> <span>{{__('Live Cycle')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/LiveConsultation/Index'}">
                        <Link  :href="route('live_consultation.index')">
                            <i class="fa-duotone fa-video"></i> <span>{{__('Live Consultation')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Equipment/Index'}">
                        <Link  :href="route('equipment.index')">
                            <i class="fa-duotone fa-toolbox"></i> <span>{{__('Equipments')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Ambulance/Index'}">
                        <Link  :href="route('ambulance.index')">
                            <i class="fa-duotone fa-truck-medical"></i> <span>{{__('Manager Ambulance')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Pharmacy/Index'}">
                        <Link  :href="route('pharmacy.index')">
                            <i class="fa-duotone fa-staff-aesculapius"></i> <span>{{__('Pharmacy')}}</span>
                        </Link>
                    </li>
                </ul>

                <ul v-if="partner.type==2">
                    <li class="menu-title">
                        <span>{{__('Home')}}</span>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Dashboard'}">
                        <Link :href="route('partner.home')">
                            <i class="la la-dashboard"></i> <span>{{__('Back to Home')}}</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Ticket/Index'}">
                        <a :href="route('ticket.index')">
                            <i class="fa-regular fa-ticket"></i>
                            <span>{{__('Checkout Reception')}}</span>
                        </a>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Laboratory/Analyse/Index'}">
                        <Link :href="route('analyse.index')">
                            <i class="fa-duotone fa-flask"></i>
                            <span>{{__('Analyses')}}</span>
                        </Link>
                    </li>
                    <li class="submenu">
                        <a href="#" @click="toggleMenu" :class="{'subdrop': $page.component === 'Settings' || $page.component === 'Laboratory/Settings/Lab'}">
                            <i class="fa-solid fa-radiation"></i>
                            <span> {{__('Config Global')}}</span>
                            <i class="la la-angle-right menu-arrow"></i>
                        </a>
                        <ul :style="`display:${[!colspan ?  'none':' block']}`" ref="config">
                            <li><Link :href="route('partner.config')">{{__('Config Global')}}</Link></li>
                            <li><Link :href="route('setting.index')">{{__('Settings')}}</Link></li>
                        </ul>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Plugins'}">
                        <Link :href="route('plugin.index')">
                            <i class="fa-duotone fa-puzzle-piece"></i>
                            <span>{{__('Plugins')}}</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->

</template>

<script>

    import { Link } from '@inertiajs/inertia-vue3'
    import { computed } from 'vue'
    import { usePage } from '@inertiajs/inertia-vue3'
    export default {
        components: {
            Link
        },

        setup(){
            const partner = computed(() => usePage().props.value.auth.partner)
            const user = computed(() => usePage().props.value.auth.user)
            const roles = computed(() => usePage().props.value.auth.roles)
            const permissions = computed(() => usePage().props.value.auth.permissions)
            return {partner,user,roles,permissions}
        },
        data(){
            return {
                colspan: false,
                hospitalisation: false
            }
        },
        methods:{
            toggleMenu: function () {
                this.colspan = !this.colspan
                this.hospitalisation = false
            },
            toggleHospi: function () {
                this.hospitalisation = !this.hospitalisation
                this.colspan = false
            }
        }
    }
</script>

<style scoped>
   .sub-active{
       background: linear-gradient(to right, #059669 0%, #453a94 100%);
       color: white;
   }
   .active-child a{
       color: white;
       font-weight: bold;
   }
   .active-child a:hover{
       color: #94A3B8;
   }
</style>
