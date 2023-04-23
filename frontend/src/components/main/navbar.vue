<template>
  <v-app-bar
    :light="light"
    :height="scrolled ? navbarHeight : pcOnly ? ($route.name == 'founder' ? 100 : 130) : 100"
    class="navbar"
    :class="{
      blank: !scrolled && !light && $route.name != 'founder',
      'soft-shadow': light,
      'dark-gradient': scrolled && !light || !scrolled && !light && $route.name == 'founder',
    }"
    :flat="!light"
    fixed
    :color="light ? 'white' : 'dark'"
    :dark="!light"
  >
    <v-container class="d-flex align-center">
      <div>
        <!-- logo -->
        <logo :light="light" :scrolled="scrolled" :width="pcOnly ? 140 : 125" />
      </div>
    </v-container>
  </v-app-bar>
</template>

<script>
import Logo from "./logo";
import GlobalComputed from "@/helpers/global-computed";
import GlobalMethods from "@/helpers/global-methods";
import links from "@/helpers/links";
import founders from "@/helpers/founders";

export default {
  name: "navbar",
  components: { Logo },
  props: {
    lightTheme: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      scrolled: false,
      links,
      founders,
    };
  },
  computed: {
    light() {
      return this.lightTheme && this.scrolled;
    },
    activeSection() {
      return this.$store.state.activeSection;
    },
    ...GlobalComputed,
  },
  methods: {
    handleScroll() {
      this.scrolled = window.scrollY > 0;
    },

    ...GlobalMethods,
  },
  mounted() {
    window.addEventListener("scroll", this.handleScroll);
    this.handleScroll();
  },
};
</script>

<style lang="scss">
.v-application .navbar {
  &,
  .v-toolbar__content {
    transition: height 0.2s ease-out, background-color 0.1s linear !important;
  }

  &.soft-shadow {
    box-shadow: 0 0 transparent, 0 0 transparent,
      0 5px 5px -4px rgba(0, 0, 0, 0.1) !important;
  }

  &.dark-gradient {
    box-shadow: 0 1px 8px 0px rgba(0, 0, 0, 0.2) !important;
  }

  &.blank {
    &,
    .v-toolbar__content {
      background: none !important;
      border: none !important;
      box-shadow: none !important;
    }
  }

  .v-btn {
    font-weight: normal !important;
    text-transform: capitalize;
    letter-spacing: 1.1px;
  }
}
</style>