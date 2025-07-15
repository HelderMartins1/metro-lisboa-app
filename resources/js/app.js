import 'bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import { createI18n } from 'vue-i18n'
import pt from './locales/pt.json'
import { FontAwesomeIcon } from './fontawesome.js'
import '../css/app.css'

const i18n = createI18n({
    legacy: false,
    locale: navigator.language.slice(0,2) || 'pt',
    fallbackLocale: 'pt',
    messages: {
        pt
    }
})

const app = createApp(App)
app.use(router)
app.use(i18n)
app.component('font-awesome-icon', FontAwesomeIcon)
app.mount('#app')
