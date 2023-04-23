<template>
  <div>
    <v-snackbar
      timeout="2000"
      v-model="alert"
      :color="color"
      :absolute="false"
      :top="true"
    >
      {{ message }}
      <template v-slot:action="{ attrs }">
        <v-btn color="blue" text v-bind="attrs" @click="hideAlert">
          <v-icon small color="white"> mdi-close </v-icon>
        </v-btn>
      </template>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "Alert",
  methods: {
    hideAlert() {
      this.$store.dispatch("alert/CLEAR");
    },
  },
  computed: {
    alert: {
      get() {
        return this.$store.state.alert.show;
      },
      set() {
        setTimeout(() => {
          this.$store.dispatch("alert/CLEAR");
        }, 3000);
      },
    },
    message() {
      return this.$store.state.alert.message;
    },
    color() {
      return this.$store.state.alert.color;
    },
  },
};
</script>
