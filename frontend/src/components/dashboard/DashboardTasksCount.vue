<template>
  <v-card class="tasks-count-card">
    <v-row class="ma-0 pa-0">
      <v-col cols="8">
        <v-card-title class="text-no-wrap pt-1 ps-2">
          Hello! 🥳
        </v-card-title>
        <v-card-subtitle class="text-no-wrap ps-2">
          You have created
        </v-card-subtitle>
        <v-card-text class="d-flex align-center mt-2 pb-2 ps-2">
          <div>
            <p class="text-xl font-weight-semibold primary--text mb-2">
              {{ tasks }} task(s)
            </p>

            <router-link :to="{ name:'tasks' }">
              <v-btn
                small
                color="primary"
              >
                View Tasks
              </v-btn>
            </router-link>
          </div>
        </v-card-text>
      </v-col>

      <v-col cols="4">
        <v-img
          contain
          height="180"
          width="159"
          :src="require(`@/assets/images/misc/triangle-${$vuetify.theme.dark ? 'dark': 'light'}.png`)"
          class="tasks-count-card-bg"
        ></v-img>
        <v-img
          contain
          height="108"
          max-width="83"
          class="tasks-count-card-trophy"
          src="@/assets/images/misc/trophy.png"
        ></v-img>
      </v-col>
    </v-row>
  </v-card>
</template>

<script>
export default {
  props: {
    tasks: {
      type: Number,
      default: 0,
    }
  },
}
</script>

<style lang="scss" scoped>
.tasks-count-card {
  position: relative;
  .tasks-count-card-bg {
    position: absolute;
    bottom: 0;
    right: 0;
  }
  .tasks-count-card-trophy {
    position: absolute;
    bottom: 10%;
    right: 8%;
  }
}
// rtl
.v-application {
  &.v-application--is-rtl {
    .tasks-count-card-bg {
      right: initial;
      left: 0;
      transform: rotateY(180deg);
    }
    .tasks-count-card-trophy {
      left: 8%;
      right: initial;
    }
  }
}
</style>
