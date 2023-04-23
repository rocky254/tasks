import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import i18n from './i18n';
import * as VueGoogleMaps from "vue2-google-maps";
import VueYouTubeEmbed from 'vue-youtube-embed';

Vue.config.productionTip = false
Vue.use(VueYouTubeEmbed)
Vue.use(VueGoogleMaps, {
  load: {
    key: process.env.VUE_APP_MAPS_API_KEY,
    libraries: "places"
  }
});

new Vue({
  router,
  store,
  vuetify,
  i18n,
  render: h => h(App)
}).$mount("#app");
