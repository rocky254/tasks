<template>
  <v-navigation-drawer
    :value="isDrawerOpen"
    app
    floating
    width="260"
    class="app-navigation-menu"
    :right="$vuetify.rtl"
    @input="(val) => $emit('update:is-drawer-open', val)"
  >
    <!-- Navigation Header -->
    <div class="vertical-nav-header d-flex items-center ps-6 pe-5 pt-5">
      <router-link to="/" class="d-flex align-center text-decoration-none">
        <v-slide-x-transition>
          <!-- logo -->
          <logo :light="light" :scrolled="scrolled" :width="pcOnly ? 200 : 185" />
        </v-slide-x-transition>
      </router-link>
    </div>

    <!-- Navigation Items -->
    <v-list expand shaped class="vertical-nav-menu-items pr-5">
      <nav-menu-link
        title="Dashboard"
        :to="{ name: '/' }"
        :icon="icons.mdiHomeOutline"
      ></nav-menu-link>
      <nav-menu-link
        title="All Tasks"
        :to="{ name: 'tasks' }"
        :icon="icons.mdiCalendarCheckOutline"
      ></nav-menu-link>
      <nav-menu-link
        title="Task Status"
        :to="{ name: 'status' }"
        :icon="icons.mdiListStatus"
      ></nav-menu-link>
      <nav-menu-link
        title="Account Settings"
        :to="{ name: 'account-settings' }"
        :icon="icons.mdiAccountCogOutline"
      ></nav-menu-link>
    </v-list>
  </v-navigation-drawer>
</template>

<script>
import { mdiHomeOutline, mdiCalendarCheckOutline, mdiListStatus, mdiAccountCogOutline } from "@mdi/js";
import NavMenuLink from "./components/NavMenuLink.vue";
import logo from '../../../components/main/logo.vue';
import GlobalComputed from "@/helpers/global-computed";

export default {
  components: {
    NavMenuLink,
    logo,
  },
  props: {
    isDrawerOpen: {
      type: Boolean,
      default: null,
    },
    lightTheme: {
      type: Boolean,
      default: false,
    },
  },
  computed: {
    ...GlobalComputed,
    light() {
      return this.lightTheme && this.scrolled;
    },
  },
  data() {
    return {
      scrolled: false,
      icons: {
        mdiHomeOutline,
        mdiCalendarCheckOutline,
        mdiAccountCogOutline,
        mdiListStatus,
      },
    }
  }
};
</script>

<style lang="scss" scoped>
.app-title {
  font-size: 1.25rem;
  font-weight: 700;
  font-stretch: normal;
  font-style: normal;
  line-height: normal;
  letter-spacing: 0.3px;
}

// ? Adjust this `translateX` value to keep logo in center when vertical nav menu is collapsed (Value depends on your logo)
.app-logo {
  transition: all 0.18s ease-in-out;
  .v-navigation-drawer--mini-variant & {
    transform: translateX(-4px);
  }
}

@include theme(app-navigation-menu) using ($material) {
  background-color: map-deep-get($material, "background");
}

.app-navigation-menu {
  .v-list-item {
    &.vertical-nav-menu-link {
      ::deep .v-list-item__icon {
        .v-icon {
          transition: none !important;
        }
      }
    }
  }
}
.app-navigation-menu {
  .upgrade-banner {
    position: absolute;
    bottom: 13px;
    left: 50%;
    transform: translateX(-50%);
  }
}
</style>
