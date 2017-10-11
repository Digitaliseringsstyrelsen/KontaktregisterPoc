<template>
    <header class="mdl-layout__header">
        <div class="mdl-layout__header-row">
            <div class="mdl-layout-spacer"></div>

            <button id="demo-menu-lower-right"
                    class="mdl-button mdl-js-button mdl-button--icon">
                <i class="material-icons">more_vert</i>
            </button>
            <span v-text="nemID" class="text-muted"></span>
            <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="demo-menu-lower-right">
                <li class="mdl-menu__item" v-for="option in options" v-text="option.name" @click="pick(option.value)"></li>
            </ul>
        </div>
    </header>
</template>

<script>
    export default {
        name: 'header-row',

        computed: {
            options () {
                return this.$store.state.nem_id_payload_options
            }
        },

        data() {
            return {
                nemID: localStorage.getItem('current_nem_id')
            }
        },

        methods: {
            pick (item) {
                this.$store.dispatch('setNemIdPayload', item);
            }
        }
    }
</script>
