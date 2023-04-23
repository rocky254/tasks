<template>
  <v-menu
    offset-y
    left
    nudge-bottom="14"
    min-width="230"
    content-class="user-profile-menu-content"
  >
    <template #activator="{ on, attrs }">
      <v-badge
        bottom
        color="success"
        overlap
        offset-x="12"
        offset-y="12"
        class="ms-4"
        dot
      >
        <v-avatar size="40px" v-bind="attrs" v-on="on">
          <v-img :src="require('@/assets/images/avatars/1.png')"></v-img>
        </v-avatar>
      </v-badge>
    </template>
    <v-list>
      <div class="pb-3 pt-2">
        <v-badge
          bottom
          color="success"
          overlap
          offset-x="12"
          offset-y="12"
          class="ms-4"
          dot
        >
          <v-avatar size="40px">
            <v-img :src="require('@/assets/images/avatars/1.png')"></v-img>
          </v-avatar>
        </v-badge>
        <div
          class="d-inline-flex flex-column justify-center ms-3"
          style="vertical-align: middle"
        >
          <span class="text--primary font-weight-semibold mb-n1">
            {{ user.name }}
          </span>
          <small class="text--disabled">
            {{ user.email }}
          </small>
        </div>
      </div>

      <v-divider></v-divider>

      <!-- Profile -->
      <v-list-item link @click="goToAccountSettings">
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            {{ icons.mdiAccountOutline }}
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Account Settings</v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <!-- Logout -->
      <v-list-item link @click="handleLogout">
        <v-list-item-icon class="me-2">
          <v-icon size="22">
            {{ icons.mdiLogoutVariant }}
          </v-icon>
        </v-list-item-icon>
        <v-list-item-content>
          <v-list-item-title>Logout</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-menu>
</template>

<script>
import { mdiAccountOutline, mdiLogoutVariant } from "@mdi/js";
import GlobalMethods from '../../helpers/global-methods';
import GlobalComputed from '../../helpers/global-computed';

export default {
  data() {
    return {
      icons: {
        mdiAccountOutline,
        mdiLogoutVariant,
      },
    };
  },
  computed: {
    ...GlobalComputed
  },
  methods: {
    ...GlobalMethods
  },
};
</script>

<style lang="scss">
.user-profile-menu-content {
  .v-list-item {
    min-height: 2.5rem !important;
  }
}
</style>
