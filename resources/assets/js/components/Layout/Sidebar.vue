<template>
    <div class="mdl-layout__drawer">
        <span class="mdl-layout-title">Kontaktregister</span>
        <nav class="mdl-navigation">
            <router-link class="mdl-navigation__link" :to="{ name: 'search' }">Søg</router-link>
            <router-link class="mdl-navigation__link" :to="{ name: 'register' }">Opret ny kontakt</router-link>
        </nav>
    </div>
</template>

<script>
    export default {
        name: 'sidebar',
    }
</script>
