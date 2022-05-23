<template>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-inner slimscroll">
            <div class="sidebar-menu">
                <ul>
                    <li class="menu-title">
                        <span>{{__('Home')}}</span>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Home'}">
                        <Link :href="route('partner.home')">
                            <i class="la la-dashboard"></i> <span>Back to Home</span>
                        </Link>
                    </li>
                    <li class="py-2" :class="{'active': $page.component ==='Partner/Ticket/Index'}">
                        <a :href="route('ticket.index')">
                            <i class="fa-solid fa-cash-register"></i>
                            <span>{{__('Checkout Reception')}}</span>
                        </a>
                    </li>

                    <li class="py-2" :class="{'active': $page.component ==='Partner/Consultation/Index'}">
                        <Link  :href="route('consultation.index')">
                            <i class="la la-home"></i> <span>{{__('Consultation')}}</span>
                        </Link>
                    </li>

                    <li class="py-2" >
                        <Link  :href="route('doctor.index')">
                            <i class="fa-solid fa-user-doctor"></i> <span>{{__('Listing Doctors')}}</span>
                        </Link>
                    </li>

                    <li class="submenu">
                        <a href="#" @click="toggleMenu"><i class="la la-cog"></i> <span> {{__('Config Global')}}</span>
                            <i class="fa-light fa-angle-right menu-arrow"></i>                        </a>
                        <ul v-bind:style="[!colspan ? 'display: none;':'display: block']" ref="config">
                            <li><Link :href="route('room.index')">{{__('Rooms')}}</Link></li>
                            <li><Link :href="route('setting')">{{__('Settings')}}</Link></li>
                        </ul>
                    </li>
                    <li class="submenu">
                        <a href="#" @click="toggleHospi">
                            <i class="fa-light fa-bed-pulse"></i>
                            <span> {{__('Hospitalisations')}}</span>
                            <i class="fa-light fa-angle-right menu-arrow"></i>
                        </a>
                        <ul v-bind:style="[!hospitalisation ? 'display: none;':'display: block']" ref="config">
                            <li><Link :href="route('hospitalisation.index')">{{__('To Hospitalized')}}</Link></li>
                            <li><Link :href="route('hospitalized.index')">{{__('Patients Hospitalized')}}</Link></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /Sidebar -->

</template>

<script>

    import { Link } from '@inertiajs/inertia-vue3'
    export default {
        components: {
            Link
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
                this.hospitalisation = !this.hospitalisation,
                    this.colspan = false
            }
        }
    }
</script>

<style scoped>

</style>
