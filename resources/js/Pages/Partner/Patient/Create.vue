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
            </div>
        </page-header>
        <div class="bg-white shadow-md rounded-md p-4">

<!--            info utilisateur-->
            <div class="row" v-if="step==1">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.first_name">
                        <label class="focus-label">{{__('First Name')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.first_name">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.last_name">
                        <label class="focus-label">{{__('First Name')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.last_name">
                    <select name="" id="gender" v-model="form.gender" class="form-control">
                        <option value="" selected disabled>{{__('Select Gender')}}</option>
                        <option :value="gender.id" v-for="gender in genders" :key="gender">{{gender.title}}</option>
                    </select>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.gender">
                        <input
                            type="date"
                            @change="handleChange"
                            class="bg-white border px-2 py-1 rounded form-control"
                            :placeholder="__('Choose Dob')"
                        />
                </div>
                <template v-show="age !=='' && age < 25">
                    <div class="col-sm-6 col-md-3" >
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.godfather_matricule">
                            <label class="focus-label">{{__('Godfather Matricule')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3" >
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.email">
                            <label class="focus-label">{{__('Email Godfather')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3" >
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.godfather">
                            <label class="focus-label">{{__('Godfather Name')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.godfather_phone">
                            <label class="focus-label">{{__('Godfather Phone')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <select class="form-control floating" v-model="form.type_piece_godfather">
                            <option value=""> -- {{__('Select Type Piece')}} -- </option>
                            <option value="p" v-for="p in typePiece"> {{p}} </option>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.godfather_piece">
                            <label class="focus-label">{{__('Piece Godfather')}}</label>
                        </div>
                    </div>

                </template>

                <template v-if="age > 25">
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.mobil">
                            <label class="focus-label">{{__('Phone')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.phone_emergency">
                            <label class="focus-label">{{__('Phone Emergency')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.email">
                            <label class="focus-label">{{__('Email')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus select-focus">
                            <select class="form-control  floating" v-model="form.type_piece">
                                <option value="" selected disabled> -- {{__('Select Type piece')}} -- </option>
                                <option :value="p" v-for="p in typePiece"> {{p}} </option>
                            </select>
                            <label class="focus-label">{{__('Type Piece')}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group form-focus">
                            <input type="text" class="form-control floating" v-model="form.piece">
                            <label class="focus-label">{{__('Number')}}  {{ ' '+form.type_piece}}</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3 p-2" v-if="form.piece">
                        <input @change="onRectoChange" id="r" type="file" class="form-control floating d-none" @input="form.piece_recto = $event.target.files[0]">
                        <label for="r" class="focus-label w-48 h-48">
                          {{ form.type_piece+' '}} {{__('Recto')}}
                            <img v-if="recto" :src="recto" alt="" />
                        </label>
                    </div>
                    <div class="col-sm-6 col-md-3 p-2" v-if="form.type_piece==='CIN' && form.piece">
                        <input @change="onVersoChange" id="v" type="file" class="form-control floating d-none" @input="form.piece_verso = $event.target.files[0]">
                        <label for="v" class="focus-label w-48 h-48 ">
                            {{ form.type_piece+' '}}   {{__('Verso')}}
                            <img v-if="verso" :src="verso"  alt=""/>
                        </label>
                    </div>
                </template>
                <div class="col-12 flex justify-end">
                    <button
                        type="button"
                        @click="nextStep"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-center rounded text-sky-100 bg-sky-500 hover:bg-sky-600"
                    >
                        {{ __('Next')}}
                        <i class="fa-duotone fa-arrow-right-long ml-2"></i>
                    </button>
                </div>
            </div>
            <div class="row" v-if="step == 2">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.first_name">
                        <label class="focus-label">{{__('First Name')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.first_name">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.last_name">
                        <label class="focus-label">{{__('First Name')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.last_name">
                    <v-date-picker v-model="date">
                        <template v-slot="{ inputValue, inputEvents }">
                            <input
                                class="bg-white border px-2 py-1 rounded form-control"
                                :value="inputValue"
                                v-on="inputEvents"
                                :placeholder="__('Choose Dob')"
                            />
                        </template>
                    </v-date-picker>
                </div>
                <div class="col-sm-6 col-md-3" >
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.email">
                        <label class="focus-label">{{__('Email')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.email">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.gender">
                        <label class="focus-label">{{__('Email')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" >
                    <select name="" id="" v-model="form.gender" class="form-control">
                        <option value="" selected disabled>{{__('Select Gender')}}</option>
                        <option :value="option" v-for="option in ['A','B','C']" :key="option">{{option}}</option>
                    </select>
                </div>
                <div class="col-12 flex justify-end">
                    <button
                        type="button"
                        @click="previewStep"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-center rounded text-slate-100 bg-slate-500 hover:bg-slate-600"
                    >
                        <i class="fa-duotone fa-arrow-left-long"></i>
                        {{ __('Preview')}}
                    </button>
                    <button
                        type="button"
                        @click="nextStep"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-center rounded text-sky-100 bg-sky-500 hover:bg-sky-600"

                    >
                        {{ __('Next')}}
                        <i class="fa-duotone fa-arrow-right-long ml-2"></i>
                    </button>
                </div>
            </div>
            <div class="row" v-if="step == 3">
                <div class="col-sm-6 col-md-3">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.first_name">
                        <label class="focus-label">{{__('First Name')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.first_name">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.last_name">
                        <label class="focus-label">{{__('First Name')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.last_name">
                    <v-date-picker v-model="date">
                        <template v-slot="{ inputValue, inputEvents }">
                            <input
                                class="bg-white border px-2 py-1 rounded form-control"
                                :value="inputValue"
                                v-on="inputEvents"
                                :placeholder="__('Choose Dob')"
                            />
                        </template>
                    </v-date-picker>
                </div>
                <div class="col-sm-6 col-md-3" >
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.email">
                        <label class="focus-label">{{__('Email')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" v-show="form.email">
                    <div class="form-group form-focus">
                        <input type="text" class="form-control floating" v-model="form.gender">
                        <label class="focus-label">{{__('Email')}}</label>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3" >
                    <select name="" id="" v-model="form.gender" class="form-control">
                        <option value="" selected disabled>{{__('Select Gender')}}</option>
                        <option :value="option" v-for="option in ['A','B','C']" :key="option">{{option}}</option>
                    </select>
                </div>
                <div class="col-12 flex justify-end">
                    <button
                        type="button"
                        @click="previewStep"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-center rounded text-slate-100 bg-slate-500 hover:bg-slate-600"
                    >
                        <i class="fa-duotone fa-arrow-left-long"></i>
                        {{ __('Preview')}}
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center px-6 py-2 text-sm font-medium text-center rounded text-teal-100 bg-teal-500 hover:bg-teal-600"
                    >
                        {{ __('Submit Form')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import {Link, useForm} from "@inertiajs/inertia-vue3";
    import { ref } from "vue";
    export default {
        name: "Create",
        components: {PageHeader, HeaderTitle, Link},
        computed() {
            $(".select").select2();
        },
        props:{
            title:String,
            genders: Array,
            img_recto: Image,
            img_verso: Image,
            typePiece: Array
        },
        data(props){
            return {
                step: ref(1),
                recto: props.img_recto,
                verso: props.img_verso,
                dob: new Date(),
                age:'',
                form: useForm({
                    'first_name':null,
                    'last_name':null,
                    'gender':'',
                    'dob':null,
                    'piece':null,
                    'email':null,
                    'mobil':null,
                    'phone2':null,
                    'profession':null,
                    'address':null,
                    'phone_emergency':null,
                    'department':null,
                    'type_piece':null,
                    'piece_recto':null,
                    'piece_verso':null,
                    'exp_date':null,
                    'date_exp':null,
                    'insurer_name':null,
                    'insurer_number':null,
                    'percentage':null,
                    'nbr_person':null,
                    'persons':null,
                    'godfather':null,
                    'godfather_phone':null,
                    'godfather_matricule':null,
                    'godfather_piece':null,
                    'type_piece_godfather':null,
                })
            }
        },
        methods:{
            nextStep(){
              this.step ++
            },
            previewStep(){
               this.step --
            },
            onRectoChange(e) {
                const file = e.target.files[0];
                this.recto = URL.createObjectURL(file);
            },
            onVersoChange(e) {
                const file = e.target.files[0];
                this.verso = URL.createObjectURL(file);
            },
            handleChange(e){
                this.age = this.getAge(e.target.value)
                console.log(this.age);
            },
             getAge(dateString) {
                let today = new Date();
                let birthDate = new Date(dateString);
                let dob = today.getFullYear() - birthDate.getFullYear();
                let m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    dob--;
                }
                return dob;
             }
        }
    }
</script>

<style scoped>

</style>
