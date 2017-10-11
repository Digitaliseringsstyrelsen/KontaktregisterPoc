<template>
    <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header mdl-layout--no-drawer-button">
        <header-row></header-row>
        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
            <sidebar></sidebar>
            <main-content></main-content>
        </div>
    </div>
</template>

<script>
    import HeaderRow from './HeaderRow.vue'
    import MainContent from './MainContent.vue'
    import Sidebar from './Sidebar.vue'

    export default {
        name: 'layout',

        components: {
            HeaderRow,
            MainContent,
            Sidebar,
        }
    }
</script>
<style>
    .tree-view-item-root {
        text-align: left !important;
    }
</style>
