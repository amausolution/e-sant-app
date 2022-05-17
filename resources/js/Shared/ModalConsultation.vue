<template>
    <transition-group name="slide-fade">
        <div class="modal-backdrop " v-if="modalMc">
            <div class="modal"
                 role="dialog"
                 aria-labelledby="modalTitle"
                 aria-describedby="modalDescription"
            >
                <header
                    class="modal-header"
                    id="modalTitle"
                >
                    <slot name="header">
                        {{__('Doing Consultation')}}
                    </slot>
                </header>

                <section
                    class="modal-body mb-4 overflow-y-auto"
                    id="modalDescription"
                >
                    <slot name="body"></slot>
                </section>

                <footer class="modal-footer flex justify-center w-full bg-white mt-12">
                    <slot name="footer"> </slot>
                </footer>
            </div>
        </div>
    </transition-group>
</template>

<script>
    export default {
        name: "ModalConsultation",
        props: ['modalMc','toggleModalConsultation'],
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
        overflow-x: hidden !important;
        overflow-y: hidden !important;
        display: flex;
        flex-direction: column;
    }

    .modal-header,
    .modal-footer {
        padding: 15px;
        display: flex;
        position: fixed;
        bottom: 0;
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
        overflow-x: hidden !important;
        overflow-y: auto !important;
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
    .bounce-enter-active {
        animation: bounce-in 0.5s;
    }
    .bounce-leave-active {
        animation: bounce-in 0.5s reverse;
    }
    @keyframes bounce-in {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.25);
        }
        100% {
            transform: scale(1);
        }
    }
    </style>

