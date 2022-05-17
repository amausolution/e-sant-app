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
        }
    }
</script>

<style scoped>

</style>
