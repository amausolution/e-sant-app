<template>

        <div class="card-body">
            <h6 class="card-title">{{consultation.date}}</h6>
            <div>
                <span>{{ __('First Diagnostic')}}</span>
                <ul class="grid grid-cols-3 gap-3">
                    <li v-for="(fdiag,title) in consultation.first_diag">
                     <span>{{__(title) }}</span>  <span>{{fdiag}}</span>
                    </li>
                </ul>
            </div>
            <div>
               <ul>
                   <li v-for="diag in consultation.diagnostic">
                       {{diag}}
                   </li>
               </ul>
            </div>
            <div>
              <span>{{__('Prescriptions')}}</span>
            </div>
            <div>
                <span>{{__('Analyses')}}</span>
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
