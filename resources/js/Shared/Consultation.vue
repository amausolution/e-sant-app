<template>
    <div class="card recent-activity">
        <div class="card-body">
            <h6 class="card-title mb-4">{{consultation.date}}</h6>
            <div class="mb-4">
                <h3>{{ __('First Diagnostic')}}</h3>
                <ul class="grid grid-cols-3 gap-3">
                    <li v-for="(fdiag,title) in consultation.first_diag">
                     <span>{{__(title) }}</span>  <span>{{fdiag}}</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card recent-activity">
        <div class="card-body">
            <h6 class="card-title mb-4">
                <i class="fa-thin fa-stethoscope"></i>
                {{__('Diagnostic')}}
            </h6>
             <div v-html="consultation.diagnostic" class="mb-4 bg-gray-100/20 p-2"></div>
        </div>
    </div>
    <div class="card recent-activity" v-if="consultation.prescriptions.length > 0">
        <div class="card-body">
            <h6 class="card-title mb-4">
                <i class="fa-duotone fa-tablets"></i>
                {{__('Prescriptions')}}
            </h6>
           <div>
               <div class="mt-3">
                   <div class="bg-white rounded-md  overflow-x-auto">
                       <table class="w-full whitespace-nowrap text-base">
                           <tr class="text-left font-semibold">
                               <th class="pb-2 pt-3 px-6 w-px">{{__('Qte')}}</th>
                               <th class="pb-2 pt-3 px-6">{{__('Label')}}</th>
                               <th class="pb-2 pt-3 px-6">{{__('Dosage')}}</th>
                               <th class="pb-2 pt-3 px-6">{{__('Dosage Text')}}</th>
                               <th class="pb-2 pt-3 px-6">{{__('Status')}}</th>
                           </tr>
                           <tr v-for="prescrit in consultation.prescriptions" :key="prescrit.id" class="hover:bg-gray-100 focus-within:bg-gray-100">
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
                                   {{ prescrit.status }}
                               </td>
                           </tr>
                       </table>
                   </div>
               </div>
           </div>
        </div>
    </div>
    <div class="card recent-activity " v-if="consultation.analyses.length !==0">
          <div class="card-body">
                    <h6 class="card-title mb-4">
                        <i class="fa-thin fa-flask"></i>
                       {{__('Analyses')}}
                    </h6>
                    <div class="bg-white rounded-md  overflow-x-auto">
                        <table class="w-full whitespace-nowrap text-base">
                            <tr class="text-left font-semibold">
                                <th class="pb-2 pt-3 px-6 w-px">{{__('Analyse')}}</th>
                                <th>{{__('Note')}}</th>
                                <th class="pb-2 pt-3 px-6 w-px">{{__('Status')}}</th>
                                <th></th>
                            </tr>
                            <tr v-for="analyse in consultation.analyses" :key="analyse.id" class=" focus-within:bg-gray-100">
                                <td class="border-t px-6 py-2 w-px">
                                    {{ analyse.title }}
                                </td>
                                <td class="border-t px-6 py-2">
                                    {{ analyse.note}}
                                </td>
                                <td class="border-t px-6 py-2">
                                    <span class="rounded-lg shadow-sm bg-slate-400 text-sm py-1 px-2" v-if=" analyse.status == 0">{{ __('New')}}</span>
                                    <span class="rounded-lg shadow-sm bg-green-400 text-sm py-1 px-2" v-else-if=" analyse.status == 1">{{ __('Done')}}</span>
                                    <span class="rounded-lg shadow-sm bg-yellow-400 text-sm py-1 px-2" v-else-if=" analyse.status == 2">{{__('In Process')}}</span>
                                    <span class="rounded-lg shadow-sm bg-red-400 text-sm py-1 px-2" v-else>{{ __('Cancel')}}</span>
                                </td>
                                <td class="border-t px-6 py-2 w-px">
                                    <button class="px-2 py-1" v-if="analyse.status==1">
                                        <i class="fa-duotone fa-prescription-bottle-medical text-emerald-500 hover:text-emerald-300 cursor-pointer fa-2x"></i>
                                    </button>
                                    <button disabled class="px-2 py-1 " v-else>
                                        <i class="fa-light fa-prescription-bottle fa-2x text-gray-100"></i>
                                    </button>
                                </td>

                            </tr>
                        </table>
                    </div>
                </div>
   </div>
</template>

<script>

    export default {
        name: "Consultation",
        props: {
            consultation: Object
        }
    }
</script>

<style scoped>

</style>
