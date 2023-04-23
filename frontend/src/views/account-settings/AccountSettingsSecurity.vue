<template>
  <v-card flat class="mt-5">
    <v-form ref="form" v-model="valid" lazy-validation>
      <div class="px-3">
        <v-card-text class="pt-5">
          <v-row>
            <v-col cols="12" sm="12" md="12">
              <!-- current password -->
              <v-text-field
                v-model="currentPassword"
                :type="isCurrentPasswordVisible ? 'text' : 'password'"
                :append-icon="isCurrentPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                label="Current Password"
                outlined
                :rules="passwordRules"
                @click:append="isCurrentPasswordVisible = !isCurrentPasswordVisible"
              ></v-text-field>

              <!-- new password -->
              <v-text-field
                v-model="newPassword"
                :type="isNewPasswordVisible ? 'text' : 'password'"
                :append-icon="isNewPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                label="New Password"
                outlined
                :rules="passwordRules"
                persistent-hint
                @click:append="isNewPasswordVisible = !isNewPasswordVisible"
              ></v-text-field>

              <!-- confirm password -->
              <v-text-field
                v-model="cPassword"
                :type="isCPasswordVisible ? 'text' : 'password'"
                :append-icon="isCPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
                label="Confirm New Password"
                outlined
                :rules="passwordRules"
                @click:append="isCPasswordVisible = !isCPasswordVisible"
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
        </v-card-text>
      </div>
    </v-form>
  </v-card>
</template>

<script>
import { mdiKeyOutline, mdiLockOpenOutline, mdiEyeOffOutline, mdiEyeOutline } from "@mdi/js";

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
      isCurrentPasswordVisible: false,
      isNewPasswordVisible: false,
      isCPasswordVisible: false,
      currentPassword: "",
      newPassword: "",
      cPassword: "",
      icons: {
        mdiKeyOutline,
        mdiLockOpenOutline,
        mdiEyeOffOutline,
        mdiEyeOutline,
      },
    }
  },
  methods: {
    resetForm() {
      this.currentPassword = "";
      this.newPassword = "";
      this.cPassword = ""; 
      this.resetValidation();
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

        this.accountDataLocale.current_password = this.currentPassword;
        this.accountDataLocale.password = this.newPassword;
        this.accountDataLocale.password_confirmation = this.cPassword;

        this.$store.dispatch("auth/UPDATE_PROFILE", this.accountDataLocale).then(
          (response) => {
            if (response.success == true) {
              this.$store.dispatch("alert/SUCCESS", "Password Updated!");
              this.loading = false;
            } else {
              this.loading = false;
              this.$store.dispatch("alert/ERROR", response.data.message);
            }
          },
          (error) => {
            this.loading = false;
            if (error.status == 422) {
              this.message = error.response.errors[0].message;
              this.$store.dispatch("alert/ERROR", this.message);
            } else {
              this.message = (error.response && error.response.data) || error.response.data.message || error.toString() || "Internal Server Error";
            }

            this.$store.dispatch("alert/ERROR", this.message.message);
            this.loading = false;
          }
        );
      }
    },
  }
};
</script>

<style lang="scss" scoped>
.two-factor-auth {
  max-width: 25rem;
}
.security-character {
  position: absolute;
  bottom: -0.5rem;
}
</style>
