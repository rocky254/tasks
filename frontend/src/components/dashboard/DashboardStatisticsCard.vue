<template>
  <v-card>
    <v-card-title class="align-start">
      <span class="font-weight-semibold">System Statistics</span>
    </v-card-title>

    <v-card-subtitle class="mb-8 mt-n5">
      <span class="font-weight-semibold text--primary me-1">A Total of {{ assigned }} users have been assigned tasks in the system</span>
      <span>😎 so far</span>
    </v-card-subtitle>

    <v-card-text>
      <v-row>
        <v-col
          v-for="data in statisticsData"
          :key="data.title"
          cols="6"
          md="3"
          class="d-flex align-center"
        >
          <v-avatar
            size="44"
            :color="resolveStatisticsIconVariation (data.title).color"
            rounded
            class="elevation-1"
          >
            <v-icon
              dark
              color="white"
              size="30"
            >
              {{ resolveStatisticsIconVariation (data.title).icon }}
            </v-icon>
          </v-avatar>
          <div class="ms-3">
            <p class="text-xs mb-0">
              {{ data.title }}
            </p>
            <h3 class="text-xl font-weight-semibold">
              {{ users }} Users
            </h3>
          </div>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>

<script>
// eslint-disable-next-line object-curly-newline
import { mdiAccountOutline } from '@mdi/js'

export default {
  props: {
    assigned: {
      type: Number,
      default: 0,
    },
    users: {
      type: Number,
      default: 0,
    },
  },
  data: () => ({
    statisticsData: [
      {
        title: 'Registered Users',
      }
    ],
    icons: {
      mdiAccountOutline,
    },
  }),
  methods: {
    resolveStatisticsIconVariation(data) {
      if (data === 'System Users') return { icon: mdiAccountOutline, color: 'primary' }
      return { icon: mdiAccountOutline, color: 'success' }
    }
  },
}
</script>
