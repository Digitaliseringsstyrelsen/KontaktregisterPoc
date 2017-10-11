<template>
    <div style="margin: 10px 0px 40px;">
        <h2>Opret medie</h2>
        <div>
            <mdl-select label="Type" id="create-media-media-type-select" v-model="type" :options="mediaTypes"></mdl-select>
            <mdl-textfield floating-label="" v-model="data"></mdl-textfield>
        </div>
        <button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect" @click="create()">
            Gem
        </button>
    </div>
</template>

<script>
    import http from '../../http'

    export default {
        name: 'create-media',

        data () {
            return {
                mediaTypes: [
                    {
                        name: 'SMS',
                        value: 'sms',
                    },
                    {
                        name: 'E-mail',
                        value: 'email',
                    }
                ],
                type: '',
                data: ''
            }
        },

        computed: {
            identifier () {
                return this.$store.state.contact.identifier
            }
        },

        methods: {
            create () {
                if (this.type === '' || this.data === '') {
                    return;
                }
                http.post(`/api/contacts/${this.identifier}/medias`, {
                    type: this.type,
                    data: this.type === 'sms' ? { number: this.data } : { email: this.data }
                })
                    .then(() => this.$store.dispatch('loadContact', this.identifier))
                    .catch((error) => {
                        this.$toast.top(error.response.data.data.message);
                    })
            },
        }
    }
</script>
