import axios from 'axios'

export default axios.create({
    headers: {
        'Authorization': 'NemID ' + localStorage.getItem('axios_nemid_auth_payload')
    }
})
