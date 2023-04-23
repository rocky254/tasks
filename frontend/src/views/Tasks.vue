<template>
  <div>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="tasks"
        item-key="id"
        class="table-rounded elevation-1"
        hide-default-footer
        disable-sort
        :search="search"
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
              <p class="grey--text ml-8 mt-n1">List of all Tasks</p>
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
            <v-col cols="12" sm="2">
              <v-btn 
                tile 
                color="secondary"
                class="text-capitalize text-white mt-n2"
                @click="newTask"
              >
                <span>Add Task</span>
                <v-icon class="ms-3">mdi-plus-box-multiple-outline</v-icon>
              </v-btn>
            </v-col>
          </v-toolbar>
          <v-progress-linear
            indeterminate
            color="primary"
            v-if="pageLoading"
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
        <!-- Actions -->
        <template v-slot:[`item.actions`]="{ item }">
          <template>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <span class="d-inline-block mb-1" v-bind="attrs" v-on="on">
                  <v-btn
                    color="info darken-2"
                    class="text-capitalize mx-1"
                    depressed
                    fab
                    x-small
                    dark
                    @click="editTask(item)"
                  >
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Edit Task</span>
            </v-tooltip>
          </template>
          <template>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <span class="d-inline-block mb-1" v-bind="attrs" v-on="on">
                  <v-btn
                    color="error darken-2"
                    class="text-capitalize mx-1"
                    depressed
                    fab
                    x-small
                    dark
                    @click="deleteTask(item)"
                  >
                    <v-icon small>mdi-delete-alert-outline</v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Delete Task</span>
            </v-tooltip>
          </template>
        </template>
      </v-data-table>
    </v-card>

    <div class="text-center">
      <v-dialog v-model="dialog" scrollable persistent max-width="750">
        <v-card>
          <v-card-title class="error--text text-darken-1 align-center">{{
            formTitle
          }}</v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container>
              <v-form ref="form" v-model="valid">
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      outlined
                      label="Task Name"
                      placeholder="Task Name"
                      :rules="rules.required"
                      v-model="editedItem.name"
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-textarea
                      outlined
                      label="Task Description"
                      placeholder="Task Description"
                      :rules="rules.required"
                      v-model="editedItem.description"
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <!-- Due Date -->
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-menu
                      ref="menu_due_date"
                      v-model="menu_due_date"
                      :close-on-content-click="true"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="editedItem.due_date"
                          :value="computedDueDate"
                          label="Due Date"
                          persistent-hint
                          append-icon="mdi-calendar"
                          v-bind="attrs"
                          v-on="on"
                          outlined
                          dense
                          color="grey"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="dueDateWatcher"
                        no-title
                        @change="menu_closing_date = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>

                  <!-- Due Time -->
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-menu
                      ref="menu_due_time"
                      v-model="menu_due_time"
                      :close-on-content-click="false"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="editedItem.due_time"
                          :value="computedDueTime"
                          label="Due Time"
                          append-icon="mdi-clock-time-four-outline"
                          persistent-hint
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          outlined
                          dense
                          color="grey"
                        ></v-text-field>
                      </template>
                      <v-time-picker
                        v-model="dueTimeWatcher"
                        full-width
                        @change="menu_due_time = false"
                      ></v-time-picker>
                    </v-menu>
                  </v-col>
                </v-row>
                <v-row>
                  <!-- Task Status -->
                  <v-col cols="12">
                    <v-select
                      v-model="editedItem.status_id"
                      :items="items"
                      label="Status"
                      color="grey"
                      dense
                      outlined
                      :rules="statusRules"
                      item-value="id"
                      item-text="name"
                    ></v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12" sm="12" md="12">
                    <v-autocomplete
                      v-model="editedItem.selected_users"
                      :value="editedItem.selected_users"
                      :items="users"
                      label="Select Users"
                      item-text="email"
                      item-value="id"
                      multiple
                      outlined
                      chips
                      color="grey"
                      item-color="grey"
                    >
                      <template v-slot:prepend-item>
                        <v-list-item ripple @click="toggleUsers">
                          <v-list-item-action>
                            <v-icon
                              :color="editedItem.selected_users.length > 0 ? 'error' : ''"
                            >
                              {{ iconUsers }}
                            </v-icon>
                          </v-list-item-action>
                          <v-list-item-content>
                            <v-list-item-title>
                              Select All
                            </v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                        <v-divider class="mt-2"></v-divider>
                      </template>
                      <template v-slot:append-item>
                        <v-divider class="mb-2"></v-divider>
                        <v-list-item disabled>
                          <v-list-item-avatar color="grey lighten-3">
                            <v-icon> mdi-file-outline </v-icon>
                          </v-list-item-avatar>
                          <v-list-item-content
                            v-if="likesAllUsers"
                          >
                            <v-list-item-title>
                              All Users Selected for This Task!
                            </v-list-item-title>
                          </v-list-item-content>

                          <v-list-item-content
                            v-else-if="likesSomeUsers"
                          >
                            <v-list-item-title>
                              Users Count
                            </v-list-item-title>
                            <v-list-item-subtitle>
                              {{ editedItem.selected_users.length }}
                            </v-list-item-subtitle>
                          </v-list-item-content>

                          <v-list-item-content v-else>
                            <v-list-item-title>
                              Which users do you want to assign this task?
                            </v-list-item-title>
                            <v-list-item-subtitle>
                              Go ahead, make a selection above!
                            </v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </template>
                    </v-autocomplete>
                  </v-col>
                </v-row>
                <v-row>
                  <!-- Start Date -->
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-menu
                      ref="menu_start_date"
                      v-model="menu_start_date"
                      :close-on-content-click="true"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="editedItem.start_date"
                          :value="computedStartDate"
                          label="Start Date"
                          persistent-hint
                          append-icon="mdi-calendar"
                          v-bind="attrs"
                          v-on="on"
                          outlined
                          dense
                          color="grey"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="startDateWatcher"
                        no-title
                        @change="menu_start_date = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>

                  <!-- Start Time -->
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-menu
                      ref="menu_start_time"
                      v-model="menu_start_time"
                      :close-on-content-click="false"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="editedItem.start_time"
                          :value="computedStartTime"
                          label="Start Time"
                          append-icon="mdi-clock-time-four-outline"
                          persistent-hint
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          outlined
                          dense
                          color="grey"
                        ></v-text-field>
                      </template>
                      <v-time-picker
                        v-model="startTimeWatcher"
                        full-width
                        @change="menu_start_time = false"
                      ></v-time-picker>
                    </v-menu>
                  </v-col>
                </v-row>
                <v-row>
                  <!-- End Date -->
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-menu
                      ref="menu_end_date"
                      v-model="menu_end_date"
                      :close-on-content-click="true"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="editedItem.end_date"
                          :value="computedEndDate"
                          label="End Date"
                          persistent-hint
                          append-icon="mdi-calendar"
                          v-bind="attrs"
                          v-on="on"
                          outlined
                          dense
                          color="grey"
                        ></v-text-field>
                      </template>
                      <v-date-picker
                        v-model="endDateWatcher"
                        no-title
                        @change="menu_end_date = false"
                      ></v-date-picker>
                    </v-menu>
                  </v-col>

                  <!-- End Time -->
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-menu
                      ref="menu_end_time"
                      v-model="menu_end_time"
                      :close-on-content-click="false"
                      transition="scale-transition"
                      offset-y
                      max-width="290px"
                      min-width="290px"
                    >
                      <template v-slot:activator="{ on, attrs }">
                        <v-text-field
                          v-model="editedItem.end_time"
                          :value="computedEndTime"
                          label="End Time"
                          append-icon="mdi-clock-time-four-outline"
                          persistent-hint
                          readonly
                          v-bind="attrs"
                          v-on="on"
                          outlined
                          dense
                          color="grey"
                        ></v-text-field>
                      </template>
                      <v-time-picker
                        v-model="endTimeWatcher"
                        full-width
                        @change="menu_end_time = false"
                      ></v-time-picker>
                    </v-menu>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-textarea
                      outlined
                      label="Task Remarks"
                      placeholder="Task Remarks"
                      :rules="rules.required"
                      v-model="editedItem.remarks"
                    />
                  </v-col>
                </v-row>
              </v-form>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn 
              x-large 
              tile 
              color="error"
              class="text-capitalize mx-1"
              @click="close"
            >
              <span>Close</span>
              <v-icon class="ms-3">mdi-cancel</v-icon>
            </v-btn>
            <v-btn 
              x-large 
              tile 
              color="primary"
              class="text-capitalize mx-1"
              @click="save"
              :loading="submitLoading"
            >
              <span>Submit</span>
              <v-icon class="ms-3">mdi-content-save-outline</v-icon>
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  </div>
</template>

<script>
import TaskService from '../services/tasks.service';
import UsersService from '../services/users.service';
import StatusService from '../services/status.service';
import moment from "moment";
import GlobalMethods from '../helpers/global-methods';
import GlobalComputed from '../helpers/global-computed';

export default {
  data: (vm) => {
    return {
      search: "",
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Due Date', value: 'due_date' },
        { text: 'Start Date', value: 'start_date' },
        { text: 'End Date', value: 'end_date' },
        { text: 'Status', value: 'status_id' },
        { text: 'Actions', value: 'actions' },
      ],
      items: [],
      users: [],
      tasks: [],
      pageLoading: true,
      valid: false,
      submitLoading: false,
      dialog: false,
      dueTimeWatcher: new Date().toTimeString().substr(0, 8),
      menu_due_time: false,
      dueDateWatcher: new Date().toISOString().substr(0, 10),
      menu_due_date: false,
      startTimeWatcher: new Date().toTimeString().substr(0, 8),
      menu_start_time: false,
      startDateWatcher: new Date().toISOString().substr(0, 10),
      menu_start_date: false,
      endTimeWatcher: new Date().toTimeString().substr(0, 8),
      menu_end_time: false,
      endDateWatcher: new Date().toISOString().substr(0, 10),
      menu_end_date: false,
      editedItem: {
        name: '',
        description: '',
        due_time: vm.formatTimeDefault(new Date().setHours(12, 0, 0, 0)),
        due_date: vm.formatDate(new Date(Date.now() + (1 * 24 * 60 * 60 * 1000)).toISOString().substr(0, 10)),
        status_id: null,
        selected_users: [],
        start_time: vm.formatTimeDefault(new Date().setHours(12, 0, 0, 0)),
        start_date: vm.formatDate(new Date(Date.now() + (1 * 24 * 60 * 60 * 1000)).toISOString().substr(0, 10)),
        end_time: vm.formatTimeDefault(new Date().setHours(12, 0, 0, 0)),
        end_date: vm.formatDate(new Date(Date.now() + (1 * 24 * 60 * 60 * 1000)).toISOString().substr(0, 10)),
        remarks: '',
      },
      defaultItem: {
        name: '',
        description: '',
        due_time: vm.formatTimeDefault(new Date().setHours(12, 0, 0, 0)),
        due_date: vm.formatDate(new Date(Date.now() + (1 * 24 * 60 * 60 * 1000)).toISOString().substr(0, 10)),
        status_id: null,
        selected_users: [],
        start_time: vm.formatTimeDefault(new Date().setHours(12, 0, 0, 0)),
        start_date: vm.formatDate(new Date(Date.now() + (1 * 24 * 60 * 60 * 1000)).toISOString().substr(0, 10)),
        end_time: vm.formatTimeDefault(new Date().setHours(12, 0, 0, 0)),
        end_date: vm.formatDate(new Date(Date.now() + (1 * 24 * 60 * 60 * 1000)).toISOString().substr(0, 10)),
        remarks: '',
      },
      editedIndex: -1,
      statusRules: [(v) => !!v || "Task Status is Required"],
      rules: {
        select: [(v) => !!v || "An item should be selected"],
        select2: [(v) => v.length > 0 || "At least one item should be selected"],
        required: [
          (v) => !!v || "Field is required",
          (v) => (v && v.length >= 3) || "Min 3 characters",
        ],
      },
      emailRules: [
        (v) => !!v || "E-mail is required",
        (v) => /.+@.+\..+/.test(v) || 'E-mail must be valid',
        (v) => (v && v.length >= 3) || "Min 3 characters",
      ],
      numberRules: [
        (v) => !!v || "Field is required",
        (v) => (v && v.length >= 1) || "Min 1 characters",
      ],
    }
  },
  created() {
    this.index();
    this.getAllUsers();
    this.getAllItems();
  },
  computed: {
    ...GlobalComputed,
    formTitle() {
      return this.editedIndex === -1 ? "New Task" : "Edit Task";
    },
    computedDueTime() {
      return this.formatTime(this.editedItem.due_time);
    },
    computedDueDate() {
      return this.formatDate(this.editedItem.due_date);
    },
    computedStartTime() {
      return this.formatTime(this.editedItem.due_time);
    },
    computedStartDate() {
      return this.formatDate(this.editedItem.due_date);
    },
    computedEndTime() {
      return this.formatTime(this.editedItem.due_time);
    },
    computedEndDate() {
      return this.formatDate(this.editedItem.due_date);
    },
    likesAllUsers() {
      return (
        this.editedItem.selected_users.length ===
        this.users.length
      );
    },
    likesSomeUsers() {
      return (
        this.editedItem.selected_users.length > 0 &&
        !this.likesAllUsers
      );
    },
    iconUsers() {
      if (this.likesAllUsers) return "mdi-close-box";
      if (this.likesSomeUsers) return "mdi-minus-box";
      return "mdi-checkbox-blank-outline";
    },
  },
  watch: {
    dueTimeWatcher() {
      this.editedItem.due_time = this.formatTime(this.dueTimeWatcher);
    },
    dueDateWatcher() {
      this.editedItem.due_date = this.formatDate(this.dueDateWatcher);
    },
    startTimeWatcher() {
      this.editedItem.start_time = this.formatTime(this.startTimeWatcher);
    },
    startDateWatcher() {
      this.editedItem.start_date = this.formatDate(this.startDateWatcher);
    },
    endTimeWatcher() {
      this.editedItem.end_time = this.formatTime(this.endTimeWatcher);
    },
    endDateWatcher() {
      this.editedItem.end_date = this.formatDate(this.endDateWatcher);
    },
  },
  methods: {
    ...GlobalMethods,
    formatTableDate(date) {
      return moment(date).format("DD-MMM-YYYY");
    },
    formatTime(input) {
      return moment(input, "HH:mm:ss").format("h:mm:ss a");
    },
    reverseFormatTime(time) {
      if (!time) return null;
      return moment(time, "h:mm:ss a").format("HH:mm:ss");
    },
    formatTimeDefault(input) {
      return moment(input).format("h:mm:ss a");
    },
    formatDateDefault(input) {
      return moment(input, "YYYY-MM-DD h:mm:ss a").format("DD-MM-YYYY");
    },
    toggleUsers() {
      this.$nextTick(() => {
        if (this.likesAllUsers) {
          this.editedItem.selected_users = [];
        } else {
          this.editedItem.selected_users = this.users.map((user) => {
            return user.id;
          });
        }
      });
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
    getAllUsers() {
      return UsersService.index().then(
        (response) => {
          if (response.status == 200) {
            this.users = response.data.data;
          } else {
            this.users = [];
            console.log(response.data.error);
          }
        },
        (error) => {
          this.users = [];
          console.log(error);
        }
      );
    },
    newTask() {
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
      this.dialog = true;
    },
    editTask(item) {
      this.editedIndex = this.tasks.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dueDateWatcher = new Date(this.reverseFormatDate(this.editedItem.due_date)).toISOString().substr(0, 10);
      this.dueTimeWatcher = this.reverseFormatTime(this.editedItem.due_time);
      this.startDateWatcher = new Date(this.reverseFormatDate(this.editedItem.start_date)).toISOString().substr(0, 10);
      this.startTimeWatcher = this.reverseFormatTime(this.editedItem.start_time);
      this.endDateWatcher = new Date(this.reverseFormatDate(this.editedItem.end_date)).toISOString().substr(0, 10);
      this.endTimeWatcher = this.reverseFormatTime(this.editedItem.end_time);
      this.dialog = true;
    },
    deleteTask(item) {
      const index = this.items.indexOf(item);
      const deleteConfirmation = confirm('Are you sure you want to delete this task?');
      if (deleteConfirmation && this.items.splice(index, 1)) {
        TaskService.delete(item).then(
          (response) => {
            if (response.status == 200) {
              this.$store.dispatch("alert/SUCCESS", response.data.message);
              this.index();
            } else {
              this.$store.dispatch("alert/ERROR", "Invalid data, please check the form and try again!");
            }
          },
          (error) => {
            if (error.response.status == 422) {
              this.$store.dispatch("alert/ERROR", error.response.data.message);
            }

            this.$store.dispatch("alert/ERROR", error.response.data.message);
          }
        );
      }
    },
    setDBDateFormat() {
      this.editedItem.due_time = this.reverseFormatTime(this.editedItem.due_time);
      this.editedItem.due_date = this.reverseFormatDate(this.editedItem.due_date) + " " + this.editedItem.due_time;
      this.editedItem.start_time = this.reverseFormatTime(this.editedItem.start_time);
      this.editedItem.start_date = this.reverseFormatDate(this.editedItem.start_date) + " " + this.editedItem.start_time;
      this.editedItem.end_time = this.reverseFormatTime(this.editedItem.end_time);
      this.editedItem.end_date = this.reverseFormatDate(this.editedItem.end_date) + " " + this.editedItem.end_time;
    },
    revertDBDateFormat() {
      this.editedItem.due_time = this.formatTime(this.editedItem.due_time);
      this.editedItem.due_date = this.formatDateDefault(this.editedItem.due_date);
      this.editedItem.start_time = this.formatTime(this.editedItem.start_time);
      this.editedItem.start_date = this.formatDateDefault(this.editedItem.start_date);
      this.editedItem.end_time = this.formatTime(this.editedItem.end_time);
      this.editedItem.end_date = this.formatDateDefault(this.editedItem.end_date);
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    index() {
      return TaskService.index().then(
        (response) => {
          if (response.status == 200) {
            this.tasks = response.data.data;
            this.pageLoading = false;
          } else {
            this.tasks = [];
            this.pageLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.tasks = [];
          this.pageLoading = false;
          if (error.response.status == 401) {
            this.handleLogout();
          }

          this.$store.dispatch("alert/ERROR", error.response.data.message);
        }
      );
    },
    save() {
      this.valid = this.validate();
      if (this.valid) {
        this.submitLoading = true;
        this.setDBDateFormat();

        if (this.editedIndex > -1) {
          Object.assign(this.tasks[this.editedIndex], this.editedItem);

          if (typeof this.editedItem.status_id != "number") {
            this.editedItem.status_id = this.editedItem.status.id;
          }

          try {
            if (typeof this.editedItem.selected_users[0] !== "number") {
              this.editedItem.selected_users = this.editedItem.selected_users.map((user) => {
                return user.id;
              });
            } else {
              this.editedItem.selected_users = this.editedItem.selected_users.slice();
            }
          } catch (error) {
            console.log(error);
          }

          TaskService.update(this.editedItem).then(
            (response) => {
              if (response.status == 200) {
                this.$store.dispatch("alert/SUCCESS", response.data.message);
                this.submitLoading = false;
                this.revertDBDateFormat();
                this.close();
                this.index();
              } else {
                this.$store.dispatch("alert/ERROR", "Invalid data, please check the form and try again!");
                this.submitLoading = false;
                this.revertDBDateFormat();
              }
            },
            (error) => {
              this.submitLoading = false;
              this.revertDBDateFormat();
              if (error.response.status == 422) {
                this.$store.dispatch("alert/ERROR", error.response.data.message);
              }

              this.$store.dispatch("alert/ERROR", error.response.data.message);
            }
          );
        } else {
          if (typeof this.editedItem.status_id != "number") {
            this.editedItem.status_id = this.editedItem.status.id;
          }

          TaskService.store(this.editedItem).then(
            (response) => {
              if (response.status == 200) {
                this.$store.dispatch("alert/SUCCESS", "Task Submitted Successfully");
                this.submitLoading = false;
                this.revertDBDateFormat();
                this.close();
                this.index();
              } else {
                this.$store.dispatch("alert/ERROR",response.response.statusText || "Internal error!");
                this.submitLoading = false;
                this.revertDBDateFormat();
              }
            },
            (error) => {
              this.submitLoading = false;
              this.revertDBDateFormat();
              console.log(error);
              if (error.response.status == 422) {
                this.$store.dispatch("alert/ERROR", error);
              }
              this.$store.dispatch("alert/ERROR", error.response.data.message);
            }
          );
        }
      }
    },
  }
};
</script>