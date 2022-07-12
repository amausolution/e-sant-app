<template>

    <li class="nav-item ml-2">
        <button  class="nav-link px-6 py-2 rounded bg-rose-400 hover:bg-rose-500 text-sm text-rose-100 shadow-md" v-on:click="toggleModal">
            <i class="fa-duotone fa-viruses"></i>
            {{__('Pathology')}}
        </button>
    </li>
    <li class="nav-item ml-2">
        <button class="nav-link px-6 py-2 text-sm text-orange-500 bg-orange-100 rounded shadow-md hover:bg-orange-200" v-on:click="toggleModalAllergy">
            <i class="fa-duotone fa-hand-dots"></i>
            {{__('Allergy')}}
        </button>
    </li>

    <Modal v-bind:revel="revel" :toggle-modal="toggleModal">

        <template v-slot:header> {{__('New Pathology')}} </template>

            <template v-slot:body >
                <div class="flex flex-col md:flex-row">
                <form @submit.prevent="submitPathology" class="w-full md:w-1/2 p-2">
                    <div class="form-group">
                        <label for="pathology">{{__('Pathology')}}</label>
                        <input id="pathology" name="pathology" class="form-control" type="text" v-model="formP.pathology" :placeholder="__('Type the pathology')" autofocus>
                    </div>
                    <div class="form-group">
                        <label for="level">{{__('Level Pathology')}}</label>
                        <input id="level" name="level" class="form-control" type="text" v-model="formP.level" :placeholder="__('Type the level of pathology')" >
                    </div>
                    <div class="form-group">
                        <textarea-input v-model="formP.observation"  class="mt-2" :label="__('Observation')" type="text"  autocapitalize="off" :placeholder="('Observation here')" />
                    </div>
                    <div class="form-group mt-3">
                        <loading-button :loading="formP.processing" class="btn-indigo ml-auto" type="submit">
                            {{__('Add Pathology')}}</loading-button>
                    </div>
                </form>
                <div class="bg-white rounded-md shadow-sm overflow-x-auto w-full md:w-1/2">
                    <table class="w-full whitespace-nowrap text-base">
                        <tr class="text-left font-bold">
                            <th class="pb-2 pt-3 px-6">{{__('Pathology')}}</th>
                            <th class="pb-2 pt-3 px-6">{{__('Level')}}</th>
                            <th class="pb-2 pt-3 px-6" colspan="2">{{__('Observation')}}</th>
                        </tr>
                        <tr v-for="pathology in patient.consultation.pathologies" :key="pathology.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                               {{ pathology.pathology}}
                            </td>
                            <td class="border-t">
                              {{ pathology.level}}
                            </td>
                            <td class="border-t">
                              {{ pathology.observation}}
                            </td>
                        </tr>
                        <tr v-if="patient.consultation.pathologies.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">{{__('No Pathology found.')}}</td>
                        </tr>
                    </table>
                </div>
                </div>
            </template>
    </Modal>

    <Modal v-bind:revel="modalA" :toggle-modal="toggleModalAllergy">

        <template v-slot:header> {{__('New Allergy')}} </template>

            <template v-slot:body >
                <div class="flex flex-col md:flex-row">
                <form @submit.prevent="submitAllergy" class="w-full md:w-1/2 p-2">
                    <div class="form-group">
                        <label for="allergy">{{__('Allergy')}}</label>
                        <input id="allergy" name="allergy" class="form-control" type="text" v-model="formA.allergy" :placeholder="__('Type the allergy')" autofocus>
                    </div>

                    <div class="form-group">
                        <textarea-input v-model="formA.observation"  class="mt-2" :label="__('Observation')" type="text"  autocapitalize="off" :placeholder="('Observation here')" />
                    </div>
                    <div class="form-group mt-3">
                        <loading-button :loading="formA.processing" class="btn-indigo ml-auto" type="submit">
                            {{__('Add Pathology')}}</loading-button>
                    </div>
                </form>
                <div class="bg-white rounded-md shadow-sm overflow-x-auto w-full md:w-1/2">
                    <table class="w-full whitespace-nowrap text-base">
                        <tr class="text-left font-bold">
                            <th class="pb-2 pt-3 px-6">{{__('Allergy')}}</th>
                            <th class="pb-2 pt-3 px-6" colspan="2">{{__('Observation')}}</th>
                        </tr>
                        <tr v-for="allergy in patient.consultation.allergies" :key="allergy.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t">
                               {{ allergy.allergy}}
                            </td>
                            <td class="border-t">
                              {{ allergy.observation}}
                            </td>
                        </tr>
                        <tr v-if="patient.consultation.allergies.length === 0">
                            <td class="px-6 py-4 border-t" colspan="4">{{__('No Allergy found.')}}</td>
                        </tr>
                    </table>
                </div>
                </div>
            </template>
    </Modal>
</template>

<script>
    import Modal from "@/Shared/Modal";
    import TextareaInput from "@/Shared/TextareaInput";
    import TextInput from "@/Shared/TextInput";
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "DetailPatient",
        components: {LoadingButton, TextInput, TextareaInput, Modal},
        props : {
            patient: Array,
        },
        data() {
            return {
                revel: false,
                modalA: false,
                formP: this.$inertia.form({
                    pathology:'',
                    observation:'',
                    level: '',
                    patient_id:this.patient.consultation.patient_id
                }),
                formA: this.$inertia.form({
                    allergy:'',
                    observation:'',
                    level: '',
                    patient_id:this.patient.consultation.patient_id
                }),
            };
        },
        methods: {
            toggleModal: function () {
                this.revel = !this.revel;
            },
            toggleModalAllergy: function () {
                this.modalA = !this.modalA;
            },
            submitPathology: function () {
             this.formP.post(route('patient.post.pathology'),{
                 onSuccess: () =>{
                     this.formP.reset()
                    //this.revel=false
                 }
             })
            },
            submitAllergy: function () {
                this.formA.post(route('patient.post.allergy'),{
                    onSuccess: () =>{
                        this.formA.reset()
                        //this.revel=false
                    }
                })
            }

        }

    }
</script>

<style >

    .modal-backdrop {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.3);
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .modal {
        background: #FFFFFF;
        box-shadow: 2px 2px 20px 1px;
        overflow-x: auto;
        display: flex;
        flex-direction: column;
    }

    .modal-header,
    .modal-footer {
        padding: 15px;
        display: flex;
    }

    .modal-header {
        position: relative;
        border-bottom: 1px solid #eeeeee;
        color: #4AAE9B;
        justify-content: space-between;
    }

    .modal-footer {
        border-top: 1px solid #eeeeee;
        flex-direction: column;
    }

    .modal-body {
        position: relative;
        padding: 20px 10px;
    }

    .btn-close {
        position: absolute;
        top: 0;
        right: 0;
        border: none;
        font-size: 20px;
        padding: 10px;
        cursor: pointer;
        font-weight: bold;
        color: #4AAE9B;
        background: transparent;
    }

    .btn-green {
        color: white;
        background: #4AAE9B;
        border: 1px solid #4AAE9B;
        border-radius: 2px;
    }

    .modal-fade-enter,
    .modal-fade-leave-to {
        opacity: 0;
    }

    .modal-fade-enter-active,
    .modal-fade-leave-active {
        transition: opacity .5s ease;
    }
</style>
