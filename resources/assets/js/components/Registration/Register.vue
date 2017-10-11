<template>
    <div class="mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--7-col">
            <mdl-select label="Type" id="contact-type-select" v-model="contact.type" :options="contactTypes"></mdl-select>
            <mdl-textfield floating-label="Indtast CPR/CVR-nummer ..." v-model="contact.identifier"></mdl-textfield>

            <div>
                <h2>Tilmelding</h2>
                <mdl-select label="Type" id="subscription-type-select" v-model="subscription.subscription_type_id" :options="subscriptionTypes"></mdl-select>
            </div>

            <div>
                <h2>Medie</h2>
                <mdl-select label="Type" id="media-type-select" v-model="media.type" :options="mediaTypes"></mdl-select>
                <mdl-textfield floating-label="Indtast telefonnummer/emailadresse ..." v-model="media.data"></mdl-textfield>
            </div>
            <!-- Colored raised button -->
            <button class="mdl-button mdl-js-button mdl-button--raised mdl-button--colored" @click="save">
                Opret
            </button>

        </div>
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--5-col col-cell--1-offset">
            <div></div>
        </div>
    </div>
</template>

<script>
    import http from '../../http'

    export default {
        name: 'register',

        data () {
            return {
                contactTypes: [
                    {
                        name: 'Person',
                        value: 'person',
                    },
                    {
                        name: 'Virksomhed',
                        value: 'company',
                    }
                ],
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
                contact: {
                    type: '',
                    identifier: ''
                },
                media: {
                    type: '',
                    data: ''
                },
                subscription: {
                    subscription_type_id: ''
                }
            }
        },

        computed: {
            subscriptionTypes () {
                let items = [];
                this.$store.getters.subscriptionTypes.forEach(item => items.push({name: item.name, value: item.id}));
                return items
            }
        },

        mounted () {
            this.$store.dispatch('loadSubscriptionTypes')
        },

        methods: {
            save () {
                if (! this.validate()) {
                    return
                }

                let postData = {
                    contact: this.contact,
                    media: {
                        type: this.media.type,
                        data: this.media.type === 'sms' ? {number: this.media.data} : {email: this.media.data}
                    },
                    subscription: this.subscription
                };

                http.post('api/register', postData).then(({ data }) => {
                    this.$router.push({ name: 'contact', params: { identifier: data.identifier }})
                })
            },
            validate () {
                // TODO: Add proper validation
                return true
            }
        }
    }
</script>
