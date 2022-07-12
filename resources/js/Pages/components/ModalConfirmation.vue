<template>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center pt-[20%] lg:pt-[15%] z-50 absolute top-0 right-0 bottom-0 left-0" v-if="showModal">
        <div class="bg-white px-6 py-4 rounded-md text-center fixed ">
            <div class="absolute text-gray-600 right-2 top-2 w-8 h-8 hover:text-black cursor-pointer" @click="modalConfirmation">
                <i class="fa-duotone fa-xmark"></i>
            </div>
            <h6 class="card-title m-b-15">
                <slot name="message">
                    This is the default title!
                </slot>
            </h6>
            <footer class="">
                <slot name="buttons"></slot>
            </footer>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ModalConfirmation",
        props: {
            idA: Number,
            modalConfirmation: Function,
            showModal:Boolean,
        }
    }
</script>

<style scoped>

</style>
