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
                    <Link :href="route('room.create')" class="btn add-btn"><i class="fa fa-plus"></i> Add Room</Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white rounded-md shadow overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-bold">
                    <th class="pb-2 pt-3 px-6">{{__('Room')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Price')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Level')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Class')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Clim')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('WC Intern')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Bed Accompany')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                </tr>
                <tr v-for="room in rooms.data" :key="room.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            {{ room.name ? room.name : room.room_number }} <span class="ml-3 text-sm text-gray-500">({{room.total_beds}} {{__('Beds')}})</span>
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            {{ room.price }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            {{ room.level }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            {{ room.class }}
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            <i v-if="room.clim" class="fa fa-check text-success"></i>
                            <i v-else class="fa fa-times text-danger"></i>
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            <i v-if="room.wc_in" class="fa fa-check text-success"></i>
                            <i v-else class="fa fa-times text-danger"></i>
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            <i v-if="room.bed_accompanying" class="fa fa-check text-success"></i>
                            <i v-else class="fa fa-times text-danger"></i>
                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2" tabindex="-1" :href="route('room.edit',{id: room.id})">
                            <template v-if="room.status">
                                <i class="fa fa-check text-success"></i>
                            </template>
                           <template v-else>
                               <i class="fa fa-times text-danger"></i>
                           </template>

                        </Link>
                    </td>
                    <td class="border-t">
                        <Link class="flex items-center px-6 py-2 text-2xl" tabindex="-1" :href="route('room.destroy',{id: room.id})"
                              method="delete" as="button" type="button" :title="__('Destroy')"
                        >
                           <i class="la la-trash text-red-400"></i>
                        </Link>
                    </td>
                </tr>
                <tr v-if="rooms.data.length === 0">
                    <td class="px-6 py-4 border-t" colspan="9">{{__('No Room found.')}}</td>
                </tr>
            </table>
        </div>
        <pagination class="mt-6" :links="rooms.links" />
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import { Link }  from "@inertiajs/inertia-vue3"
    import Pagination from "@/Shared/Pagination";
    export default {
        name: "index",
        components: {Pagination, PageHeader, HeaderTitle, Link},
        props: {
            title: String,
            rooms: Object
        }
    }
</script>

<style scoped>

</style>
