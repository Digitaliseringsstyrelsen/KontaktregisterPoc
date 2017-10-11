if (! String.prototype.splice) {
    String.prototype.splice = function (start, delCount, newSubStr) {
        return this.slice(0, start) + newSubStr + this.slice(start + Math.abs(delCount))
    }
}

if (! String.prototype.asFormattedIdentifier) {
    String.prototype.asFormattedIdentifier = function (type) {
        return type === 'person' ? this.splice(6, 0, '-') : this
    }
}

import Vue from 'vue'
import VueMdl from 'vue-mdl'
import TreeView from 'vue-json-tree-view'
import store from './store'
import router from './router'
import Layout from './components/Layout/Layout.vue'
import 'vue2-toast/lib/toast.css';
import Toast from 'vue2-toast';

Vue.use(Toast);
Vue.use(VueMdl)
Vue.use(TreeView)

const app = new Vue({
    el: '#app',
    store,
    router,

    components: {
        Layout
    },

    computed: {
        title () {
            return this.$store.state.page_title
        }
    },

    watch: {
        title: (val, old) => {
            document.title = val
        }
    },
});
