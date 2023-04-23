const initialState = {
    color: '',
    message: '',
    show: false
}

export const alert = {
    namespaced: true,
    state: initialState,
    actions: {
        SUCCESS({ commit }, message) {
            commit('SUCCESS', message)
        },
        ERROR({ commit }, message) {
            commit('ERROR', message)
        },
        INFO({ commit }, message) {
            commit('INFO', message)
        },
        CLEAR({ commit }) {
            commit('CLEAR')
        }
    },
    mutations: {
        SUCCESS(state, message) {
            state.color = 'success';
            state.message = message;
            state.show = true;
        },
        ERROR(state, message) {
            state.color = 'error';
            state.message = message;
            state.show = true;
        },
        INFO(state, message) {
            state.color = 'info';
            state.message = message;
            state.show = true;
        },
        CLEAR(state) {
            state.color = '';
            state.message = '';
            state.show = false;
        }
    },

}