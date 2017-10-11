import Vue from 'vue'
import Vuex from 'vuex'
import contact from './modules/contact'
import contactLog from './modules/contactLog'
import subscriptionTypes from './modules/subscriptionTypes'

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        page_title: 'Kontaktregister',
        nem_id_payload: localStorage.getItem('axios_nemid_auth_payload'),
        nem_id_payload_options: [
            {
                name: 'Borger - Ingen rettigheder',
                value: 'Borger_uden rettigheder.xml'
            },
            {
                name: 'Myndighed - Admin',
                value: 'Myndighedsbruger_med admin rettighed.xml'
            },
            {
                name: 'Myndighed - Ikke admin',
                value: 'Myndighedsbruger_uden admin rettighed.xml'
            },
            {
                name: 'Virksomhedsbruger - Kontaktregisterrettighed',
                value: 'Virksomhedsbruger_med kontaktregister rettighed.xml'
            },
            {
                name: 'Virksomhedsbruger - Ekstern kontaktregisterrettighed',
                value: 'Virksomhedsbruger_med ekstern kontaktregister rettighed.xml'
            },
            {
                name: 'Virksomhedsbruger - Tegningsret & Kontaktregisterrettighed',
                value: 'Virksomhedsbruger_med tegningsret og kontaktregister rettighed.xml'
            },
            {
                name: 'Virksomhedsbruger - Ingen kontaktregisterrettighed',
                value: 'Virksomhedsbruger_uden kontaktregister rettighed.xml'
            },
        ]
    },

    modules: {
        contact,
        contactLog,
        subscriptionTypes
    },

    mutations: {
        setPageTitle (state, title) {
            state.page_title = title
        },
        setNemIdPayload (state, data) {
            localStorage.setItem('axios_nemid_auth_payload', data)
            state.nem_id_payload = data
        },
        setNemIdName(state, data) {
            localStorage.setItem('current_nem_id', data);
            state.nem_id = data
        }
    },

    actions: {
        setPageTitle ({ commit }, title) {
            commit('setPageTitle', title)
        },
        setNemIdPayload ({ commit }, file) {
            let rawFile = new XMLHttpRequest()
            rawFile.open("GET", '/nemid_payloads/' + file, true)

            rawFile.onreadystatechange = () => {
                if (rawFile.readyState === 4 && (rawFile.status === 200 || rawFile.status === 0)) {
                    commit('setNemIdPayload', rawFile.responseText);
                    commit('setNemIdName', file);
                }
            }
            rawFile.send(null)
        }
    }
})
