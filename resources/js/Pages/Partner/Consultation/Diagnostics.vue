<template>
    <form class="mt-6" @submit.prevent="submitDiag" >
        <div v-if="consultation.first_diag ">
            <label for="diag">{{__('Type Diagnostic Below')}}</label>
            <textarea v-on:keyup.enter="submitDiag" id="diag" name="diagnostic" cols="6" rows="6" class="form-control" v-model="form.diagnostic"></textarea>
            <input type="hidden" name="idconsult" v-model="form.idconsult">
            <loading-button :loading="form.processing" class="btn-indigo px-3 ml-auto mt-3" type="button" v-on:click="endDiag">{{__('End The Diagnostic')}}</loading-button>
        </div>
   </form>
</template>

<script>
    import TextareaInput from "../../../Shared/TextareaInput";
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "Diagnostic",
        components: {LoadingButton, TextareaInput},
        props: {
            endDiag:Function,
            consultation: Object,
        },

        data: function () {
            return{
                form: this.$inertia.form({
                    diagnostic: '',
                    idconsult: this.consultation.slug
                })
            }
        },
        methods: {
            submitDiag: function () {
                this.form.post(route('post.diag'),{
                    preserveScroll:true,
                    onSuccess: () => this.form.reset(),
                })
            }
        }
    }
</script>


<style scoped>

</style>
