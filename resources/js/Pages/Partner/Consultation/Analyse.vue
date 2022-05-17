<template>
    <form v-on:submit.prevent="submitAnalyse" class="w-full md:w-2/4 flex flex-col mx-auto" id="analys">
        <div class="flex flex-col space-y-3 mb-4" v-for="(analyse,a) in form.analyses" :key="a">
            <label class="typo__label">{{__('Category Analysis')}}</label>
            <vue-multiselect v-model="analyse.analyse_id" :select-label="__('type enter to select')" :options="category_analyses"  v-bind:placeholder="__('Select one')" label="title" track-by="id" tag-placeholder="Add this as new tag">
             <template>
                 <span slot="noResult">{{__('Consider changing the search query.')}}</span>
             </template>
            </vue-multiselect>
            <div class="flex flex-row space-x-4 justify-start items-baseline">
                <span>{{__('Emergency:')}}</span>
                <label for="yes" class="flex flex-row space-x-2">
                    <span>{{__('Yes')}}</span>
                    <input type="radio" v-model="analyse.emergency" id="yes" value="1" class="w-5 h-5">
                </label>
                <label for="no" class="flex flex-row space-x-2">
                    <span>{{ __('No')}}</span>
                    <input type="radio" v-model="analyse.emergency" id="no" value="0" class="w-5 h-5">
                </label>
            </div>
            <div>
                <label for="note">{{__('Note')}}</label>
                <textarea class="form-control" v-model="analyse.note" cols="6" id="note"></textarea>
            </div>
            <span class="flex flex-row space-x-3 justify-end">
                    <i class="fas fa-minus-circle text-danger" @click="remove(a)" v-show="a || ( !k && form.analyses.length > 1)"></i>
                    <i class="fas fa-plus-circle text-success" @click="add(a)" v-show="a === form.analyses.length-1"></i>
            </span>

        </div>
        <loading-button :loading="form.processing" class="btn-indigo ml-auto" type="submit">Add Analysis</loading-button>
    </form>
</template>

<script>
    import VueMultiselect from 'vue-multiselect'
    import LoadingButton from "@/Shared/LoadingButton";
    import TextareaInput from "@/Shared/TextareaInput";

    export default {
        name: "Analyse",
        components:{TextareaInput, LoadingButton, VueMultiselect},
        props : {
            consultation: Object,
            category_analyses:Array
        },
        data: function(){
            return{
                modalMa: false,
                form: this.$inertia.form({
                    analyses: [{
                        analyse_id:'',
                        emergency:'',
                        note:'',
                    }],
                    _method: 'post',
                    idCons: this.consultation.slug
                })
            }
        },
        methods:{
            submitAnalyse () {
                this.form.post(route('analyse.add'),{
                    onSuccess: ()=>{
                        this.$emit('closeModal')
                }
                })
            },
            add(index) {
                this.form.analyses.push({
                    analyse_id:'',
                    emergency:'',
                    note:'',
                });
            },
            remove(index) {
                this.form.analyses.splice(index, 1);
            },
        }
    }
</script>

<style scoped>

</style>
