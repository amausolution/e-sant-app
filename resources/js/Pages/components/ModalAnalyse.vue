<template>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center pt-[20%] lg:pt-[15%] z-50 absolute top-0 right-0 bottom-0 left-0" v-if="showModalAn">
        <div class="bg-white px-16 py-14 rounded-md text-center fixed ">
            <div class="absolute text-gray-600 right-2 top-2 w-8 h-8 hover:text-black cursor-pointer" @click="modalAnalyse">
                <i class="fa-duotone fa-xmark"></i>
            </div>
            <h6 class="card-title m-b-15">{{__('Accompanying details')}}</h6>

            <div>
                <button @click="modalAnalyse"
                    class="px-6 py-2 rounded bg-slate-400 hover:bg-slate-500 text-slate-100"
                >
                    {{__('Close')}}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalAnalyse",
        props: {
            toggleModalAnalyse: Function,
            showModalAn:Boolean,
        }
    }
</script>

<style scoped>

</style>
