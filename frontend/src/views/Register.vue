<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->
        <v-card-title class="d-flex py-7">
          <router-link
            to="/"
            class="d-flex align-center"
          >
            <h2 class="text-2xl font-weight-semibold">
              Registration Form
            </h2>
          </router-link>
        </v-card-title>

        <!-- title -->
        <v-card-text>
          <p class="text-2xl font-weight-semibold text--primary mb-2">
            Adventure Starts Here 🚀
          </p>
          <p class="mb-2">
            Make your experience easy and fun!
          </p>
        </v-card-text>

        <!-- register form -->
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field
              v-model="user.name"
              outlined
              label="Name"
              placeholder="Name (Optional)"
              class="mb-5"
            ></v-text-field>

            <v-text-field
              v-model="user.email"
              outlined
              label="Email"
              placeholder="Email"
              :rules="emailRules"
              class="mb-5"
            ></v-text-field>

            <v-text-field
              v-model="user.password"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              @click:append="isPasswordVisible = !isPasswordVisible"
              :rules="passwordRules"
              class="mb-5"
            ></v-text-field>

            <v-text-field
              v-model="user.confirmPassword"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Confirm Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              @click:append="isPasswordVisible = !isPasswordVisible"
              :rules="passwordRules"
              class="mb-5"
            ></v-text-field>
          </v-form>
        </v-card-text>

        <!-- login to existing account  -->
        <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
          <span class="me-2">
            Already have an account?
          </span>
          <router-link :to="{ name:'login' }">
            Sign in instead
          </router-link>
        </v-card-text>

        <v-card-actions class="d-flex justify-center">
          <v-btn
            block
            color="primary"
            :loading="loading"
            @click="handleRegister"
          >
            Sign Up
          </v-btn>
        </v-card-actions>
      </v-card>
    </div>

    <!-- background triangle shape  -->
    <img
      class="auth-mask-bg"
      height="190"
      :src="require(`@/assets/images/misc/mask-${$vuetify.theme.dark ? 'dark':'light'}.png`)"
    >

    <!-- tree -->
    <v-img
      class="auth-tree"
      width="247"
      height="185"
      src="@/assets/images/misc/tree.png"
    ></v-img>

    <!-- tree  -->
    <v-img
      class="auth-tree-3"
      width="377"
      height="289"
      src="@/assets/images/misc/tree-3.png"
    ></v-img>
  </div>
</template>

<script>
import User from "../models/user";
import { mdiFacebook, mdiTwitter, mdiGithub, mdiGoogle, mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js';

export default {
  data() {
    return {
      isPasswordVisible: false,
      user: new User("", "", ""),
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
      editedItem: {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
      },
      defaultItem: {
        name: '',
        email: '',
        password: '',
        confirmPassword: '',
      },
      socialLink: [
        {
          icon: mdiFacebook,
          color: '#4267b2',
          colorInDark: '#4267b2',
        },
        {
          icon: mdiTwitter,
          color: '#1da1f2',
          colorInDark: '#1da1f2',
        },
        {
          icon: mdiGithub,
          color: '#272727',
          colorInDark: '#fff',
        },
        {
          icon: mdiGoogle,
          color: '#db4437',
          colorInDark: '#db4437',
        },
      ],
      icons: {
        mdiEyeOutline,
        mdiEyeOffOutline,
      },
    }
  },
  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    handleRegister() {
      this.valid = this.validate();
      if (this.valid && this.user.email && this.user.password && this.user.confirmPassword) {
        this.loading = true;
        this.user.callback = process.env.VUE_APP_EMAIL_URL + "#/reset-password";
        this.$store.dispatch("auth/REGISTER", this.user).then(
          (response) => {
            if (response.success == true) {
              this.$router.push("/login");
              this.loading = false;
            } else {
              console.log(response);
              this.loading = false;
            }
          },
          (error) => {
            this.loading = false;
            console.log(error);
          }
        );
      } else {
        this.loading = false;
      }
    },
  },
}
</script>

<style lang="scss">
@import '~@/plugins/vuetify/default-preset/preset/pages/auth.scss';
</style>
