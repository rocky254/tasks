<template>
  <div class="auth-wrapper auth-v1">
    <div class="auth-inner">
      <v-card class="auth-card">
        <!-- logo -->
        <v-card-title class="d-flex align-center justify-center py-7">
          <router-link
            to="/"
            class="d-flex align-center"
          >
            <h2 class="text-2xl font-weight-semibold">
              SIGN IN
            </h2>
          </router-link>
        </v-card-title>

        <!-- title -->
        <v-card-text>
          <p class="text-2xl font-weight-semibold text--primary mb-2">
            Welcome to our Portal! 👋🏻
          </p>
          <p class="mb-2">
            Please sign-in to your account
          </p>
        </v-card-text>

        <!-- login form -->
        <v-card-text>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field
              v-model="user.email"
              outlined
              label="Email"
              placeholder="Email"
              class="mb-5"
              :rules="emailRules"
            ></v-text-field>

            <v-text-field
              v-model="user.password"
              outlined
              :type="isPasswordVisible ? 'text' : 'password'"
              label="Password"
              placeholder="············"
              :append-icon="isPasswordVisible ? icons.mdiEyeOffOutline : icons.mdiEyeOutline"
              @click:append="isPasswordVisible = !isPasswordVisible"
              class="mb-5"
              :rules="passwordRules"
            ></v-text-field>

            <div class="d-flex align-center justify-space-between flex-wrap">
              <v-checkbox
                label="Remember Me"
                hide-details
                class="me-3 mt-1"
              >
              </v-checkbox>

              <!-- forgot link -->
              <a
                href="javascript:void(0)"
                class="mt-1"
              >
                Forgot Password?
              </a>
            </div>
          </v-form>
        </v-card-text>

        <!-- social links -->
        <v-card-actions class="d-flex justify-center">
          <v-btn
            block
            color="primary"
            class="mt-6"
            :loading="loading"
            @click="handleLogin"
          >
            Login
          </v-btn>
        </v-card-actions>

        <!-- create new account  -->
        <v-card-text class="d-flex align-center justify-center flex-wrap mt-2">
          <span class="me-2">
            Don't have an account?
          </span>
          <router-link :to="{ name:'register' }">
            Sign up now
          </router-link>
        </v-card-text>
      </v-card>
    </div>

    <!-- background triangle shape  -->
    <img
      class="auth-mask-bg"
      height="173"
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
import { mdiFacebook, mdiTwitter, mdiGithub, mdiGoogle, mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js';
import User from "../models/user";

export default {
  data() {
    return {
      isPasswordVisible: false,
      email: '',
      password: '',
      user: new User("", "", ""),
      confirmPassword: '',
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
    handleLogin() {
      this.valid = this.validate();
      if(this.valid && this.user.email && this.user.password) {
        this.loading = true;
        this.$store.dispatch("auth/LOGIN", this.user).then(
          (response) => {
            if(response.success == true) {
              setTimeout(() => {
                this.loading = false;
                this.$router.push("/");
              }, 1500);
            } else {
              this.loading = false;
            }
          },
          (error) => {
            this.loading = false;
            console.log(error);
          }
        );
      }
    },
  },
}
</script>

<style lang="scss">
@import '~@/plugins/vuetify/default-preset/preset/pages/auth.scss';
</style>