<template>
    <main class="mdl-layout__content page-content">
        <div class="mdl-grid">
            <div class="mdl-cell mdl-cell--12-col">
                <router-view></router-view>
            </div>
        </div>
    </main>
</template>

<script>
    export default {
        name: 'main-content',
    }
</script>
