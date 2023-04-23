<template>
  <div>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="items"
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
                <v-icon class="mr-2 mt-n1" color="primary">mdi-list-status</v-icon>Task Status
              </v-toolbar-title>
              <p class="grey--text ml-8 mt-n1">List of all Task Status</p>
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
                @click="newStatus"
              >
                <span>Add Status</span>
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
                    @click="editStatus(item)"
                  >
                    <v-icon small>mdi-pencil</v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Edit Status</span>
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
                    @click="deleteStatus(item)"
                  >
                    <v-icon small>mdi-delete-alert-outline</v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Delete Status</span>
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
                  <v-col cols="12" md="12" sm="12">
                    <v-text-field
                      outlined
                      label="Name"
                      placeholder="Name"
                      :rules="rules.required"
                      v-model="editedItem.name"
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
import StatusService from '../services/status.service';
import moment from "moment";
import GlobalMethods from '../helpers/global-methods';
import GlobalComputed from '../helpers/global-computed';

export default {
  data: () => {
    return {
      search: "",
      headers: [
        { text: 'Name', value: 'name' },
        { text: 'Date Created', value: 'created_at' },
        { text: 'Date Updated', value: 'updated_at' },
        { text: 'Actions', value: 'actions' },
      ],
      items: [],
      pageLoading: true,
      valid: false,
      submitLoading: false,
      dialog: false,
      editedItem: {
        name: '',
      },
      defaultItem: {
        name: '',
      },
      editedIndex: -1,
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
  },
  computed: {
    ...GlobalComputed,
    formTitle() {
      return this.editedIndex === -1 ? "New Status" : "Edit Status";
    },
  },
  methods: {
    ...GlobalMethods,
    filterText(value, search) {
      return (
        value != null && search != null && typeof value === "string" &&
        value.toString().toLocaleLowerCase().indexOf(search) !== -1
      );
    },
    formatTableDate(date) {
      return moment(date).format("DD-MMM-YYYY");
    },
    newStatus() {
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
      this.dialog = true;
    },
    editStatus(item) {
      this.editedIndex = this.items.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.booking_date = this.formatDate(this.editedItem.booking_date);
      this.dialog = true;
    },
    deleteStatus(item) {
      const index = this.items.indexOf(item);
      const deleteConfirmation = confirm('Are you sure you want to delete this status?');
      if (deleteConfirmation && this.items.splice(index, 1)) {
        StatusService.delete(item).then(
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
      this.editedItem.booking_date = this.reverseFormatDate(this.editedItem.booking_date);
    },
    revertDBDateFormat() {
      this.editedItem.booking_date = this.formatDate(this.editedItem.booking_date);
    },
    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },
    index() {
      return StatusService.index().then(
        (response) => {
          if (response.status == 200) {
            this.items = response.data.data;
            this.pageLoading = false;
          } else {
            this.items = [];
            this.pageLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.items = [];
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

        if (this.editedIndex > -1) {
          StatusService.update(this.editedItem).then(
            (response) => {
              if (response.status == 200) {
                this.$store.dispatch("alert/SUCCESS", response.data.message);
                this.submitLoading = false;
                this.close();
                this.index();
              } else {
                this.$store.dispatch("alert/ERROR", "Invalid data, please check the form and try again!");
                this.submitLoading = false;
              }
            },
            (error) => {
              this.submitLoading = false;
              if (error.response.status == 422) {
                this.$store.dispatch("alert/ERROR", error.response.data.message);
              }

              this.$store.dispatch("alert/ERROR", error.response.data.message);
            }
          );
        } else {
          StatusService.store(this.editedItem).then(
            (response) => {
              if (response.status == 200) {
                this.$store.dispatch("alert/SUCCESS", "Status Submitted Successfully");
                this.submitLoading = false;
                this.close();
                this.index();
              } else {
                this.$store.dispatch("alert/ERROR",response.response.statusText || "Internal error!");
                this.submitLoading = false;
              }
            },
            (error) => {
              this.submitLoading = false;
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