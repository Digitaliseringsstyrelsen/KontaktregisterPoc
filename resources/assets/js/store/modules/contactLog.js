import http from '../../http'

export default {
    state: {
        entries: []
    },

    mutations: {
        setEntries (state, payload) {
            state.entries = payload
        }
    },

    actions: {
        loadContactLog ({ commit }, identifier) {
            http.get(`/api/contacts/${identifier}/log`).then(({ data }) => commit('setEntries', data.data))
        }
    }
}
