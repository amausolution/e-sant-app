<template>
   <!-- <div v-if="consulta.prescriptions.length > 0" class="mt-3">
        <div class="bg-white rounded-md  overflow-x-auto">
            <table class="w-full whitespace-nowrap text-base">
                <tr class="text-left font-semibold">
                    <th class="pb-2 pt-3 px-6 w-px">{{__('Qte')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Label')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Dosage')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Dosage Text')}}</th>
                    <th class="pb-2 pt-3 px-6">{{__('Duration')}}</th>
                </tr>
                <tr v-for="prescrit in consulta.prescriptions" :key="prescrit.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t px-6 py-2 w-px">
                            {{ prescrit.quantity }}
                    </td>
                    <td class="border-t px-6 py-2">
                            {{ prescrit.label }}

                    </td>
                    <td class="border-t px-6 py-2">
                            {{ prescrit.dosage }}
                    </td>
                    <td class="border-t px-2  w-auto items-center">
                        <span v-for="item in prescrit.dosage_text" :key="index" class="p-1 bg-cyan-400 max-w-sm  rounded shadow-sm">
                            {{ __(item) }}
                        </span>
                    </td>
                    <td class="border-t px-6 py-2 w-px">
                            {{ prescrit.duration }}
                    </td>
                </tr>
            </table>
        </div>
    </div>-->
    <form @submit.prevent="submitPrescription()">
        <input type="hidden" name="idcons" v-model="form.idcons">
        <div class="grid grid-cols-3 md:grid-cols-8 gap-2 md:gap-4 mt-4" v-for="(prescription,k) in form.prescriptions" :key="k"
             v-if="consulta.diagnostic.length > 0 ">
            <div>
               <text-input v-on:keyup.enter="submitPrescription" v-model="prescription.qte"  class="mt-2" :label="__('Quantity')" type="text" autofocus autocapitalize="off" />
            </div>
            <div class="col-span-2 md:col-span-4">
                  <text-input v-on:keyup.enter="submitPrescription" v-model="prescription.label" class="mt-2" :label="__('Label')" type="text"  autocapitalize="off" />
            </div>
            <div>
                 <text-input v-on:keyup.enter="submitPrescription" v-model="prescription.dosage" class="mt-2" :label="__('Dosage')" type="text"  autocapitalize="off" />
            </div>
            <div>
                <div>
                    <input  value="morning" type="checkbox" name="ma" :id="`ma+${k}`" v-model="prescription.dosageText">
                    <label :for="`ma+${k}`">{{ __('Morning')}}</label>
                </div>
                <div>
                    <input value="noon" type="checkbox" name="mi" :id="`mi+${k}`" v-model="prescription.dosageText">
                    <label :for="`mi+${k}`">{{ __('Noon')}}</label>
                </div>
               <div>
                   <input  value="evening" type="checkbox" name="s" :id="`s+${k}`" v-model="prescription.dosageText">
                   <label :for="`s+${k}`">{{ __('Morning')}}</label>
               </div>
            </div>
            <div class="flex flex-row items-end space-x-2">
                <text-input v-on:keyup.enter="submitPrescription" v-model="prescription.duration" class="mt-2" :label="__('Duration')" type="text"  autocapitalize="off" />
                <span class="flex flex-row space-x-3">
                    <i class="fas fa-minus-circle text-danger" @click="remove(k)" v-show="k || ( !k && form.prescriptions.length > 1)"></i>
                    <i class="fas fa-plus-circle text-success" @click="add(k)" v-show="k === form.prescriptions.length-1"></i>
                </span>
            </div>
        </div>
        <div class="flex mb-5"  v-if="consulta.diagnostic.length > 0 ">
            <loading-button :loading="form.processing" class="btn-indigo px-3 mt-3" type="button" v-on:click="endPres">{{__('Add Prescription')}}</loading-button>
        </div>
    </form>
</template>

<script>
    import TextInput from "@/Shared/TextInput";
    import LoadingButton from "@/Shared/LoadingButton";
    export default {
        name: "Prescription",
        components: {LoadingButton, TextInput},
        props: {
            endPres: Function,
            consulta: Object,
        },
        data() {
            return {
               form: this.$inertia.form({
                   idcons:this.consulta.slug,
                   prescriptions: [{
                       qte:'',
                       label:'',
                       dosage:'',
                       duration:'',
                       dosageText: [],
                   }]
               })
            }
        },
        methods: {
            add(index) {
                this.form.prescriptions.push({
                    qte: '',
                    duration: '',
                    label: '',
                    dosage: '',
                    dosageText: [],
                });
            },
            remove(index) {
                this.form.prescriptions.splice(index, 1);
            },
           submitPrescription: function () {
                this.form.post(route('post.prescription'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        this.form.reset();
                        // props.endPres()
                    }
                })
            }
        }
    }
</script>

<style scoped>

</style>
