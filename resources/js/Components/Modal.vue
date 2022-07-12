<template>
    <TransitionRoot appear :show="isOpen" as="template">
        <Dialog as="div" class="relative z-[9990] w-full">
            <TransitionChild
                as="template"
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black bg-opacity-25 "  />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">

                <div
                    class="flex min-h-full items-center justify-center p-4 text-center "
                >
                    <TransitionChild
                        as="template"
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel
                            class="w-full transform overflow-hidden rounded-2xl bg-white p-6 text-left
                             align-middle shadow-xl transition-all relative"
                            :class="maxWidthClass">
                             <slot name="close"></slot>
                            <DialogTitle
                                as="h3"
                                class="text-lg font-medium leading-6 text-gray-900 "
                            >
                                <slot name="title"></slot>
                            </DialogTitle>
                                <slot name="body"></slot>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
    import {
        TransitionRoot,
        TransitionChild,
        Dialog,
        DialogPanel,
        DialogTitle,
    } from '@headlessui/vue'
    import {computed} from "vue";

    const props = defineProps({
        openModal: Function,
        closeModal: Function,
        isOpen:Boolean,
        maxWidth: {
            type: String,
            default: '2xl',
        },
    })
    const maxWidthClass = computed(() => {
        return {
            'sm': 'sm:max-w-sm',
            'md': 'sm:max-w-md',
            'lg': 'sm:max-w-lg',
            'xl': 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl',
        }[props.maxWidth];
    });

</script>

<style scoped>

</style>
