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
        <div class="bg-white shadow-md rounded-md min-h-[90%] p-4">
            <form class="row" @submit.prevent="submitForm">
                <div class="col-6 col-sm-6 col-md-4">
                    <div class="form-group form-focus">
                        <input @input="checkMatricule" type="text" class="form-control floating" v-model="form.matricule">
                        <label class="focus-label">{{__('Patient ID')}}</label>
                    </div>
                    <span class="help-block text-xs" v-if="form.errors.matricule">{{form.errors.matricule}}</span>
                </div>
                <template v-if="checkMat">
                    <div class="col-6 col-sm-6 col-md-4" >
                        <div class="form-group form-focus" :class="{'focused': form.type_operation}">
                            <input type="text" class="form-control floating" v-model="form.type_operation">
                            <label class="focus-label">{{__('Type Operation')}}</label>
                        </div>
                    </div>
                    <div class="form-group col-6 col-sm-6 col-md-4">
                        <v-date-picker v-model="date" locale="fr" mode="dateTime" is24hr :timezone="timezone" >
                            <template v-slot="{ inputValue, inputEvents }">
                                <input id="dt" :placeholder="__('Programming Date')"
                                       class="bg-white border px-2 py-1 rounded form-control"
                                       :value="inputValue"
                                       v-on="inputEvents"
                                />
                            </template>
                        </v-date-picker>
                    </div>
                    <div class="form-group col-12">
                        <label for="">{{__('Note')}}</label>
                        <div ref="editorWrapper">
                            <ckeditor
                                v-model="editorData"
                                :editor="editor"
                                :config="editorConfig"
                                :disabled="loading"
                                @ready="onReady"
                            ></ckeditor>
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <loading-button :loading="form.processing"
                                        class="bg-emerald-400 shadow-md hover:bg-emerald-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300"
                                        type="submit">
                            {{__('Submit form')}}
                        </loading-button>
                    </div>
                </template>

            </form>
          <!--  <v-calendar
                class="custom-calendar max-w-full"
                :masks="masks"
                :attributes="attributes"
                disable-page-swipe
                is-expanded
            >
                <template v-slot:day-content="{ day, attributes }">
                    <div class="flex flex-col h-full z-10 overflow-hidden">
                        <span class="day-label text-sm text-gray-900">{{ day.day }}</span>
                        <div class="flex-grow overflow-y-auto overflow-x-auto">
                            <p
                                v-for="attr in attributes"
                                :key="attr.key"
                                class="text-xs leading-tight rounded-sm p-1 mt-0 mb-1"
                                :class="attr.customData.class"
                            >
                                {{ attr.customData.title }}
                            </p>
                        </div>
                    </div>
                </template>
            </v-calendar>-->

        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import { Inertia } from '@inertiajs/inertia';
    import NProgress from 'nprogress'
    import DecoupledEditor from "@ckeditor/ckeditor5-build-decoupled-document";
    import  {DatePicker, Calendar} from "v-calendar";
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "Create",
        components: {LoadingButton, PageHeader, HeaderTitle,Link,DatePicker ,Calendar},
        props:{
            title: String,
            patient: Object
        },
        data(){
            const month = new Date().getMonth();
            const year = new Date().getFullYear();
            return{
                form:useForm({
                    matricule: null,
                    type_operation: null,
                    note: null,
                    date_programming: null,
                }),
                checkMat: false,
                editor: DecoupledEditor,
                editorData: '',
                editorConfig: {
                    // Run the editor with the German UI.
                    language: 'fr'
                },
                date: null,
                timezone: 'Africa/Dakar',
                masks: {
                    weekdays: 'WWW',
                },
                attributes: [
                    {
                        key: 1,
                        customData: {
                            title: 'Lunch with mom.',
                            class: 'bg-red-600 text-white',
                        },
                        dates: new Date(2022, 6, 1),
                    },
                    {
                        key: 2,
                        customData: {
                            title: 'Take Noah to basketball practice',
                            class: 'bg-blue-500 text-white',
                        },
                        dates: new Date(year, month, 2),
                    },
                    {
                        key: 3,
                        customData: {
                            title: "Noah's basketball game.",
                            class: 'bg-blue-500 text-white',
                        },
                        dates: new Date(year, month, 5),
                    },
                    {
                        key: 4,
                        customData: {
                            title: 'Take car to the shop',
                            class: 'bg-indigo-500 text-white',
                        },
                        dates: new Date(year, month, 5),
                    },
                    {
                        key: 4,
                        customData: {
                            title: 'Meeting with new client.',
                            class: 'bg-teal-500 text-white',
                        },
                        dates: new Date(year, month, 7),
                    },
                    {
                        key: 5,
                        customData: {
                            title: "Mia's gymnastics practice.",
                            class: 'bg-pink-500 text-white',
                        },
                        dates: new Date(year, month, 11),
                    },
                    {
                        key: 6,
                        customData: {
                            title: 'Cookout with friends.',
                            class: 'bg-orange-500 text-white',
                        },
                        dates: { months: 5, ordinalWeekdays: { 2: 1 } },
                    },
                    {
                        key: 7,
                        customData: {
                            title: "Mia's gymnastics recital.",
                            class: 'bg-pink-500 text-white',
                        },
                        dates: new Date(year, month, 22),
                    },
                    {
                        key: 8,
                        customData: {
                            title: 'Visit great grandma.',
                            class: 'bg-red-600 text-white',
                        },
                        dates: new Date(year, month, 25),
                    },
                ],
            }
        },
        methods: {
            onReady (editor) {
                this.$refs.editorWrapper.prepend(editor.ui.view.toolbar.element)
            },
            submitForm: function () {
               this.form.transform((data) =>({
                   ...data,
                   note: this.editorData,
                   date_programming: this.date,
               })).post(route('operation.store'),{})
            },
           checkMatricule(){
                console.log(this.form.data('matricule'))
               Inertia.visit(route('operation.check'),
                    {

                        method: 'POST',
                        data: {...this.form.data('matricule')},
                        preserveScroll: true,
                        preserveState:true,
                        onStart: visit => {
                            NProgress.done()
                            NProgress.remove()
                        },
                        onError: (error) => {
                            this.form.setError('matricule',error.matricule)
                            this.checkMat= false
                        },
                        onSuccess: ()=> {
                            this.form.clearErrors('matricule')
                            this.checkMat= true
                        }
                })
            }
        }
    }
</script>

<style scoped>
    .ck-rounded-corners .ck.ck-editor__editable:not(.ck-editor__nested-editable), .ck.ck-editor__editable.ck-rounded-corners:not(.ck-editor__nested-editable) {
        border-bottom-right-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        height: 300px;
        /*border: solid 1px #A3A3A3 !important;*/
        border: 1px solid var(--ck-color-toolbar-border);
    }
</style>
