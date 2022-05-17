<template>
    <!-- Search Filter -->
    <form class="row filter-row" @submit.prevent="submitFD">
        <input type="hidden" name="idc" v-model="form.idc">
        <div class="col-4 col-sm-4 col-md-3">
            <div class="form-group form-focus " v-bind:class="[form.tension && form.tension.length > 0 ? 'focused' : '', ]">
                <input type="text" class="form-control floating" name="tension" v-model="form.tension" v-on:keyup.enter="submitFD">
                <label class="focus-label"><i class="fa-solid fa-heart-pulse"></i>{{__('Tension')}}</label>
            </div>
        </div>
        <div class="col-4 col-sm-4 col-md-3">
            <div class="form-group form-focus" v-bind:class="[form.temperature && form.temperature.length > 0 ? 'focused' : '', ]">
                <input type="text" class="form-control floating" name="temperature" v-model="form.temperature" v-on:keyup.enter="submitFD">
                <label class="focus-label"><i class="fa-solid fa-thermometer"></i>{{__('Temperature')}}</label>
            </div>
        </div>
        <div class="col-4 col-sm-4 col-md-3">
            <div class="form-group form-focus" v-bind:class="[form.sugar && form.sugar.length > 0 ? 'focused' : '', ]">
                <input type="text" class="form-control floating" name="sugar" v-model="form.sugar" v-on:keyup.enter="submitFD">
                <label class="focus-label flex flex-row">
                    <Sugar />{{__('Sugar')}} </label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3 flex flex-row space-x-3 items-center">
            <loading-button :loading="form.processing" class="btn-indigo px-3 ml-auto" type="button" v-on:click="endFdiag">{{__('Validated')}}</loading-button>
        </div>
    </form>
    <!-- Search Filter -->
</template>

<script>
    import Sugar from "../../../Shared/Sugar";
    import {useForm} from "@inertiajs/inertia-vue3";
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "FirstDiag",
        components: {LoadingButton, Sugar},
        props:{
            consultation: Object,
            endFdiag: Function
        },
        data: function(){
            return{
                form: this.$inertia.form({
                    _method: 'post',
                    idc: this.consultation.slug,
                    tension: this.consultation.first_diag.tension,
                    temperature: this.consultation.first_diag.temperature,
                    sugar: this.consultation.first_diag.sugar
                })
            }
        },
        methods: {
            submitFD () {
                this.form.post(route('consultation.fd'),{
                    preserveScroll:true,
                    onSuccess: ()=>{
                     this.endFdiag()
                }
                })
            },
        }
    }
</script>
<!--<script setup>
    import {useForm} from "@inertiajs/inertia-vue3";
    import {defineProps} from "vue";
    const props = defineProps({
        consultation: Object,
    })

    let form = useForm({
        idc: props.consultation.slug,
        tension: props.consultation.first_diag.tension,
        temperature: props.consultation.first_diag.temperature,
        sugar: props.consultation.first_diag.sugar
    })
    let  submitFD = () => {
        form.post(route('consultation.fd'),{
            preserveScroll:true
        })

        //processing.value = false
    }

</script>-->

<style scoped>

</style>
