<template>
  <v-app>
    <v-main>
      <loading v-if="loading" />
      <Navbar :light-theme="lightNavbar" />
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
import Loading from "../components/default/loading";
import Navbar from "../components/main/navbar.vue";
import GlobalComputed from '../helpers/global-computed';

export default {
  components: {
    Navbar,
    Loading
  },
  computed: {
    ...GlobalComputed
  },
}
</script>
