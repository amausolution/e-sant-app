<template>
    <Link :disabled="$page.component === 'Partner/Setting/Info' ? true : false " as="button" type="button" :href="route('setting.index')"
          class="px-6 py-2 text-sm rounded shadow bg-slate-100 hover:bg-slate-200 text-slate-500"
    >
        {{__('Company Setting')}}
    </Link>
    <Link :disabled="$page.component === 'Partner/Setting/Email' ? true : false " as="button" type="button"  :href="route('setting.email')"
          class="px-6 py-2 text-sm text-orange-500 bg-orange-100 rounded shadow hover:bg-orange-200"
    >
        {{__('Email Setting')}}
    </Link>
    <Link :disabled="$page.component === 'Partner/Setting/Localisation' ? true : false " as="button" type="button"  :href="route('setting.localisation')"
          class="px-6 py-2 text-sm rounded shadow bg-emerald-100 hover:bg-emerald-200 text-emerald-500"
    >
        {{__('Localisation')}}
    </Link>
    <Link :disabled="$page.component === 'Partner/Setting/Invoice' ? true : false " as="button" type="button"  :href="route('setting.invoice')"
          class="px-6 py-2 text-sm rounded shadow bg-cyan-100 hover:bg-cyan-200 text-cyan-500"
    >
        {{__('Invoice Setting')}}
    </Link>
    <Link :disabled="$page.component === 'Partner/Setting/Salary' ? true : false " as="button" type="button"  :href="route('setting.salary')"
          class="px-6 py-2 text-sm rounded shadow bg-violet-100 hover:bg-violet-200 text-violet-500"
    >
        {{__('Salary Setting')}}
    </Link>
    <Link :disabled="$page.component === 'Partner/Setting/Equipment' ? true : false " as="button" type="button"  :href="route('setting.equipment')"
          class="px-6 py-2 text-sm rounded shadow bg-violet-100 hover:bg-violet-200 text-violet-500"
    >
        {{__('Equipment Setting')}}
    </Link>
</template>

<script>
    import {Link} from "@inertiajs/inertia-vue3"
    import { usePage as $page } from '@inertiajs/inertia-vue3'
    export default {
        name: "NaveBar",
        components : {Link}
    }
</script>

<style scoped>

</style>
