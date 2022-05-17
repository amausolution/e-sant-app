<template>
    <Head>
        <title>Your page title</title>
        <meta name="description" content="Your page description">
    </Head>

    <Header />
    <Side />
    <div class="page-wrapper">
         <slot />
    </div>
</template>

<script>
    import { Head } from '@inertiajs/inertia-vue3'
    import Side from '../Shared/Side'
    import { computed } from 'vue'
    import {usePage} from "@inertiajs/inertia-vue3";
    import Header from "../Shared/Header";
    export default {
        components : {Header, Side },
        setup() {
            const user = computed(() => usePage().props.value.appName)
            return {user}
        },
    }
</script>

<style scoped>

</style>
