<template>
  <div>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="bookings"
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
                <v-icon class="mr-2 mt-n1" color="primary">mdi-calendar</v-icon>Bookings
              </v-toolbar-title>
              <p class="grey--text ml-8 mt-n1">List of all Bookings</p>
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
            v-if="pageLoading"
          ></v-progress-linear>
        </template>
        <!-- Customer Name -->
        <template #[`item.customer_name`]="{ item }">
          <div class="d-flex flex-column">
            <span class="d-block font-weight-semibold text--primary text-truncate">
              {{ item.customer_name }}
            </span>
          </div>
        </template>
        <!-- Unit Cost -->
        <template #[`item.unit_cost`]="{ item }">
          {{ toMoneyFormat(item.unit_cost) }}
        </template>
        <!-- Booking Date -->
        <template #[`item.booking_date`]="{ item }">
          {{ formatTableDate(item.booking_date) }}
        </template>
        <!-- Status -->
        <template #[`item.status`]="{ item }">
          <v-chip
            small
            :color="statusColor[status[item.status]]"
            class="font-weight-medium"
          >
            {{ status[item.status] }}
          </v-chip>
        </template>
        <!-- Actions -->
        <template v-slot:[`item.actions`]="{ item }">
          <template>
            <v-tooltip top>
              <template v-slot:activator="{ on, attrs }">
                <span class="d-inline-block mb-1" v-bind="attrs" v-on="on">
                  <v-btn
                    color="grey darken-1"
                    class="text-capitalize mx-1"
                    depressed
                    fab
                    x-small
                    dark
                    @click="editBooking(item)"
                  >
                    <v-icon small>mdi-eye</v-icon>
                  </v-btn>
                </span>
              </template>
              <span>Preview Booking</span>
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
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      outlined
                      label="Name"
                      placeholder="Name"
                      :rules="rules.required"
                      v-model="editedItem.customer_name"
                      readonly
                    />
                  </v-col>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      outlined
                      label="Email"
                      placeholder="Email"
                      :rules="emailRules"
                      v-model="editedItem.customer_email"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-text-field
                      outlined
                      label="School Name"
                      placeholder="School Name"
                      :rules="rules.required"
                      v-model="editedItem.school_name"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      outlined
                      label="No. of Students"
                      placeholder="No. of Students"
                      type="number"
                      v-model="editedItem.no_of_students"
                      readonly
                    />
                  </v-col>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      outlined
                      label="No. of Teachers"
                      placeholder="No. of Techers"
                      type="number"
                      v-model="editedItem.no_of_teachers"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      outlined
                      label="Grade"
                      placeholder="Grade"
                      type="number"
                      v-model="editedItem.grade"
                      readonly
                    />
                  </v-col>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-select
                      outlined
                      :items="meals"
                      label="Will Meals Be Provided?"
                      item-value="id"
                      item-text="title"
                      :rules="rules.select"
                      v-model="editedItem.meals"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      outlined
                      label="Unit Cost"
                      placeholder="Unit Cost"
                      type="number"
                      readonly
                      v-model="editedItem.unit_cost"
                    />
                  </v-col>
                  <v-col :cols="pcOnly ? 6 : 12">
                    <v-text-field
                      v-model="editedItem.booking_date"
                      label="Booking Date"
                      persistent-hint
                      append-icon="mdi-calendar"
                      outlined
                      color="grey"
                      readonly
                    ></v-text-field>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-select
                      v-model="editedItem.meals_available"
                      :value="editedItem.meals_available"
                      :items="meals_available"
                      label="Select Required Foods"
                      item-text="title"
                      item-value="id"
                      v-if="editedItem.meals == 2"
                      multiple
                      outlined
                      chips
                      color="grey"
                      item-color="grey"
                      readonly
                    >
                      <template v-slot:label>
                        <span class="input__label"
                          >Select Required Foods</span
                        >
                      </template>
                      <template v-slot:prepend-item>
                        <v-list-item ripple @click="toggleMeals">
                          <v-list-item-action>
                            <v-icon
                              :color="editedItem.meals_available.length > 0 ? 'error' : ''"
                            >
                              {{ iconMeals }}
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
                            v-if="likesAllMeals"
                          >
                            <v-list-item-title>
                              All Meals Selected for This Booking!
                            </v-list-item-title>
                          </v-list-item-content>

                          <v-list-item-content
                            v-else-if="likesSomeMeals"
                          >
                            <v-list-item-title>
                              Meals Count
                            </v-list-item-title>
                            <v-list-item-subtitle>
                              {{ editedItem.meals_available.length }}
                            </v-list-item-subtitle>
                          </v-list-item-content>

                          <v-list-item-content v-else>
                            <v-list-item-title>
                              Which Meals are Required for this
                              Booking?
                            </v-list-item-title>
                            <v-list-item-subtitle>
                              Go ahead, make a selection above!
                            </v-list-item-subtitle>
                          </v-list-item-content>
                        </v-list-item>
                      </template>
                    </v-select>
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-select
                      outlined
                      :items="paymentOptions"
                      label="Complementary Confimed Booking (Payment Options)"
                      item-value="id"
                      item-text="title"
                      :rules="rules.select"
                      v-model="editedItem.confirmed"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-select
                      outlined
                      :items="packagesCols"
                      label="Booking Package"
                      item-value="id"
                      item-text="headline"
                      :rules="rules.select"
                      v-model="editedItem.package"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-textarea
                      rows="4"
                      no-resize
                      auto-grow
                      outlined
                      label="Will Computers Be Required?"
                      placeholder="Please Enter The Number of Computers Required (Optional)"
                      v-model="editedItem.computers_required"
                      readonly
                    />
                  </v-col>
                </v-row>
                <v-row>
                  <v-col cols="12">
                    <v-select
                      outlined
                      :items="booking_status"
                      label="Booking Status"
                      item-value="id"
                      item-text="title"
                      :rules="rules.select"
                      v-model="editedItem.status"
                      color="success"
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
import { mdiSquareEditOutline, mdiDotsVertical } from "@mdi/js";
import data from "../components/dashboard/datatable-data";
import BookingService from '../services/booking.service';
import moment from "moment";
import GlobalMethods from '../helpers/global-methods';
import GlobalComputed from '../helpers/global-computed';

export default {
  data: (vm) => {
    return {
      search: "",
      statusColor: {
        Current: "primary",
        Paid: "success",
        Rejected: "error",
      },
      headers: [
        { text: 'Name', value: 'customer_name' },
        { text: 'Email', value: 'customer_email' },
        { text: 'Date', value: 'booking_date' },
        { text: 'Status', value: 'status' },
        { text: 'Actions', value: 'actions' }
      ],
      bookings: [],
      pageLoading: true,
      valid: false,
      submitLoading: false,
      dialog: false,
      packagesCols: [
        {
          id: 1,
          color: 'blue', 
          icon: 'mdi-run', 
          headline: vm.$t('sections.packages.cols.0.headline'), 
          description: vm.$t('sections.packages.cols.0.description')
        },
        {
          id: 2,
          color: 'deep-orange', 
          icon: 'mdi-book-education-outline', 
          headline: vm.$t('sections.packages.cols.1.headline'), 
          description: vm.$t('sections.packages.cols.1.description')
        },
        {
          id: 3,
          color: 'teal', 
          icon: 'mdi-all-inclusive', 
          headline: vm.$t('sections.packages.cols.2.headline'), 
          description: vm.$t('sections.packages.cols.2.description')
        },
      ],
      meals: [
        {
          id: 1,
          title: 'No'
        },
        {
          id: 2,
          title: 'Yes'
        },
      ],
      meals_available: [
        {
          id: 1,
          title: 'Chips - Kshs. 180 Per Plate',
          price: 180
        },
        {
          id: 2,
          title: 'Sausage - Kshs. 60 Per Plate',
          price: 60
        },
        {
          id: 3,
          title: 'Soda - Kshs. 60 Per Plate',
          price: 60
        },
        {
          id: 4,
          title: 'Milk - Kshs. 60 Per Plate',
          price: 60
        },
        {
          id: 5,
          title: 'Pilau - Kshs. 180 Per Plate',
          price: 180
        },
        {
          id: 6,
          title: 'Chicken - Kshs. 200 Per Plate',
          price: 200
        },
      ],
      paymentOptions: [
        {
          id: 1,
          title: 'Payment will be done on Arrival at Destination'
        },
        {
          id: 2,
          title: 'Payment will be done on Checkout'
        },
      ],
      editedItem: {
        customer_name: '',
        customer_email: '',
        school_name: '',
        no_of_students: '',
        grade: '',
        no_of_teachers: '',
        no_of_people: 0,
        confirmed: '',
        meals: '',
        meals_available: [],
        unit_cost: '',
        computers_required: '',
        status: '',
        package: '',
      },
      defaultItem: {
        customer_name: '',
        customer_email: '',
        school_name: '',
        no_of_students: '',
        no_of_people: 0,
        grade: '',
        no_of_teachers: '',
        confirmed: '',
        meals: '',
        meals_available: [],
        unit_cost: '',
        computers_required: '',
        status: '',
        package: '',
      },
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
      usreList: data,
      status: {
        1: "Pending",
        2: "Paid",
        3: "Rejected",
      },
      booking_status: [
        {
          id: 1,
          title: "Pending",
        },
        {
          id: 2,
          title: "Paid",
        },
        {
          id: 3,
          title: "Rejected",
        }
      ],
      icons: {
        mdiSquareEditOutline,
        mdiDotsVertical,
      },
    }
  },
  created() {
    this.index();
  },
  computed: {
    ...GlobalComputed,
    formTitle() {
      return this.editedIndex === -1 ? "New Booking" : "Edit Booking";
    },
    // required meal types
    likesAllMeals() {
      return (
        this.editedItem.meals_available.length ===
        this.meals_available.length
      );
    },
    likesSomeMeals() {
      return (
        this.editedItem.meals_available.length > 0 &&
        !this.likesAllMeals
      );
    },
    iconMeals() {
      if (this.likesAllMeals) return "mdi-close-box";
      if (this.likesSomeMeals) return "mdi-minus-box";
      return "mdi-checkbox-blank-outline";
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
    toMoneyFormat(number) {
      return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "KES",
      }).format(number);
    },
    formatTableDate(date) {
      return moment(date).format("DD-MMM-YYYY");
    },
    editBooking(item) {
      this.editedIndex = this.bookings.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editedItem.booking_date = this.formatDate(this.editedItem.booking_date);
      this.dialog = true;
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
    // required foods
    toggleMeals() {
      this.$nextTick(() => {
        if (this.likesAllMeals) {
          this.editedItem.meals_available = [];
        } else {
          this.editedItem.meals_available = this.meals_available.map((meal) => {
              return meal.id;
          });
        }
      });
    },
    index() {
      return BookingService.index().then(
        (response) => {
          if (response.status == 200) {
            this.bookings = response.data.data;
            this.pageLoading = false;
          } else {
            this.bookings = [];
            this.pageLoading = false;
            console.log(response.data.error);
          }
        },
        (error) => {
          this.bookings = [];
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
        var userConfirmation = confirm("Are you sure you want to update this booking?");
        if (userConfirmation) {
          this.submitLoading = true;
          this.setDBDateFormat();

          if (this.editedIndex > -1) {
            if (typeof this.editedItem.confirmed != "number") {
              this.editedItem.confirmed = this.editedItem.confirmed.id;
            }

            if (typeof this.editedItem.meals != "number") {
              this.editedItem.meals = this.editedItem.meals.id;
            }

            BookingService.update(this.editedItem).then(
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
            BookingService.store(this.editedItem).then(
              (response) => {
                if (response.status == 200) {
                  this.$store.dispatch("alert/SUCCESS", "Booking Submitted Successfully");
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
      }
    },
  }
};
</script>