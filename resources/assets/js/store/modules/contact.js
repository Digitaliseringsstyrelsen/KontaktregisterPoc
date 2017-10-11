import { forEach } from 'lodash'
import http from '../../http'

export default {
    state: {
        loaded: false,
        type: null,
        identifier: null,
        subscriptions: [],
        medias: []
    },

    mutations: {
        setContact (state, payload) {
            forEach(payload, (val, key) => state[key] = val)
            state['loaded'] = true
        }
    },

    actions: {
        loadContact ({ commit }, identifier) {
            http.get(`/api/contacts/${identifier}`).then(({ data }) => commit('setContact', data))
        }
    }
}
