import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import * as BootstrapVueNext from 'bootstrap-vue-next'
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'

const app = createApp(App)
// bootstrap-vue-next may export either a default or named exports depending on build.
// Use the default if present, otherwise fall back to the module object.
const BV = (BootstrapVueNext as any).default || BootstrapVueNext
app.use(router)
app.use(BV)
app.mount('#app')