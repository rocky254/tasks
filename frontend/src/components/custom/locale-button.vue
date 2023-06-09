<template>
  <v-menu offset-y :transition="transition">
    <template v-slot:activator="{ on, attrs }">
      <v-btn
        tile
        outlined
        :icon="icon"
        :block="block"
        :class="{ surface2: !text }"
        :large="large"
        text
        @click="goToBookingPage"
        v-bind="attrs"
        v-on="on"
      >
        <v-icon class="me-2" :size="20">mdi-calendar-today</v-icon>
        <span v-if="!icon">{{ currentLang }}</span>
        <v-icon v-if="dropdownIcon" right>mdi-menu-down</v-icon>
      </v-btn>
    </template>
  </v-menu>
</template>

<script>
import GlobalMethods from '../../helpers/global-methods';

export default {
  props: {
    large: {
      type: Boolean,
      default: true,
    },
    text: {
      type: Boolean,
      default: false,
    },
    dropdownIcon: {
      type: Boolean,
      default: false,
    },
    color: {
      default: "strong-text",
    },
    block: {
      type: Boolean,
      default: false,
    },
    icon: {
      type: Boolean,
      default: false,
    },
    transition: {
      default: "slide-y-transition", // 'slide-y-transition'
    },
  },
  data() {
    return {
      langs: [
        { value: "en", text: "English", title: "Book Now" },
      ],
    };
  },
  computed: {
    currentLang() {
      let lang = this.langs.find((l) => l.value === this.current);
      return lang ? lang.title : "";
    },
    current() {
      return this.$i18n.locale;
    },
  },
  methods: {
    changeLocale(locale) {
      if (locale !== this.current) {
        this.$i18n.locale = locale;

        let rtl = locale === "ar";
        this.$vuetify.rtl = rtl;

        this.setSavedLocale(locale);
      }
    },
    goToBookingPage() {
      this.$store.dispatch("SET_LIGHT_NAVBAR", true);
      this.$router.push('/booking');
    },
    setSavedLocale(locale) {
      window.localStorage.setItem("locale", locale);
    },
    checkLocale(locale) {
      return this.langs.some((lang) => lang.value === locale);
    },
    getBrowserLocale() {
      if (window.navigator.languages && window.navigator.languages[0]) {
        let locale = window.navigator.languages[0].split("-")[0];
        if (locale && this.checkLocale(locale)) {
          return locale;
        }
      }
      return null;
    },

    ...GlobalMethods
  },
  created() {
    let savedLocale = window.localStorage.getItem("locale");
    if (savedLocale && this.checkLocale(savedLocale)) {
      if (this.current !== savedLocale) {
        this.changeLocale(savedLocale);
      }
    } else {
      // first time

      let browserLocale = this.getBrowserLocale(),
        locale = browserLocale
          ? browserLocale
          : process.env.VUE_APP_I18N_LOCALE;
      if (this.current !== locale) {
        this.changeLocale(locale);
      } else {
        this.setSavedLocale(locale);
      }
    }
  },
};
</script>