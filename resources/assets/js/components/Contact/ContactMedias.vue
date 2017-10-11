<template>
    <div class="mdl-grid">
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--7-col">
            <create-media></create-media>
            <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp media-table" style="margin-top: 10px; width: 100%">
                <thead>
                <tr>
                    <th class="mdl-data-table__cell--non-numeric">Type</th>
                    <th class="mdl-data-table__cell--non-numeric">Data</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <media-item v-for="media in medias" :key="media.id" :media="media"></media-item>
                </tbody>
            </table>
        </div>
        <div class="mdl-layout-spacer"></div>
        <div class="mdl-cell mdl-cell--5-col col-cell--1-offset">
            <h2>Resultat</h2>
            <div v-if="medias.length">
                <tree-view :data=medias :options="{maxDepth: 6}"></tree-view>
            </div>
            <div v-if="!medias.length">
                <span>Ingen resultater</span>
            </div>
        </div>
    </div>
</template>

<script>
    import MediaItem from "./MediaItem"
    import CreateMedia from "./CreateMedia"

    export default {
        name: 'contact-medias',

        components: {
            CreateMedia,
            MediaItem
        },

        computed: {
            medias () {
                return this.$store.state.contact.medias
            }
        }
    }
</script>
