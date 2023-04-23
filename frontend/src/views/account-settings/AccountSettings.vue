<template>
  <v-card id="account-setting-card">
    <!-- tabs -->
    <v-tabs v-model="tab" show-arrows>
      <v-tab v-for="tab in tabs" :key="tab.icon">
        <v-icon size="20" class="me-3">
          {{ tab.icon }}
        </v-icon>
        <span>{{ tab.title }}</span>
      </v-tab>
    </v-tabs>

    <!-- tabs item -->
    <v-tabs-items v-model="tab">
      <v-tab-item>
        <account-settings-account
          :account-data="accountSettingData.account"
        ></account-settings-account>
      </v-tab-item>

      <v-tab-item>
        <account-settings-security
          :account-data="accountSettingData.account"
        ></account-settings-security>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>

<script>
import { mdiAccountOutline, mdiLockOpenOutline } from "@mdi/js";
import AccountSettingsAccount from "./AccountSettingsAccount.vue";
import AccountSettingsSecurity from "./AccountSettingsSecurity.vue";
import GlobalComputed from '../../helpers/global-computed';

export default {
  components: {
    AccountSettingsAccount,
    AccountSettingsSecurity,
  },
  computed: {
    ...GlobalComputed
  },
  data() {
    return {
      tab: '',
      tabs: [
        { title: "Account", icon: mdiAccountOutline },
        { title: "Security", icon: mdiLockOpenOutline },
      ],
      accountSettingData: {
        account: {
          id: "",
          name: "",
          email: "",
        },
      }
    }
  },
  created() {
    this.getProfile();
  },
  methods: {
    getProfile() {
      this.accountSettingData.account.id = this.user.id;
      this.accountSettingData.account.name = this.user.name;
      this.accountSettingData.account.email = this.user.email;
    }
  }
};
</script>
