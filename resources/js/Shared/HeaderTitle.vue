<template>
    <Head >
        <title>{{ title ? ` ${site.title} - ${title} ` : 'E-sante'}}</title>
        <link rel="icon" type="image/svg+xml" :href="favicon" />
        <meta head-key="description" name="description" content="This is a page specific description" />
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
            const site = computed(() => usePage().props.value.auth.partner)
            const favicon = computed(() => usePage().props.value.favicon)

            return { site, favicon }
        },
    }
</script>

<style scoped>

</style>
