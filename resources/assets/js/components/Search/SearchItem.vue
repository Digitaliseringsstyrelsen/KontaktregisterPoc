<template>
    <div class="mdl-list__item" @click="goToContact">
        <span class="mdl-list__item-primary-content">
          <i class="material-icons mdl-list__item-avatar" v-text="type === 'person' ? 'person' : 'business'">person</i>
          <span v-text="identifier.asFormattedIdentifier(type)"></span>
        </span>
    </div>
</template>

<script>
    export default {
        name: 'search-item',

        props: ['type', 'identifier'],

        methods: {
            goToContact () {
                this.$router.push({ name: 'contact', params: { identifier: this.identifier }})
            }
        }
    }
</script>
