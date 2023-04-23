<template>
  <v-row>
    <v-col
      cols="12"
      md="4"
    >
      <dashboard-tasks-count :tasks="tasksCount"></dashboard-tasks-count>
    </v-col>
    <v-col
      cols="12"
      md="8"
    >
      <dashboard-statistics-card :assigned="assignedCount" :users="usersCount"></dashboard-statistics-card>
    </v-col>
    <v-col cols="12">
      <dashboard-datatable :tasks="tasks" :loading="tasksLoading" :items="items"></dashboard-datatable>
    </v-col>
  </v-row>
</template>

<script>
import DashboardTasksCount from '../components/dashboard/DashboardTasksCount.vue';
import DashboardStatisticsCard from '../components/dashboard/DashboardStatisticsCard.vue';
import DashboardDatatable from '../components/dashboard/DashboardDatatable.vue';
import TasksService from '../services/tasks.service';
import GlobalMethods from '../helpers/global-methods';
import UsersService from '../services/users.service';
import StatusService from '../services/status.service';

export default {
  components: {
    DashboardTasksCount,
    DashboardStatisticsCard,
    DashboardDatatable,
  },
  data: () => ({
    tasksCount: 0,
    usersCount: 0,
    assignedCount: 0,
    tasksLoading: true,
    pageLoading: true,
    tasks: [],
    items: [],
  }),
  mounted() {
    this.index();
    this.getAllTasks();
    this.getSystemUsers();
    this.getAssignedTasksCount();
    this.getAllItems();
  },
  methods: {
    ...GlobalMethods,
    index() {
      return TasksService.tasksCount().then(
        (response) => {
          if (response.status == 200) {
            this.tasksCount = response.data.data;
            this.pageLoading = false;
          } else {
            this.tasksCount = 0;
            this.pageLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.tasksCount = 0;
          this.pageLoading = false;
          if (error.response.status == 401) {
            this.handleLogout();
          }

          this.$store.dispatch("alert/ERROR", error.response.data.message);
        }
      );
    },
    getSystemUsers() {
      return UsersService.usersCount().then(
        (response) => {
          if (response.status == 200) {
            this.usersCount = response.data.data;
            this.pageLoading = false;
          } else {
            this.usersCount = 0;
            this.pageLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.usersCount = 0;
          this.pageLoading = false;
          if (error.response.status == 401) {
            this.handleLogout();
          }

          this.$store.dispatch("alert/ERROR", error.response.data.message);
        }
      );
    },
    getAssignedTasksCount() {
      return TasksService.assignedTasksCount().then(
        (response) => {
          if (response.status == 200) {
            this.assignedCount = response.data.data;
            this.pageLoading = false;
          } else {
            this.assignedCount = 0;
            this.pageLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.assignedCount = 0;
          this.pageLoading = false;
          if (error.response.status == 401) {
            this.handleLogout();
          }

          this.$store.dispatch("alert/ERROR", error.response.data.message);
        }
      );
    },
    getAllItems() {
      return StatusService.index().then(
        (response) => {
          if (response.status == 200) {
            this.items = response.data.data;
          } else {
            this.items = [];
            console.log(response.data.error);
          }
        },
        (error) => {
          this.items = [];
          console.log(error);
        }
      );
    },
    getAllTasks() {
      this.tasksLoading = true;
      return TasksService.recentTasks().then(
        (response) => {
          if (response.status == 200) {
            this.tasks = response.data.data;
            this.tasksLoading = false;
          } else {
            this.tasks = [];
            this.tasksLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.tasks = [];
          this.tasksLoading = false;
          if (error.response.status == 401) {
            this.handleLogout();
          }

          this.$store.dispatch("alert/ERROR", error.response.data.message);
        }
      );
    },
  },
}
</script>
