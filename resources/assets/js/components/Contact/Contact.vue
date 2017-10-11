<template>
    <div v-if="isLoading" class="mdl-typography--text-center">
        <div class="mdl-spinner mdl-js-spinner is-active"></div>
    </div>
    <div v-else class="mdl-typography--text-center">
        <h1 v-text="contact.identifier.asFormattedIdentifier(contact.type)"></h1>
        <p>Type: {{ contact.type }}</p>

        <mdl-tabs v-model="selectedTab">
            <mdl-tab tab="Kontaktoplysninger">
                <contact-medias></contact-medias>
            </mdl-tab>
            <mdl-tab tab="Tilmeldinger">
                <contact-subscriptions></contact-subscriptions>
            </mdl-tab>
            <mdl-tab tab="Aktivitet">
                <contact-activities></contact-activities>
            </mdl-tab>
        </mdl-tabs>
    </div>
</template>

<script>
    import ContactMedias from './ContactMedias.vue'
    import ContactSubscriptions from './ContactSubscriptions.vue'
    import ContactActivities from "./ContactActivities"

    export default {
        name: 'contact',

        components: {
            ContactActivities,
            ContactMedias,
            ContactSubscriptions
        },

        data () {
            return {
                identifier: location.pathname.substr(1).split('/')[1],
                selectedTab: 'Kontaktoplysninger'
            }
        },

        mounted () {
            this.$store.dispatch('setPageTitle', `Vis kontakt - ${this.identifier}`)
            this.$store.dispatch('loadContact', this.identifier)
            this.$store.dispatch('loadSubscriptionTypes')
        },

        computed: {
            isLoading () {
                return !this.contact.loaded
            },
            contact () {
                return this.$store.state.contact
            },
            subscriptionTypes () {
                return this.$store.getters.subscriptionTypes
            }
        }
    }
</script>
