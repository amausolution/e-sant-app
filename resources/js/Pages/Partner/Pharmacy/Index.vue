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
                <div class="col-auto float-right ml-auto space-x-2">
                    <Link as="button" type="button" :href="route('medicine.index')" class="bg-cyan-400 shadow-md hover:bg-cyan-600 text-white px-2 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-pills"></i>
                        {{__('Medicines')}}
                    </Link>
                    <Link as="button" type="button" :href="route('category.index')" class="bg-slate-400 shadow-md hover:bg-slate-600 text-white px-2 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-list"></i>
                        {{__('Categories')}}</Link>
                    <Link as="button" type="button" :href="route('brand.index')" class="bg-rose-400 shadow-md hover:bg-rose-600 text-white px-2 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-rectangles-mixed"></i>
                        {{__('Brands')}}</Link>
                </div>
            </div>
        </page-header>

        <div class="row">
            <div class="col-sm-6">
                <div class="card">
                    <div class="flex flex-row justify-between p-3">
                        <h6 class="card-title m-b-15">Project details</h6>
                        <form class="onoffswitch">
                            <input type="checkbox" v-model="form1.value" name="status"
                                   class="onoffswitch-checkbox" id="status"
                                   true-value="1"
                                   false-value="0"
                                   v-bind:checked="pharmacy.status"
                                   @change="updateS('status')" >
                            <label class="onoffswitch-label" for="status">
                                <span class="onoffswitch-inner"></span>
                                <span class="onoffswitch-switch"></span>
                            </label>
                        </form>
                    </div>
                    <div class="card-body">

                        <table class="table table-striped table-border">
                            <tbody>
                            <tr>
                                <td class="text-left"><i class="fa-duotone fa-prescription-bottle-medical mr-1"></i>{{__('Title')}}: </td>
                                <td class="text-right">
                                    <Popover class="relative">
                                        <PopoverButton class="no-underline hover:underline hover:decoration-dotted text-sky-400">{{pharmacy.title ?? 'empty'}}</PopoverButton>
                                        <PopoverPanel as="form" class="absolute z-10 bg-white rounded-md shadow-sm p-3 text-left"  v-slot="{ close }">
                                            <div>
                                                <label for="">Title</label>
                                                <form class="flex flex-row space-x-2 text-sm" @submit.prevent="update('title', close)">
                                                    <input :disabled="form.processing" autofocus type="text" class="form-control form-control-sm" v-model="form.value">
                                                    <button type="submit">
                                                        <i class="fa-duotone fa-square-check text-green-400 hover:text-green-600 fa-2x"></i>
                                                    </button>
                                                    <button type="button">
                                                        <i class="fa-duotone fa-square-xmark text-red-400 hover:text-red-600 fa-2x"></i>
                                                    </button>
                                                </form>
                                                <span class="help-block">error</span>
                                            </div>
                                        </PopoverPanel>
                                    </Popover>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-left"><i class="fa-duotone fa-phone mr-1"></i>{{__('Phone')}}: </td>
                                <td class="text-right">
                                    <Popover class="relative" >
                                        <PopoverButton class="no-underline hover:underline hover:decoration-dotted text-sky-400">{{pharmacy.phone ?? 'empty'}}</PopoverButton>
                                        <PopoverPanel as="form" class="absolute z-10 bg-white rounded-md shadow-sm p-3 text-left" v-slot="{ close }">
                                            <div>
                                                <label for="">Phone</label>
                                                <form class="flex flex-row space-x-2 text-sm" @submit.prevent="update('phone',close)">
                                                    <input :disabled="form.processing" autofocus type="text" class="form-control form-control-sm" v-model="form.value">
                                                    <button type="submit">
                                                        <i class="fa-duotone fa-square-check text-green-400 hover:text-green-600 fa-2x"></i>
                                                    </button>
                                                    <button type="button">
                                                        <i class="fa-duotone fa-square-xmark text-red-400 hover:text-red-600 fa-2x"></i>
                                                    </button>
                                                </form>
                                                <span class="help-block">error</span>
                                            </div>
                                        </PopoverPanel>
                                    </Popover>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-left"><i class="fa-duotone fa-mobile mr-1"></i>{{__('Mobile')}}: </td>
                                <td class="text-right">
                                    <Popover class="relative" >
                                        <PopoverButton class="no-underline hover:underline hover:decoration-dotted text-sky-400">{{pharmacy.mobile ?? 'empty'}}</PopoverButton>
                                        <PopoverPanel as="form" class="absolute z-10 bg-white rounded-md shadow-sm p-3 text-left" v-slot="{ close }">
                                            <div>
                                                <label for="">Mobile</label>
                                                <form class="flex flex-row space-x-2 text-sm" @submit.prevent="update('mobile',close)">
                                                    <input :disabled="form.processing" autofocus type="text" class="form-control form-control-sm" v-model="form.value">
                                                    <button type="submit">
                                                        <i class="fa-duotone fa-square-check text-green-400 hover:text-green-600 fa-2x"></i>
                                                    </button>
                                                    <button type="button">
                                                        <i class="fa-duotone fa-square-xmark text-red-400 hover:text-red-600 fa-2x"></i>
                                                    </button>
                                                </form>
                                                <span class="help-block">error</span>
                                            </div>
                                        </PopoverPanel>
                                    </Popover>

                                </td>
                            </tr>
                            <tr>
                                <td class="text-left"><i class="fa-duotone fa-envelope mr-1"></i>{{__('Email')}}: </td>
                                <td class="text-right">
                                    <Popover class="relative" >
                                        <PopoverButton class="no-underline hover:underline hover:decoration-dotted text-sky-400">{{pharmacy.email ?? 'empty'}}</PopoverButton>
                                        <PopoverPanel as="form" class="absolute z-10 bg-white rounded-md shadow-sm p-3 text-left" v-slot="{ close }">
                                            <div>
                                                <label for="">Email</label>
                                                <form class="flex flex-row space-x-2 text-sm" @submit.prevent="update('email',close)">
                                                    <input :disabled="form.processing" autofocus type="text" class="form-control form-control-sm" v-model="form.value">
                                                    <button type="submit">
                                                        <i class="fa-duotone fa-square-check text-green-400 hover:text-green-600 fa-2x"></i>
                                                    </button>
                                                    <button type="button">
                                                        <i class="fa-duotone fa-square-xmark text-red-400 hover:text-red-600 fa-2x"></i>
                                                    </button>
                                                </form>
                                                <span class="help-block">error</span>
                                            </div>
                                        </PopoverPanel>
                                    </Popover>

                                </td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
           <div class="col-sm-6">
               <div class="grid grid-cols-2 gap-3">
                   <div class="bg-white hover:bg-white/20 h-24 shadow-md rounded-md grid content-center">
                       <div class="flex flex-row place-items-center justify-between px-3">
                           <div class="bg-red-100 rounded-full w-16 h-16 p-2 grid place-items-center">
                               <i class="fa-duotone fa-cubes text-red-600 fa-2x"></i>
                           </div>
                          <div>
                              <div class="text-2xl font-bold text-right">
                                  23
                              </div>
                              <div class="text-lg text-right">
                              stock
                              </div>
                          </div>
                       </div>
                   </div>
               </div>
           </div>
        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue'
    export default {
        name: "Index",
        components: {PageHeader, HeaderTitle, Link, Popover, PopoverButton, PopoverPanel},
        props: {
            title: String,
            pharmacy: Array,
            products: Object
        },
        setup (props) {
            const form = useForm({
                value: null,
                key: null
            })
            const form1 = useForm({
                value: props.pharmacy.status,
                key: 'status'
            })
            return { form, form1 }
        },
        methods: {
            update: function (key, closePopover) {
                this.form
                    .transform((data) => ({
                        ...data,
                        key: key,
                    }))
                    .put(route('pharmacy.updateInfo'),{
                        onSuccess: () => {
                            closePopover()
                            this.form.reset('value')
                        }
                    })
            },
            updateS: function () {
                this.form1.put(route('pharmacy.updateInfo'),{
                    preserveState: true,
                    onSuccess: () => (page) => {}
                    })
            }
        }
    }
</script>

<style scoped>

</style>
