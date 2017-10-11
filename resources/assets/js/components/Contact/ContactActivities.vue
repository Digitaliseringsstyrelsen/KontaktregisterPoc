<template>
    <div class="mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--12-col">
            <div v-for="element in elements" :entry="element" :key="element">
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp" style="width: 100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Type</th>
                        <th class="mdl-data-table__cell--non-numeric">Gammel værdi</th>
                        <th class="mdl-data-table__cell--non-numeric">Ny værdi</th>
                        <th>Tidspunkt</th>
                    </tr>
                    </thead>
                    <tbody>
                        <activity-item v-if="element.length" v-for="entry in element" :entry="entry" :key="entry"></activity-item>
                    </tbody>
                </table>
                <br/>
                <br/>
            </div>
        </div>
        <div class="mdl-layout-spacer"></div>
    </div>
</template>

<script>
    import ActivityItem from './ActivityItem.vue'
    import {groupBy} from 'lodash'
    import TreeView from 'vue-json-tree-view'

    export default {
        name: 'contact-activities',

        components: {ActivityItem},

        mounted () {
            this.$store.dispatch('loadContactLog', this.identifier)
        },

        computed: {
            identifier () {
                return this.$store.state.contact.identifier
            },
            elements () {
                return groupBy(this.$store.state.contactLog.entries, 'type')
            }
        }
    }
</script>
