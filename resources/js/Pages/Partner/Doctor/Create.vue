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
                <div class="col-auto float-right ml-auto">
                    <Link :href="route('doctor.index')" class="btn add-btn" ><i class="fa fa-arrow-left-long"></i> {{__('Go Back')}}</Link>
                </div>
            </div>
        </page-header>

      <form class="form-horizontal row"  @submit.prevent="submit()">
            <div class="col-12 col-md-12 col-lg-6 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><i class="fa-solid fa-user-plus mr-2"></i>{{__('Information ')}}</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-body">
                            <div class="form-group" >
                                <label class="control-label mx-8">{{__('Avatar')}}</label>
                                <div class="col-md-5 flex items-center">
                                    <input type="file" @input="form.avatar = $event.target.files[0]" />
                       <!--          <InputAvatar :default-src="defaultAvatar" v-model="form.avatar" />-->
                                </div>
                                <span v-if="form.errors.avatar">{{ form.errors.avatar}}</span>
                            </div>

                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="fn" class="control-label ">{{__('First Name')}}
                                        <span class="required"> * </span>
                                    </label>
                                    <input id="fn" type="text" v-model="form.first_name"
                                           :placeholder="__('Enter The First Name Here')"
                                           class="form-control input-height " :class="{'is-invalid': form.errors.first_name}" />

                                    <span class="help-block text-sm" v-if="form.errors.first_name"> {{ form.errors.first_name}} </span>

                                </div>
                                <div class="form-group col-6" :class="{'text-red-400':form.errors.last_name}">
                                    <label for="ln" class="control-label ">{{__('Last Name')}}
                                        <span class="required"> * </span>
                                    </label>
                                    <input id="ln" type="text" name="last_name"  v-model="form.last_name"
                                           :placeholder="__('Enter The Last Name')"
                                           class="form-control input-height" :class="{'is-invalid':form.errors.last_name}" />
                                    <span class="help-block text-sm" v-if="form.errors.last_name"> {{ form.errors.last_name}} </span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-6  ">
                                    <label for="sex" class="control-label ">{{__('Gender')}}
                                        <span class="required"> * </span>
                                    </label>
                                    <multiselect v-model="form.gender" :options="genders" :custom-label="nameWithLang"
                                                 :selectedLabel="__('Selected')" :placeholder="__('Select one')"
                                                 label="title" track-by="id" :class="{'is-invalid': form.errors.gender}"></multiselect>
                                    <span class="help-block" v-if="form.errors.gender"> {{ form.errors.gender}} </span>
                                </div>
                                <div class="form-group col-6 ">
                                    <label for="tel" class="control-label ">{{__('Phone Number')}}
                                        <span class="required"> * </span>
                                    </label>
                                    <input id="tel" name="phone" type="text"  :placeholder="__('Enter The Phone Number Here')"
                                           class="form-control numeric-validation" v-model="form.phone" :class="{'is-invalid': form.errors.phone}" />
                                    <span class="help-block text-xs" v-if="form.errors.phone"> {{ form.errors.phone}} </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-6 ">
                                    <label for="mail" class="control-label ">{{__('E-mail')}}
                                        <span class="required"> * </span>
                                    </label>
                                    <div class="input-group">
                                        <input id="mail" type="text" class="form-control input-height" v-model="form.email"
                                               name="email" :placeholder="__('Enter The Email Address Here')" :class="{'is-invalid': form.errors.email}">
                                    </div>
                                    <span class="help-block text-xs" v-if="form.errors.email"> {{ form.errors.email}} </span>
                                </div>
                                <div class="form-group col-6">
                                    <label for="prof" class="control-label ">{{__('Profession')}}
                                        <span class="required"> * </span>
                                    </label>
                                    <multiselect v-model="form.profession" :value="user.profession" :options="professions"
                                                 :custom-label="nameWithLang" :selectedLabel="__('Selected')"
                                                 :class="{'is-invalid':form.errors.profession}"
                                                 :placeholder="__('Select one')" label="title" track-by="id"></multiselect>
                                    <span class="help-block" v-if="form.errors.profession">{{ form.errors.profession }} </span>
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="dob" class="control-label ">{{__('Birthday')}}
                                    <span class="required"> * </span>
                                </label>
                                <div class="">
                                    <input class="form-control " v-model="form.birthday" type="date"
                                           :placeholder="__('Enter The Dob Here')"
                                           :class="{'is-invalid': form.errors.birthday}"
                                    >
                                </div>
                                <span class="help-block" v-if="form.errors.birthday"> {{ form.errors.birthday}} </span>
                            </div>
                            <div class="form-group ">
                                <label for="spc" class="control-label ">{{__('Specialization')}}
                                    <span class="required"> * </span>
                                </label>
                                <Multiselect v-model="form.specialities" :options="specialities" :multiple="true"
                                             :placeholder="__('Type to search')"
                                             track-by="id"
                                             label="title"
                                             select-label="Type Enter to select"
                                             :selectedLabel="__('Selected')"
                                             :class="{'is-invalid': form.errors.specialities}"
                                >
                                    <span slot="noResult">{{__('No elements found. Consider changing the search query.')}}</span>
                                </Multiselect>
                                <span class="help-block" v-if="form.errors.specialities">{{ form.errors.specialities}}</span>

                            </div>
                            <div class="form-group " >
                                <label for="dept" class="control-label ">{{__('Departments')}}
                                    <span class="required"> * </span>
                                </label>
                                <Multiselect v-model="form.department" :options="departments" :multiple="true"
                                             :placeholder="__('Type to search')"
                                             track-by="id"
                                             label="department"
                                             select-label="Type Enter to select"
                                             :selectedLabel="__('Selected')"
                                             :class="form.errors.department"
                                >
                                    <span slot="noResult">{{__('No elements found. Consider changing the search query.')}}</span>
                                </Multiselect>
                                <span class="help-block" v-if="form.errors.department">{{form.errors.department}}</span>
                            </div>
                            <div class="form-group">
                                <label for="address" class="control-label ">{{__('Address')}}
                                    <span class="required"> * </span>
                                </label>
                                <textarea id="address" v-model="form.address" name="address"
                                          :placeholder="__('Enter Address Here')"
                                          class="form-control" rows="2"
                                          :class="{'is-invalid':form.errors.address}"
                                ></textarea>
                                <span class="help-block" v-if="form.errors.address"> {{form.errors.address}} </span>
                            </div>
                            <div class="form-group">
                                <label for="ed" class="control-label ">{{__('Education')}}
                                </label>
                                <textarea id="ed" v-model="form.education" name="education" class="form-control"
                                          :placeholder="__('Enter Education ')"
                                          :class="{'is-invalid':form.errors.education}"
                                          rows="2"
                                ></textarea>
                                <span class="help-block" v-if="form.errors.education"> {{ form.errors.education }} </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6 col-sm-12 flex flex-col">
                <div class="" v-if="user.length !== 0">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0"><i class="fa-solid fa-key mr-2"></i>{{__('Reset Password ')}}</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-body">
                                <div>
                                    <a href="#" class="" data-toggle="modal" data-target="#reset-pw">{{ __('Reset Pass Word') }}</a>
                                    <span class="text-sm">{{ __(('Default: ("password")')) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0"><i class="fa-solid fa-fingerprint mr-2"></i>{{__('Roles ')}} </h4>
                        </div>
                        <div class="card-body">

                            <div class="grid grid-flow-row-dense grid-cols-3 grid-rows-3 gap-2">
                                <div class="flex items-center" v-for="role in roles" :key="role.id">
                                    <div class="custom-control">
                                        <input :name="`rols[${role.id}]`" :value="role.id" type="checkbox"  v-model="form.role" class="custom-control-input" :id="`role-${role.id}`" >
                                        <label class="custom-control-label" :for="`role-${role.id}`">{{ role.name}} </label>
                                    </div>
                                    <label for="role" class="col-form-label"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-0"><i class="fa-solid fa-user-shield mr-2"></i>{{__('Permission ')}}</h4>
                        </div>
                        <div class="card-body">

                            <div class="grid grid-flow-row-dense grid-cols-3 grid-rows-3 gap-2">

                                <div class="flex items-center" v-for="permission in permissions" :key="permission.id">
                                    <div class="custom-control ">
                                        <input :name="`permission[${permission.id}]`" :value="permission.id" type="checkbox" v-model="form.permissionUser" class="custom-control-input" :id="`permission-${permission.id}`" >
                                        <label class="custom-control-label" :for="`permission-${permission.id}`">{{permission.name}}</label>
                                    </div>
                                    <label for="permission.id" class="col-form-label"> </label>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-mb-6">
                <div class="form-actions">
                    <div class="row">
                        <div class="offset-md-3 col-md-5 justify-content-between d-flex">
                            <button v-if="user.length !== 0" type="submit" class="btn btn-round btn-primary w-96">{{__('Update Doctor')}}</button>
                            <button v-else type="submit" class="btn btn-round btn-success w-96">{{__('Save Doctor')}}</button>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</template>

<script>
    import HeaderTitle from "@/Shared/HeaderTitle";
    import PageHeader from "@/Shared/PageHeader";
    import { Head, Link } from '@inertiajs/inertia-vue3'
    import { useForm } from '@inertiajs/inertia-vue3'
    import InputAvatar from "@/Shared/InputAvatar";
    import Multiselect from 'vue-multiselect'
    import SelectInput from "@/Shared/SelectInput";

    export default {
        name: "Create",
        components: {SelectInput, InputAvatar, PageHeader, HeaderTitle,Link,Multiselect},
        props: {
            title: String,
            roles: Object,
            permissions: Object,
            user: Array,
            departments: Object,
            genders: Object,
            specialities: Object,
            professions: Object,
            defaultAvatar: String,
            session: Number

        },
        data() {
          return {
              url: null,
              fn: '',
              ln: '',
             // permissionUser: []
          }
        },
        setup(){
            const form = useForm({
                  // _method: 'put',
                   avatar:  null,
                   first_name:  '',
                   last_name:'',
                   matricule:'',
                   email: '',
                   phone: '',
                   birthday: '',
                   profession: '',
                   address: '',
                   gender: '',
                   title: '',
                   cin:'',
                   education: '',
                   department: [],
                   specialities: [],
                   role: [],
                   permissionUser: [],
                   username:'',
                   password:'password'
                })
            return { form };
        },
        methods:{

            previewImage(e) {
                const file = e.target.files[0];
                this.url = URL.createObjectURL(file);
            },
            submit() {
              /*  if (this.$refs.photo) {
                    this.form.avatar = this.$refs.photo.files[0];
                }*/

                 this.form.post(route('doctor.store'),{
                     preserveScroll: (page) => Object.keys(page.props.errors).length,
                 })
            },

        }

    }
</script>

<style scoped>

</style>
