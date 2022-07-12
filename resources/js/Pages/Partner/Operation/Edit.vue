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
                <div class="col-auto float-right ml-auto space-x-6">
                    <Link as="button" type="button" :href="route('operation.index')" class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-arrow-left-long"></i>
                        {{__('Go Back')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <div class="rounded-md shadow-md grid grid-cols-1 md:grid-cols-2 ">
            <div class="bg-white">
                <h4 class="px-4 py-1">{{__('Info Patient')}}</h4>
                <div class="p-4">
                    <div class="flex flex-col md:flex-row md:space-x-2 mb-2">
                        <span class="text-muted">{{__('Patient Name')}}: </span>
                        <span>{{operation.patient.name}}</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:space-x-2 mb-2">
                        <span class="text-muted">{{__('Patient Phone')}}: </span>
                        <span>{{operation.patient.phone}}</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:space-x-2 mb-2">
                        <span class="text-muted">{{__('Patient Blood Group')}}: </span>
                        <span>{{operation.patient.group_blood}}</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:space-x-2 mb-2">
                        <span class="text-muted">{{__('Patient Age')}}: </span>
                        <span>{{operation.patient.age}}</span>
                    </div>
                </div>

            </div>
            <div class="bg-green-200">
                <h4 class="px-4 py-1">{{__('Info Operation')}}</h4>
                <div class="p-4">
                    <div class="flex flex-col md:flex-row md:space-x-2 mb-2">
                        <span class="text-muted">{{__('Type Operation')}}: </span>
                        <span>{{operation.type_operation}}</span>
                    </div>
                    <div class="flex flex-col md:flex-row md:space-x-2 mb-2">
                        <span class="text-muted">{{__('Date Programming')}}: </span>
                        <span>{{operation.date_programming}}</span>
                    </div>
                    <div class="flex flex-col  mb-2">
                        <span class="text-muted">{{__('Note For This Operation')}}: </span>
                        <div v-html="operation.note"></div>
                    </div>
                </div>

            </div>
        </div>
        <div class="rounded-md shadow-md bg-white mt-4">
            <div class="px-4 py-3 text-xl font-semibold">{{ __('Tasks')}}</div>
            <div class="grid grid-cols-1 md:grid-cols-2">
                <form class="p-4 bg-slate-100 flex flex-row" @submit.prevent="addTask">
                    <div class="w-full grow">
                         <div class="form-group form-focus" :class="{'focused': formTask.title}">
                            <input type="text" class="form-control floating" v-model="formTask.title">
                            <label class="focus-label">{{__('Type Operation')}}</label>
                        </div>
                    </div>
                    <div class="flex-none w-auto">
                        <button type="submit" class="bg-slate-400 text-white hover:bg-slate-600 px-4 py-[.55rem]">
                            <i class="fa-duotone fa-circle-plus text-2xl" v-show="!formTask.processing"></i>
                            <i class="fa-duotone fa-spinner fa-spin text-2xl" v-show="formTask.processing"></i>
                        </button>
                    </div>
                </form>
                <ul class="p-4 bg-sky-200 mb-0[!important]" style="margin-bottom: 0">
                    <draggable
                        class="list-group"
                        ghost-class="ghost"
                        :list="operation.tasks"
                        group="tasks"
                        @start="drag=true"
                        @end="onEnd($event)"
                        item-key="id">
                        <template #item="{element}">
                            <li class="bg-[#F8FAFC] hover:bg-[#E2E8F0] hover:shadow-lg mt-1 p-2 rounded-md cursor-pointer group flex gap-4 items-center"
                                :id="element.id">
                                <span class="flex-none cursor-move" >
                                    <i class="fa-thin fa-bars"></i>
                                </span>
                                <div class="flex items-center gap-2 relative grow " @click="doneTask(element.id)">
                                    <span class="w-8 h-8 rounded-full bg-white p-1 flex items-center justify-center" >
                                        <i class="fa-duotone fa-check text-green-500" v-if="element.status==1"></i>
                                    </span>
                                    <span :class="{'line-through': element.status == 1}"> {{element.title}}</span>
                                    <span class="hidden group-hover:flex absolute right-2 top-2" @click="deleteTask(element.id)">
                                        <i class="fa-duotone fa-trash-can-undo text-red-500"></i>
                                    </span>
                                </div>
                            </li>
                        </template>
                    </draggable>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import {Inertia} from "@inertiajs/inertia";
    import draggable from 'vuedraggable'
    export default {
        name: "Edit",
        components: {HeaderTitle, PageHeader, Link, draggable},
        props:{
            title: String,
            operation: Array
        },
        data(){
            return {
                formTask: useForm({
                    title:null,
                }),
                drag: true,
                oldIndex: '',
                newIndex: ''
            }
        },
        methods: {
            addTask: function () {
                this.formTask.transform( (data) => ({
                    ...data,
                    operationId: this.operation.id
                })).post(route('operation.task'),{})
            },
            doneTask(e){
                Inertia.put(route('task.done',{id: e},{
                    method: 'put',
                    data: {},
                    preserveState: true,
                    preserveScroll: false,
                }))
            },
            deleteTask(i){
                Inertia.delete(route('task.done',{id: i},{
                    method: 'delete',
                    data: {},
                    preserveState: true,
                    preserveScroll: true,
                }))
            },
            onEnd: function (evt) {
                this.$inertia.put(route('task.sort',{id:evt.item.id}),{
                    sort: evt.newIndex
                })
            }
        }
    }
</script>

<style scoped>

</style>
