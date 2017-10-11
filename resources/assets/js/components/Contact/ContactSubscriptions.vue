<template>
    <div class="mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--7-col">
            <create-subscription></create-subscription>

            <div v-for="subscription in contact.subscriptions">
                <subscription-item :subscription="subscription"></subscription-item>
            </div>
        </div>
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--5-col col-cell--1-offset">
            <h2>Resultat</h2>
            <tree-view v-if="contact.subscriptions.length" :data=contact.subscriptions :options="{maxDepth: 6}"></tree-view>
        </div>
    </div>
</template>

<script>
    import SubscriptionItem from "./SubscriptionItem"
    import CreateSubscription from "./CreateSubscription"

    export default {
        name: 'contact-subscriptions',

        components: {
            CreateSubscription,
            SubscriptionItem
        },

        computed: {
            contact () {
                return this.$store.state.contact
            }
        }
    }
</script>
