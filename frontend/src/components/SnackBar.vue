<template>
  <div>
    <v-snackbar
      timeout="2000"
      v-model="snackbarShow"
      :color="snackbarColor"
      :absolute="true"
      :top="true"
    >
      {{ snackbarMessage }}
      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="hideSnackbar">
          <v-icon small color="white"> mdi-close </v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "SnackBar",
  methods: {
    hideSnackbar() {
      this.$store.dispatch("alert/CLEAR");
    },
  },
  computed: {
    snackbarShow: {
      get() {
        return this.$store.state.auth.snackbar.show;
      },
      set() {
        this.$store.dispatch('auth/CLEAR_SNACK')
      },
    },
    snackbarMessage() {
      return this.$store.state.auth.snackbar.message;
    },
    snackbarColor() {
      return this.$store.state.auth.snackbar.color;
    },
  },
};
</script>
