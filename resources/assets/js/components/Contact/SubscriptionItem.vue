<template>
        <div class="mdl-card mdl-shadow--2dp mdl-typography--text-left" style="width: 100%; margin-top: 16px;">
            <div class="mdl-card__title">
                <h2 class="mdl-card__title-text" v-text="subscription.type"></h2>
            </div>
            <div class="mdl-card__supporting-text">
                <p>
                    <strong>Accepterede betingelser:</strong> {{ subscription.accepted_terms.version }}<br />
                    <button v-if="!subscription.accepted_terms.latest" @click="acceptLatestTerms" class="mdl-button mdl-button-small mdl-js-button mdl-button--raised mdl-js-ripple-effect">
                        Accepter nyeste ({{ latestTerms.version }})
                    </button>
                </p>
                <p>
                    <strong>Startdato:</strong> {{ subscription.started_at }}
                </p>
                <p v-if="subscription.ended_at !== null">
                    <strong>Slutdato:</strong> {{ subscription.ended_at }}
                </p>

                <h4>Tilknyttede medier</h4>
                <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp subscriptions-table" style="margin-top: 10px; width: 100%">
                    <thead>
                    <tr>
                        <th class="mdl-data-table__cell--non-numeric">Type</th>
                        <th class="mdl-data-table__cell--non-numeric">Data</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="media in subscription.medias">
                        <td class="mdl-data-table__cell--non-numeric" v-text="media.type"></td>
                        <td class="mdl-data-table__cell--non-numeric" v-text="Object.values(media.data)[0]"></td>
                        <td>
                            <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" @click="detachMedia(media)">
                                Slet
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="mdl-card__actions mdl-card--border" v-if="subscription.ended_at === null">
                <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" @click="toggleUnsubscribeModal">
                    Afmeld
                </a>
            </div>
            <mdl-dialog ref="unsubscribe" title="Bekræft afmelding">
                <p>Vælg dato for afmeldingens ikrafttrædelse:</p>
                <input type="date" v-model="end_at" v-bind:value="end_at.toString()" />
                <template slot="actions">
                    <mdl-button @click.native="toggleUnsubscribeModal">Nej</mdl-button>
                    <mdl-button primary @click.native="unsubscribe(end_at)">Ja</mdl-button>
                </template>
            </mdl-dialog>
        </div>
</template>

<script>
    import http from '../../http'

    export default {
        name: 'subscription-item',

        props: ['subscription'],

        data () {
            return {
                end_at: new Date()
            }
        },

        computed: {
            identifier () {
                return this.$store.state.contact.identifier
            },
            subscriptionTypes () {
                return this.$store.getters.subscriptionTypes
            },
            latestTerms () {
                for (let i = 0; i < this.subscriptionTypes.length; i++) {
                    if (this.subscriptionTypes[i].name === this.subscription.type) {
                        return this.subscriptionTypes[i].terms[0]
                    }
                }

                return {
                    id: false,
                    version: '--'
                }
            }
        },

        methods: {
            unsubscribe (end_at) {
                http.put(`/api/contacts/${this.identifier}/subscriptions/${this.subscription.id}`, {
                    end_at: end_at
                }).then(() => {
                    this.$store.dispatch('loadContact', this.identifier)
                    this.toggleUnsubscribeModal()
                })
            },
            acceptLatestTerms () {
                if (! this.latestTerms.id) {
                    return
                }
                http.post(`/api/contacts/${this.identifier}/subscriptions/${this.subscription.id}/accept-terms`, {
                    term_id: this.latestTerms.id
                }).then(() => this.$store.dispatch('loadContact', this.identifier))
            },
            detachMedia (media) {
                http.delete(`/api/contacts/${this.identifier}/media-subscriptions?subscription_id=${this.subscription.id}&media_id=${media.id}`)
                    .then(() => this.$store.dispatch('loadContact', this.identifier))
            },
            toggleUnsubscribeModal () {
                this.$refs.unsubscribe.show ? this.$refs.unsubscribe.close() : this.$refs.unsubscribe.open()
            }
        }
    }
</script>
