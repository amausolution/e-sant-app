<template>
    <HeaderTitle title="Select Department" />
    <form @submit.prevent="submit">
        <div class="container d-flex h-100">
            <div class="row align-self-center w-100 justify-content-center">
                <div class="col-6 mx-auto my-5">
                    <div class="jumbotron">
                        <h6 class="text-truncate text-2xl">{{__('Choose Department')}}</h6>
                        <div class="form-group">
                            <label for="">{{ __('Choose One Department')}}</label>
                            <select name="depart" id="" class="form-control" v-model="form.depart">
                                <option v-for="department in departments" v-bind:value="department.id">
                                    {{ department.department }}
                                </option>
                            </select>
<!--                            <div v-if="errors.depart">{{ errors.depart }}</div>-->
                        </div>
                        <div class="form-group">
                            <label for="room">{{ __('Enter room number')}}</label>
                            <input name="room" class="form-control" type="text" id="room" v-model="form.room" >
                            <!--                            <div v-if="errors.depart">{{ errors.depart }}</div>-->
                        </div>
                        <p class="lead">
                            <button type="submit" class="btn btn-primary btn-lg">{{ __('Validated')}}</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</template>

<script>
    import LayoutPortable from "../Layouts/LayoutPortable";
    import HeaderTitle from "../Shared/HeaderTitle";
    export default {
        props: {
            departments :Object
        },
        name: "SelectDepart",
        components: {HeaderTitle},
        layout: LayoutPortable,
    }
</script>
<script setup>
    import { useForm} from  '@inertiajs/inertia-vue3'
    let form = useForm({
        depart:'',
        room:''
    });
    let  submit = () => {
        form.post(route('check.department'))
        //processing.value = false
    }
</script>
<style scoped>
    .contain {
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
