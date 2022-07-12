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
                    <Link :href="route('analyse.index')" class="btn add-btn" ><i class="fa fa-arrow-left-long"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="p-4 text-lg m-b-15">Analyse <span class="text-sky-500 hover:underline">#{{analyse.slug}}</span></h6>
                   <div class="flex flex-row space-x-3 items-baseline">
                       <span class="text-muted">{{__('Price')}}: </span>
                       <span>{{typeAnalyse.price}}</span>
                   </div>
                   <div class="flex flex-row space-x-3 items-baseline">
                       <span class="text-muted">{{__('Accept Assurance')}}: </span>
                       <span v-if="typeAnalyse.take_assurance">
                        <i class="fa-duotone fa-hexagon-check text-success fa-3x"></i>
                      </span>
                       <span v-else class="text-xl">
                        <i class="fa-duotone fa-hexagon-xmark text-danger "></i>
                      </span>
                   </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div>
                        <span>{{__('Registered')}}</span>
                    </div>
                    <div>
                        <form @submit.prevent="submitResult">
                            <div class="form-group">
                                <label for="">{{__('Result')}}</label>
                                <input type="text" class="form-control">
                            </div>

                            <div>
                                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">{{__('Submit Form')}}</loading-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {Link, useForm} from "@inertiajs/inertia-vue3"
    import PageHeader from "@/Shared/PageHeader";
    import HeaderTitle from "@/Shared/HeaderTitle";
    import DecoupledEditor from '@ckeditor/ckeditor5-build-decoupled-document';
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "Show",
        components: {LoadingButton, HeaderTitle, PageHeader, Link},
        props: {
            title: String,
            analyse: Object,
            typeAnalyse:Object,
        },
        data(){
            return {
                editor: DecoupledEditor,
                editorConfig: {
                    // Run the editor with the German UI.
                    language: 'fr',
                    height: '500px',
                    ui: {
                        width: '500px',
                        height: '300px'
                    }
                }
            }
        },
        setup(props){
            const form = useForm({
                note: '',
                result: '',
                idAnalyse:props.analyse.id
            })
            return { form }
        },
        methods: {
            onReady( editor )  {
                // Insert the toolbar before the editable area.
                editor.ui.getEditableElement().parentElement.insertBefore(
                    editor.ui.view.toolbar.element,
                    editor.ui.getEditableElement(),
                );
            },
            submitResult(){
                this.form.post(route('analyse.result'))
            }
        }
    }
</script>

<style scoped>
    .document-editor {
        border: 1px solid red;
        border-radius: var(--ck-border-radius);

        /* Set vertical boundaries for the document editor. */
        max-height: 500px;

        /* This element is a flex container for easier rendering. */
        display: flex;
        flex-flow: column nowrap;
    }

    .document-editor__toolbar {
        /* Make sure the toolbar container is always above the editable. */
        z-index: 1;

        /* Create the illusion of the toolbar floating over the editable. */
        box-shadow: 0 0 5px hsla( 0,0%,0%,.2 );

        /* Use the CKEditor CSS variables to keep the UI consistent. */
        border-bottom: 1px solid var(--ck-color-toolbar-border);
    }

    /* Adjust the look of the toolbar inside the container. */
    .document-editor__toolbar .ck-toolbar {
        border: 0;
        border-radius: 0;
    }

    /* Make the editable container look like the inside of a native word processor application. */
    .document-editor__editable-container {
        /*padding: calc( 2 * var(--ck-spacing-large) );*/
       /* background: var(--ck-color-base-foreground);*/

        /* Make it possible to scroll the "page" of the edited content. */

    }

    .document-editor__editable-container .ck-editor__editable {
        /* Set the dimensions of the "page". */
        width: 100%;
        min-height: 20rem;

        /* Keep the "page" off the boundaries of the container. */
        /*padding: 1cm 2cm 2cm;*/

     /*   border: 1px hsl(0, 1%, 47%) solid;
        border-radius: var(--ck-border-radius);
        background: white;*/

        /* The "page" should cast a slight shadow (3D illusion). */
        box-shadow: 0 0 5px hsla( 0,0%,0%,.1 );

        /* Center the "page". */
        margin: 0 auto;
    }

    /* Set the default font for the "page" of the content. */
    .document-editor .ck-content,
    .document-editor .ck-heading-dropdown .ck-list .ck-button__label {
        font: 16px/1.6 "Helvetica Neue", Helvetica, Arial, sans-serif;
    }

    /* Adjust the headings dropdown to host some larger heading styles. */
    .document-editor .ck-heading-dropdown .ck-list .ck-button__label {
        line-height: calc( 1.7 * var(--ck-line-height-base) * var(--ck-font-size-base) );
        min-width: 6em;
    }

    /* Scale down all heading previews because they are way too big to be presented in the UI.
    Preserve the relative scale, though. */
    .document-editor .ck-heading-dropdown .ck-list .ck-button:not(.ck-heading_paragraph) .ck-button__label {
        transform: scale(0.8);
        transform-origin: left;
    }

    /* Set the styles for "Heading 1". */
    .document-editor .ck-content h2,
    .document-editor .ck-heading-dropdown .ck-heading_heading1 .ck-button__label {
        font-size: 2.18em;
        font-weight: normal;
    }

    .document-editor .ck-content h2 {
        line-height: 1.37em;
        padding-top: .342em;
        margin-bottom: .142em;
    }

    /* Set the styles for "Heading 2". */
    .document-editor .ck-content h3,
    .document-editor .ck-heading-dropdown .ck-heading_heading2 .ck-button__label {
        font-size: 1.75em;
        font-weight: normal;
        color: hsl( 203, 100%, 50% );
    }

    .document-editor .ck-heading-dropdown .ck-heading_heading2.ck-on .ck-button__label {
        color: var(--ck-color-list-button-on-text);
    }

    /* Set the styles for "Heading 2". */
    .document-editor .ck-content h3 {
        line-height: 1.86em;
        padding-top: .171em;
        margin-bottom: .357em;
    }

    /* Set the styles for "Heading 3". */
    .document-editor .ck-content h4,
    .document-editor .ck-heading-dropdown .ck-heading_heading3 .ck-button__label {
        font-size: 1.31em;
        font-weight: bold;
    }

    .document-editor .ck-content h4 {
        line-height: 1.24em;
        padding-top: .286em;
        margin-bottom: .952em;
    }

    /* Set the styles for "Paragraph". */
    .document-editor .ck-content p {
        font-size: 1em;
        line-height: 1.63em;
        padding-top: .5em;
        margin-bottom: 1.13em;
    }

    /* Make the block quoted text serif with some additional spacing. */
    .document-editor .ck-content blockquote {
        font-family: Georgia, serif;
        margin-left: calc( 2 * var(--ck-spacing-large) );
        margin-right: calc( 2 * var(--ck-spacing-large) );
    }
</style>
