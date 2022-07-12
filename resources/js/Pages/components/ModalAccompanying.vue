<template>
    <div class="bg-slate-800 bg-opacity-50 flex justify-center pt-[20%] lg:pt-[15%] z-50 absolute top-0 right-0 bottom-0 left-0" v-if="showModalAc">
        <div class="bg-white px-16 py-14 rounded-md text-center fixed ">
            <div class="absolute text-gray-600 right-2 top-2 w-8 h-8 hover:text-black cursor-pointer" @click="modalAccompanying">
                <i class="fa-duotone fa-xmark"></i>
            </div>
            <h6 class="card-title m-b-15">{{__('Accompanying details')}}</h6>
            <table class="table table-striped table-border">
                <tbody>
                    <tr>
                        <td class="text-muted">{{__('Accompanying Name')}}</td>
                        <td class="text-right">{{accompanying.accompanying}}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{__('Accompanying Phone')}}</td>
                        <td class="text-right">{{accompanying.accompanying_phone}}</td>
                    </tr>
                    <tr>
                        <td class="text-left text-muted">{{__('Type Piece')}}</td>
                        <td class="text-right">{{accompanying.type_piece}}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">{{__('Accompanying Piece')}}</td>
                        <td class="text-right">{{accompanying.piece_guarantor}}</td>
                    </tr>
                </tbody>
            </table>
            <div>
                <button @click="modalAccompanying"
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
        name: "ModalAccompanying",
        props: {
            accompanying: Array,
            modalAccompanying: Function,
            showModalAc:Boolean,
        }
    }
</script>

<style scoped>

</style>
