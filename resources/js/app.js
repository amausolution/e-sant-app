import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from "./Layouts/Layout";
import {translations} from "./Mixins/translation";
createInertiaApp({
    resolve: name => {
        let page = require(`./Pages/${name}`).default;
        if (page.layout === undefined){
            page.layout = Layout;
        }

        return page;
    },
    setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .mixin({methods: {route: window.route}})
            .mixin(translations)
            .use(plugin)
            .mount(el)
    },
})
InertiaProgress.init({
    // The delay after which the progress bar will
    // appear during navigation, in milliseconds.
    //delay: 250,
    zIndex: 1031,
    // The color of the progress bar.
    color: '#dd224e',

    // Whether to include the default NProgress styles.
    includeCSS: true,

    // Whether the NProgress spinner will be shown.
    showSpinner: true,
})
import "../../node_modules/print-js/dist/print.css";
import "../../node_modules/print-js/dist/print";


