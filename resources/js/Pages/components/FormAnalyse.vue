<template>

        <div class="form-group">
            <label class="typo__label">{{__('Select Analyse')}}</label>
            <multiselect v-model="form.analyse" deselect-label="Can't remove this value" track-by="id" label="title"
                         placeholder="Select one" :options="categories" :searchable="true" :allow-empty="false">
                <template slot="singleLabel" slot-scope="{ category }"><strong>{{ category.title }}</strong></template>
            </multiselect>
            <!-- <select name="" id="" v-model="form.analyse" class="form-control">
                 <option :value="category.id" v-for="(category) in categories">{{category.title}}</option>
             </select>-->
            <span v-if="form.errors.analyse">{{form.errors.analyse}}</span>
        </div>
        <div class="form-group">
            <label for="price">{{__('Price')}}</label>
            <input id="price" type="text" class="form-control" v-model.number="form.price">
        </div>
        <div class="form-group">
            <label for="duration">{{__('Duration')}}</label>
            <input id="duration" type="text" class="form-control" v-model="form.duration">
        </div>
        <div class="form-group">
            <label for="insurer">{{__('Take Assurance')}}</label>
            <input id="insurer" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.take_assurance">
        </div>
        <div class="form-group">
            <label for="status">{{__('Status')}}</label>
            <input id="status" type="checkbox" class="form-control" style="width: 1rem; height: 1rem" v-model="form.status" checked>
        </div>

</template>

<script>
    import LoadingButton from "@/Shared/LoadingButton";
    import {useForm} from "@inertiajs/inertia-vue3";
    import Multiselect from "vue-multiselect";
    export default {
        name: "FormAnalyse",
        components: {LoadingButton,Multiselect},
        props: {
            data: Object,
            categories: Array
        },
        setup(props){
            const form=useForm({
                analyse: props.data.analyse??'',
                price: props.data.price??'',
                take_assurance: props.data.take_assurance??'',
                status: props.data.status??'',
                duration: props.data.duration??''
            })

            return {form}
        },
    }
</script>

<style scoped>

</style>
