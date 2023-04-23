<template>
  <v-card>
    <v-data-table
      :headers="headers"
      :items="tasks"
      item-key="id"
      class="table-rounded elevation-1"
      hide-default-footer
      disable-sort
      :custom-filter="filterText"
    >
      <template v-slot:top>
        <v-toolbar flat class="pt-2">
          <v-col cols="12" sm="5" class="mt-5">
            <v-toolbar-title
              class="primary--text font-weight-bold text--darken-1 justify-center"
            >
              <v-icon class="mr-2 mt-n1" color="primary">mdi-calendar-check-outline</v-icon>Tasks
            </v-toolbar-title>
            <p class="grey--text ml-8 mt-n1">List of all Recent Tasks</p>
            <v-divider class="mx-4" inset vertical></v-divider>
          </v-col>

          <v-spacer></v-spacer>
          <v-col cols="12" sm="3" class="mt-5">
            <v-text-field
              label="Search"
              v-model="search"
              append-icon="mdi-magnify"
              dense
              small
              color="primary"
              outlined
            ></v-text-field>
          </v-col>
        </v-toolbar>
        <v-progress-linear
          indeterminate
          color="primary"
          v-if="loading"
        ></v-progress-linear>
      </template>
      <!-- Status -->
      <template #[`item.status_id`]="{ item }">
        <v-chip
          small
          :color="statusColor(item.status_id)"
          class="font-weight-medium"
        >
          {{ getStatusName(item.status_id, items) }}
        </v-chip>
      </template>
    </v-data-table>
  </v-card>
</template>

<script>
import GlobalMethods from '../../helpers/global-methods';

export default {
  props: {
    tasks: {
      type: Array,
      default: () => [],
    },
    items: {
      type: Array,
      default: () => [],
    },
    loading: {
      type: Boolean,
      default: true,
    },
  },
  data: () => ({
    search: "",
    headers: [
      { text: 'Name', value: 'name' },
      { text: 'Due Date', value: 'due_date' },
      { text: 'Start Date', value: 'start_date' },
      { text: 'End Date', value: 'end_date' },
      { text: 'Status', value: 'status_id' },
    ],
  }),
  methods: {
    ...GlobalMethods,
  }
}
</script>
