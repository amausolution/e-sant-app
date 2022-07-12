import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/inertia-vue3'
import { InertiaProgress } from '@inertiajs/progress'
import Layout from "./Layouts/Layout";
import {translations} from "./Mixins/translation";
import CKEditor from '@ckeditor/ckeditor5-vue';
import VCalendar from 'v-calendar';
import 'v-calendar/dist/style.css';
import '../../public/partner/assets/js/app.js'
import VueMoment from 'vue-moment'
import moment from 'moment'
// Load Locales ('en' comes loaded by default)
require('moment/locale/fr');
import NProgress from 'nprogress'
import { Inertia } from '@inertiajs/inertia'

Inertia.on('start', () => NProgress.start())
Inertia.on('finish', () => NProgress.done())
// Choose Locale
moment.locale('fr');
const app = createInertiaApp({
    resolve: name => {
        let page = require(`./Pages/${name}`).default;
        if (page.layout === undefined){
            page.layout = Layout;
        }
        return page;
    },
    setup: function ({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .mixin({methods: {route: window.route}})
            .mixin(translations)
            .use(plugin)
            .use(CKEditor)
            .use(moment)
            .use(VCalendar, {})
            .mount(el)
    },
})
/*
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
*/
import "../../node_modules/print-js/dist/print.css";
import "../../node_modules/print-js/dist/print";


