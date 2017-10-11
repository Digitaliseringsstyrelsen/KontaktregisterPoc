import http from '../../http'

export default {
    state: {
        items: []
    },

    getters: {
        subscriptionTypes: state => {
            return state.items
        }
    },

    mutations: {
        setSubscriptionTypes (state, payload) {
            state.items = payload
        }
    },

    actions: {
        loadSubscriptionTypes ({ commit }) {
            http.get('/api/subscription-types').then(({ data }) => commit('setSubscriptionTypes', data.data))
        }
    }
}
