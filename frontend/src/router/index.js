import Vue from "vue";
import VueRouter from "vue-router";
import Register from "../views/Register.vue";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import Status from "../views/Status.vue";
import Tasks from "../views/Tasks.vue";
import AccountSettings from "../views/account-settings/AccountSettings.vue";

Vue.use(VueRouter);

const routes = [
  {
    path: "/register",
    name: "register",
    component: Register,
    meta: {
      layout: 'blank',
    },
  },
  {
    path: "/login",
    name: "login",
    component: Login,
    meta: {
      layout: 'blank',
    },
  },
  {
    path: "/",
    name: "dashboard",
    component: Dashboard
  },
  {
    path: "/tasks",
    name: "tasks",
    component: Tasks
  },
  {
    path: "/status",
    name: "status",
    component: Status
  },
  {
    path: "/account-settings",
    name: "account-settings",
    component: AccountSettings
  },
];

const router = new VueRouter({
  // mode: "history",
  base: process.env.BASE_URL,
  routes
});

// control auth in all routes
router.beforeEach((to, from, next) => {
  const publicPages = ['/login', '/register', '/', '/booking', '/confirm', '/register', '/our-founder'];
  const authRequired = !publicPages.includes(to.path);
  const loggedIn = localStorage.getItem('token');

  // trying to access a restricted page + not logged in
  // redirect to login page
  if (authRequired && !loggedIn) {
    next({ path: '/login' });
  } else {
    next();
  }
});

export default router;