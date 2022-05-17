<template>

    <!-- Search Filter -->
    <form @submit.prevent="search" class="row filter-row">
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <input name="patient_id" type="text" class="form-control floating" v-model="patient_id">
                <label class="focus-label">Patient ID Or Phone </label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <input name="first_name" type="text" class="form-control floating" v-model="first_name">
                <label class="focus-label">Patient First Name</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus">
                <input name="last_name" type="text" class="form-control floating" v-model="last_name">
                <label class="focus-label">Patient Last Name</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group form-focus select-focus">
                <select name="gender" class="select floating" v-model="gender">
                    <option value="" selected disabled>{{__('Select Gender')}}</option>
                    <option value="1">{{__('Male')}}</option>
                    <option value="0">{{__('Female')}}</option>
                </select>
                <label class="focus-label">{{__('Gender')}}</label>
            </div>
        </div>
        <button type="submit">rechercher</button>

    </form>
    <div>
        <span v-for="patient in patients" v-text="patient.first_name" :key="patient.id"></span>
    </div>
    <!-- Search Filter -->
</template>
<script>
    export default {
        props : {
            patients: Object
        },
    }
</script>
<script setup>
    import { useForm} from  '@inertiajs/inertia-vue3'
    let form = useForm({
        patient_id:'',
        first_name: '',
        last_name: '',
        gender: ''
    });

    let  search = () => {
        form.get(route('create.matricule'))
        //processing.value = false
        Inertia.reload({ only: ['users'] })
    }
</script>

<style scoped>

</style>
