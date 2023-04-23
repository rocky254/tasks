import AuthService from '../services/auth.service'

const user = JSON.parse(localStorage.getItem('user'))
const initialState = user ? { status: { loggedIn: true }, user, selected_user: null } : { status: { loggedIn: false }, user: null, selected_user: null };

const snackbar = {
    show: false,
    message: '',
    color: ''
}

export const auth = {
    namespaced: true,
    state: {...initialState, snackbar },
    actions: {
        LOGIN({ commit }, user) {
            return AuthService.login(user).then(
                response => {
                    if (response.status == 200) {
                        commit('LOGIN_SUCCESS', response.data)
                        return Promise.resolve(response.data)
                    } else {
                        commit('LOGIN_FAILURE', response)
                        return Promise.reject(response)
                    }

                },
                error => {
                    commit('LOGIN_FAILURE', error)
                    return Promise.reject(error)
                }
            )
        },
        LOGOUT({ commit }) {
            AuthService.logout()
            commit('LOGOUT')
        },
        REGISTER({ commit }, user) {
            return AuthService.register(user).then(
                response => {
                    if (response.status == 200) {
                        commit('REGISTER_SUCCESS', response)
                        return Promise.resolve(response.data)
                    } else {
                        if (response.errors) {
                            this.$store.dispatch("booking/SET_VALIDATION_ERRORS", response.errors);
                        }

                        commit('VALIDATION_ERROR', response.data.error)
                        return Promise.reject(response)
                    }
                },
                error => {
                    if (error.response && error.response.errors) {
                        this.$store.dispatch("booking/SET_VALIDATION_ERRORS", error.response.errors);
                    }

                    commit('REGISTER_FAILURE', error)
                    return Promise.reject(error)
                }
            )
        },
        UPDATE_PROFILE({ commit }, user) {
            return AuthService.update(user).then(
                response => {
                    commit('UPDATE_SUCCESS', response.data)
                    return Promise.resolve(response.data)
                },
                error => {
                    commit('UPDATE_FAILURE', error)
                    return Promise.reject(error)
                }
            )
        },
        CLEAR_SNACK({ commit }) {
            commit('CLEAR_SNACK');
        },
    },
    mutations: {
        LOGIN_SUCCESS(state, response) {
            state.status.loggedIn = true
            state.snackbar.show = true
            state.snackbar.color = 'success'
            state.snackbar.message = 'User Login Successful!'
            localStorage.setItem('user', JSON.stringify(response.data.user))
            localStorage.setItem('token', response.data.access_token)
            state.user = response.data.user
        },
        LOGIN_FAILURE(state, error) {
            console.log(error);
            state.status.loggedIn = false
            state.user = null
            state.snackbar.show = true
            state.snackbar.color = 'error'
            state.snackbar.message = error.response.data.message ||
                (error.response && error.response.data) ||
                error.mesage ||
                error.toString();
        },
        LOGOUT(state) {
            state.status.loggedIn = false
            state.user = null
            state.snackbar.show = false
            state.snackbar.color = ''
            state.snackbar.message = ''
        },
        REGISTER_SUCCESS(state, response) {
            state.status.loggedIn = true
            state.snackbar.show = true
            state.snackbar.color = 'success'
            state.snackbar.message = 'User Registration Successful!'
            state.user = response.data.user
        },
        REGISTER_FAILURE(state, error) {
            state.snackbar.show = true
            state.snackbar.color = 'error'
            state.snackbar.message = error.response.data.message || error.response.data.errors
        },
        VALIDATION_ERROR(state, errors) {
            let msg = ''
            let errorsArray = Object.values(errors);
            errorsArray.forEach(element => {
                console.log(element)
                msg = msg + element + '.'
            });
            state.snackbar.show = true
            state.snackbar.color = 'error'
            state.snackbar.message = msg
        },
        UPDATE_SUCCESS(state, response) {
            state.user = response.data.user
        },
        UPDATE_FAILURE(state, error) {
            console.log(error);
        },
        CLEAR_SNACK(state) {
            state.snackbar.show = false
            state.snackbar.color = ''
            state.snackbar.message = ''
        },
    },
    getters: {
        user: (state) => state.user
    }
}