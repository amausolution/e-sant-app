<template>
    <!-- Main Wrapper -->
    <div class="main-wrapper container-login100">
        <div class="account-content">
            <div class="container">
                <!-- Account Logo -->
                <!-- /Account Logo -->
                <div class="account-box">
                    <div class="account-wrapper ">
                        <div class="account-logo flex justify-center">
                                <img :src="`${logoLoging}`" alt="Dreamguy's Technologies">
                        </div>
                        <h3 class="account-title">Login</h3>
                        <p class="account-subtitle">{{__("Access to our dashboard")}}</p>
                        <!-- Account Form -->
                        <form @submit.prevent="submit">
                            <div class="form-group">
                                <label for="username">{{__('Your Login')}}</label>
                                <input id="username" name="username" class="form-control" type="text" v-model="form.username" placeholder="Type your username">
                            </div>
                            <div class="form-group">
                                <label for="pw">{{__('Your Password')}}</label>
                                <div class="position-relative">
                                         <span class="position-absolute show-password" @click="toggleShow">
                                             <i class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                        </span>
                                        <input id="pw" name="password" class="form-control" type="password" v-model="form.password" placeholder="*************">
                                </div>
                            </div>
                            <label class="flex items-center mt-6 select-none" for="remember">
                                <input  id="remember" v-model="form.remember" class="mr-1 w-4 h-4" type="checkbox" />
                                <span class="text-sm">{{__('Remember Me')}}</span>
                            </label>
                            <div class="form-group text-center">
                                <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">
                                    {{__('Login')}}</loading-button>
                            </div>
                            <div v-if="form.errors.username" v-text="form.errors.username"
                                 class="alert alert-danger"></div>

                        </form>
                        <!-- /Account Form -->

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Main Wrapper -->
</template>

<script>
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        components: {LoadingButton},
        layout: null,
        props:{
          logoLoging: String,
        },
        data() {
            return {
                showPassword: false,
                password: null
            };
        },
        computed: {
            buttonLabel() {
                return (this.showPassword) ? "Hide" : "Show";
            }
        },
        methods: {
            toggleShow() {
                this.showPassword = !this.showPassword;
                const passwordField = document.querySelector('#pw')

                if (passwordField.getAttribute('type') === 'password') passwordField.setAttribute('type', 'text')
                else passwordField.setAttribute('type', 'password')
            }
        }

    }
</script>
<script setup>
    import { useForm} from  '@inertiajs/inertia-vue3'
    let form = useForm({
        username:'',
        password: '',
        remember: '',
    });

  let  submit = () => {
      form.post(route('partner.login'))
      //processing.value = false
    }
</script>
<style scoped>
    .container-login100 {
        background-image: url('/images/bg-partner.png');
    }
    .show-password {
        right: 8px;
        top: 15px;
        cursor: pointer;
        font-size: 16px;
    }
    .show-password i {
        font-size: 20px;
    }
    .show-password:hover{
        color: #454141;
    }
</style>
