<template>
    <div class="mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--6-col">
            <form @submit.prevent="search">
                <mdl-textfield floating-label="Indtast CPR/CVR-numre ..." v-model="input"></mdl-textfield>
            </form>

            <div class="mdl-list" v-if="contacts.length">
                <search-item v-for="contact in contacts" :key="contact.identifier" :type="contact.type" :identifier="contact.identifier"></search-item>
            </div>
        </div>
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--6-col">
            <h3>Resultat</h3>
            <div v-if="contacts.length">
                <tree-view :data=contacts :options="{maxDepth: 6}"></tree-view>
            </div>
            <div v-else>
                <span>Ingen resultater</span>
            </div>
        </div>
    </div>
</template>

<script>
    import http from '../../http'
    import SearchItem from "./SearchItem"

    export default {
        name: 'search',

        components: {
            SearchItem
        },

        data () {
            return {
                input: '',
                contacts: []
            }
        },

        mounted () {
            this.$store.dispatch('setPageTitle', 'Søg')
        },

        methods: {
            search () {
                http.get(`/api/search?query=${this.input}`)
                    .then(({ data }) => {
                        this.contacts = data.data
                    })
            }
        }
    }
</script>
