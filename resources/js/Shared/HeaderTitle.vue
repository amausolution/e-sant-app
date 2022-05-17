<template>
    <Head :title="title ? ` ${site}|${title} ` : 'My App'">
        <slot />
    </Head>
</template>

<script>
    import {Head, usePage} from '@inertiajs/inertia-vue3'
    import { computed } from 'vue'

    export default {
        components: {
            Head,
        },
        props: {
            title: String,
        },
        setup() {
            const site = computed(() => usePage().props.value.appName)
            return { site }
        },
    }
</script>

<style scoped>

</style>
