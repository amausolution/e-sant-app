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
                    <Link as="button" type="button" :href="route('patients.index')" class="bg-sky-400 shadow-md hover:bg-sky-600 text-white px-6 py-2 rounded-full hover:rounded-md transition delay-150 duration-300">
                        <i class="fa-duotone fa-arrow-left-long"></i>
                        {{__('Go Back')}}
                    </Link>
                </div>
            </div>
        </page-header>
        <div class="bg-white p-4 rounded-md shadow-md ">
            <div class="row filter-row">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="formSearch.identifier">
                        <label class="focus-label">{{__('Patient ID')}}</label>
                    </div>
                </div>
            </div>
            <template v-if="patient.length > 0">
                <div class="border-bottom mb-4">
                    <h3>{{__('Register a death')}}</h3>
                    <div class="flex flex-col space-y-2">
                        <div class="flex flex-row space-x-4">
                            <span class="text-gray-300 font-medium">{{__('Name')}}:</span>
                            <span class="font-semibold">{{patient.name}}</span></div>
                        <div class="flex flex-row space-x-4">
                            <span class="text-gray-300 font-medium">{{__('Matricule')}}:</span>
                            <span class="font-semibold">{{patient.matricule}}</span>
                        </div>
                    </div>
                </div>
                <form @submit.prevent="storeDeath">
                    <div class="row">
                        <div class="form-group col-6">
                            <label class="form-label" for="dt"
                            >{{__('Select Date Death')}}</label
                            >
                            <v-date-picker v-model="date" locale="fr">
                                <template v-slot="{ inputValue, inputEvents }">
                                    <input id="dt"
                                           class="bg-white border px-2 py-1 rounded form-control"
                                           :value="inputValue"
                                           v-on="inputEvents"
                                    />
                                </template>
                            </v-date-picker>
                            <span v-if="form.errors.date" class="help-block">{{form.errors.date}}</span>
                        </div>
                        <div class="form-group">
                            <div class="flex ">
                                <label class="text-gray-600 font-medium">{{__('Time of Death')}}</label>
                            </div>
                            <v-date-picker mode="time" v-model="date" :timezone="timezone"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note">{{__('Kind of death')}}</label>
                        <textarea name="" id="note" cols="3" rows="3" class="form-control" v-model="form.kind_of_death"></textarea>
                        <span v-if="form.errors.kind_of_death" class="help-block">{{form.errors.kind_of_death}}</span>
                    </div>
                    <div class="form-group">
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
                            <i class="fa-duotone fa-skull mr-4"></i>
                            {{__('Submit form')}}
                        </loading-button>
                    </div>
                </form>
            </template>

        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import '@ckeditor/ckeditor5-build-classic/build/translations/fr';
    import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
    import LoadingButton from "@/Shared/LoadingButton";
    import VCalendar from 'v-calendar';
    import throttle from "lodash/throttle";
    import pickBy from "lodash/pickBy";
    export default {
        name: "Death",
        components: {LoadingButton, PageHeader, HeaderTitle,Link,VCalendar},
        props:{
            title: String,
            patient: Array,
            filters: Object,
        },
        setup(props){
            const form = useForm({
                kind_of_death:null,
                date:null,
                note:null,
             //   id: props.death.id
            });

            return {form}
        },
        methods:{
            storeDeath: function () {
                 this.form.transform((data) => ({
                     ...data,
                     note: this.editorData,
                     date: this.date,
                 })).post(route('live.death.store'),{})
            },
            onReady (editor) {
                this.$refs.editorWrapper.prepend(editor.ui.view.toolbar.element)
            }
        },

        watch: {
            formSearch: {
                deep: true,
                    handler: throttle(function () {
                    this.$inertia.get(route('live.death'), pickBy(this.formSearch), { preserveState: true })
                }, 150),
            },
        },
        data(){
            return {
                editor: DecoupledEditor,
                editorData: '',
                editorConfig: {
                    // Run the editor with the German UI.
                    language: 'fr'
                },
                date: new Date(),
                time: new Date(),
                timezone: 'UTC',
              formSearch: useForm({
                    identifier: this.filters.identifier,
                })
            }
        },
    }
</script>

<style scoped>
    .ck-rounded-corners .ck.ck-editor__editable:not(.ck-editor__nested-editable), .ck.ck-editor__editable.ck-rounded-corners:not(.ck-editor__nested-editable) {
        border-bottom-right-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
        height: 400px;
        /*border: solid 1px #A3A3A3 !important;*/
        border: 1px solid var(--ck-color-toolbar-border);
    }

</style>
