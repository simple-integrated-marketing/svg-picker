Vue = window.Vue;
// import Vue from 'vue';
// Vue.config.devtools = true;
import App from './App.vue'
import VueClipboard from 'vue-clipboard2'
Vue.use(VueClipboard);



new Vue({
  el: '#settings-svg-picker-settings',
  render: h => h(App)
})

document.getElementById("settings-json").parentNode.removeChild(document.getElementById("settings-json"));
