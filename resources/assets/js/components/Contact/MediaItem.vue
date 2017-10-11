<template>
    <tr>
        <td class="mdl-data-table__cell--non-numeric" v-text="media.type"></td>
        <td class="mdl-data-table__cell--non-numeric" v-text="Object.values(media.data)[0]"></td>

        <td>
            <mdl-button primary @click.native="$refs.updateMedia.open">Edit</mdl-button>
            <mdl-dialog ref="updateMedia" title="Bekræft sletning">
                <input v-model="mediaData" placeholder="edit">
                <template slot="actions">
                    <mdl-button @click.native="$refs.updateMedia.close">Cancel</mdl-button>
                    <mdl-button primary @click.native="updateMedia">Update</mdl-button>
                </template>
            </mdl-dialog>
        </td>

        <td>
            <mdl-button primary @click.native="$refs.destroyMedia.open">Slet</mdl-button>
            <mdl-dialog ref="destroyMedia" title="Bekræft sletning">
                <p>Er du sikker på at du vil slette det valgte medie?</p>
                <template slot="actions">
                    <mdl-button @click.native="$refs.destroyMedia.close">Nej</mdl-button>
                    <mdl-button primary @click.native="destroyMedia">Ja</mdl-button>
                </template>
            </mdl-dialog>
        </td>
    </tr>
</template>

<script>
    import http from '../../http'

    export default {
        name: 'media-item',

        props: ['media'],

        computed: {
            identifier () {
                return this.$store.state.contact.identifier
            }
        },

        data () {
            return {
                mediaData: Object.values(this.media.data)[0]
            }
        },

        methods: {
            destroyMedia () {
                http.delete(`/api/contacts/${this.identifier}/medias/${this.media.id}`)
                    .then(() => {
                        this.$store.dispatch('loadContact', this.identifier);
                        this.$refs.destroyMedia.close()
                    })
                    .catch((error) => {
                        this.$refs.destroyMedia.close()
                        this.$toast.top(error.response.data.data.message);
                    })
            },

            updateMedia() {
                http.put(`/api/contacts/${this.identifier}/medias/${this.media.id}`, {
                    type: this.media.type,
                    data: this.media.type === 'sms' ? { number: this.mediaData } : { email: this.mediaData }
                })
                    .then(() => {
                        this.$store.dispatch('loadContact', this.identifier);
                        this.$refs.updateMedia.close()
                    })
                    .catch((error) => {
                        this.$refs.updateMedia.close()
                        this.$toast.top(error.response.data.data.message);
                    })
            }
        }
    }
</script>
