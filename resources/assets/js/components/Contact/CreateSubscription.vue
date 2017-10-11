<template>
    <div style="margin: 10px 0px 40px;">
        <h2>Opret tilmelding</h2>
        <div>
            <mdl-select label="Type" id="create-subscription-subscription-type-select" v-model="subscription_type_id" :options="subscriptionTypes"></mdl-select>
            <mdl-select label="Medie" id="create-subscription-media-type-select" v-model="media_id" :options="medias"></mdl-select>
        </div>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" @click="subscribe()">
            Gem
        </button>
    </div>
</template>

<script>
    import http from '../../http'

    export default {
        name: 'create-subscription',

        data () {
            return {
                subscription_type_id: '',
                media_id: ''
            }
        },

        computed: {
            identifier () {
                return this.$store.state.contact.identifier
            },
            subscriptionTypes () {
                let items = [];
                this.$store.getters.subscriptionTypes.forEach(item => items.push({ name: item.name, value: item.id }));
                return items;
            },
            medias () {
                let items = [];
                this.$store.state.contact.medias.forEach(item => items.push({ name: `${item.type} - ${Object.values(item.data)[0]}`, value: item.id }));
                return items;
            }
        },

        methods: {
            subscribe () {
                if (this.subscription_type_id === '' || this.media_id === '') {
                    return;
                }
                http.post(`/api/contacts/${this.identifier}/subscriptions`, {
                    subscription_type_id: this.subscription_type_id,
                }).then(({ data }) => http.post(`/api/contacts/${this.identifier}/media-subscriptions`, {
                    subscription_id: data.id,
                    media_id: this.media_id
                }).then(() => this.$store.dispatch('loadContact', this.identifier)))
            },
        }
    }
</script>
