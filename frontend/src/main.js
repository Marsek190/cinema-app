import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './App'
import router from './router'
import axios from 'axios';
import VeeValidate, { Validator } from 'vee-validate';
import ru from 'vee-validate/dist/locale/ru';
import "bootstrap/dist/css/bootstrap.css";

Vue.use(VeeValidate)
Vue.use(VueRouter)
Validator.localize('ru', ru)

Vue.config.productionTip = false
const baseUrl = "http://localhost:8000"

window.axios = axios
window.axios.defaults.baseURL = baseUrl

new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>'
})
