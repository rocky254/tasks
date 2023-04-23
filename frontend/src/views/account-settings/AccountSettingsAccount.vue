<template>
  <v-card flat class="pa-3 mt-2">
    <v-card-text>
      <v-form class="multi-col-validation mt-6" ref="form" v-model="valid" lazy-validation>
        <v-row>
          <v-col cols="12" md="12" sm="12">
            <v-text-field
              v-model="accountDataLocale.name"
              label="Name"
              outlined
              :rules="nameRules"
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="12" sm="12">
            <v-text-field
              v-model="accountDataLocale.email"
              label="Email"
              outlined
              :rules="emailRules"
              readonly
            ></v-text-field>
          </v-col>

          <v-col cols="12" md="12" sm="12">
            <v-btn 
              color="primary" 
              class="me-3 mt-4"
              :loading="loading"
              @click="updateProfile"
            > 
              Save Changes 
            </v-btn>
            <v-btn
              color="secondary"
              outlined
              class="mt-4"
              type="reset"
              @click.prevent="resetForm"
            >
              Cancel
            </v-btn>
          </v-col>
        </v-row>
      </v-form>
    </v-card-text>
  </v-card>
</template>

<script>
import { mdiCloudUploadOutline } from "@mdi/js";

export default {
  props: {
    accountData: {
      type: Object,
      default: () => {},
    },
  },
  data() {
    return {
      valid: true,
      loading: false,
      passwordRules: [
        (v) => !!v || "Password is invalid",
        (v) => (v && v.length >= 3) || "Min 3 characters",
      ],
      nameRules: [
        (v) => !!v || "Name is required",
        (v) => (v && v.length >= 3) || "Min 3 characters",
      ],
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        (v) => (v && v.length >= 3) || "Min 3 characters",
      ],
      accountDataLocale: JSON.parse(JSON.stringify(this.accountData)),
      icons: {
        mdiCloudUploadOutline,
      },
    }
  },
  methods: {
    resetForm() {
      this.accountDataLocale = JSON.parse(JSON.stringify(this.accountData));  
    },
    validate() {
      return this.$refs.form.validate();
    },
    reset() {
      return this.$refs.form.reset();
    },
    resetValidation() {
      return this.$refs.form.resetValidation();
    },
    updateProfile() {
      this.valid = this.validate();
      if(this.valid) {
        this.loading = true;
        this.$store.dispatch("auth/UPDATE_PROFILE", this.accountDataLocale).then(
          (response) => {
            if (response.success == true) {
              this.$store.dispatch("alert/SUCCESS", "Profile Updated!");
              this.loading = false;
            } else {
              this.loading = false;
              this.$store.dispatch("alert/ERROR", response.message);
            }
          },
          (error) => {
            this.loading = false;
            if (error.status == 422) {
              console.log(error.response.errors);
              this.message = error.response.errors[0].message;
              this.$store.dispatch("alert/ERROR", this.message);
            } else {
              this.message = (error.response && error.response.data) || error.message || error.toString() || "Internal Server Error";
            }
            this.$store.dispatch("alert/ERROR", this.message);
            this.loading = false;
          }
        );
      }
    },
  }
};
</script>
