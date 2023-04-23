import Vue from "vue";
import Vuex from "vuex";
import { alert } from './alert.module';
import { auth } from './auth.module';
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);

export default new Vuex.Store({
    plugins: [createPersistedState()],
    state: {
        loading: true,
        lightNavbar: false,
        activeSection: 'home',
    },
    actions: {
        SET_LOADING({ commit }, val) {
            commit('SET_LOADING', val)
        },
        SET_LIGHT_NAVBAR({ commit }, light) {
            commit('SET_LIGHT_NAVBAR', light)
        },
        SET_ACTIVE_SECTION({ commit }, section) {
            commit('SET_ACTIVE_SECTION', section)
        },
    },
    mutations: {
        SET_LOADING(state, val) {
            state.loading = val;
        },
        SET_LIGHT_NAVBAR(state, light) {
            state.lightNavbar = light;
        },
        SET_ACTIVE_SECTION(state, section) {
            state.activeSection = section;
        },
    },
    modules: {
        alert,
        auth,
    }
});
