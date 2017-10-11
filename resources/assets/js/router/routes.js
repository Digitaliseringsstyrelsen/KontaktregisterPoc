import Contact from '../components/Contact/Contact.vue'
import Search from '../components/Search/Search.vue'
import Register from '../components/Registration/Register.vue'

export default [
    {
        name: 'home',
        path: '/',
        component: Search
    },
    {
        name: 'search',
        path: '/search',
        component: Search
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'contact',
        path: '/contacts/:identifier',
        component: Contact
    }
]
