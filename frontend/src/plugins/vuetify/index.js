import Vue from "vue";
import Vuetify from "vuetify/lib";
import preset from './default-preset/preset'

Vue.use(Vuetify);

export default new Vuetify({
  preset,
  icons: {
    iconfont: 'mdiSvg',
  },
  theme: {
    options: {
      customProperties: true,
      variations: false,
    },
    themes: {
      light: {
        primary: "#066a38",
        secondary: "#ad242c",
        dark: "#050A19",
        light: "#f5f8f9",
        "strong-text": "#ddeeff",
        "footer-bg": "#066a38",
        text: "#a8bdd2",
        "text-light": "#646984",

        accent: "#82B1FF",
        error: "#FF5252",
        info: "#2196F3",
        success: "#4CAF50",
        warning: "#FFC107"
      }
    }
  }
});