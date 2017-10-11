<template>
    <tr>
        <td class="mdl-data-table__cell--non-numeric" v-text="entry.type"></td>
        <td class="mdl-data-table__cell--non-numeric">
            <tree-view :data=entry.old_value :options="{maxDepth: 3}"></tree-view>
        </td>
        <td class="mdl-data-table__cell--non-numeric">
            <tree-view :data=entry.new_value :options="{maxDepth: 3}"></tree-view>
        </td>
        <td v-text="entry.occurrence"></td>
    </tr>
</template>

<script>
    export default {
        name: 'activity-item',

        props: ['entry']
    }
</script>
