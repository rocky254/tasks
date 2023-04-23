// Data sevice
import axios from 'axios'
import authHeader from './auth-header'

const API_URL = process.env.VUE_APP_API_URL;

class StatusService {
    
    index() {
        return axios.get(API_URL + 'status', { headers: authHeader() })
    }

    show(resource) {
        return axios.put(API_URL + 'status/' + resource.id, resource, { headers: authHeader() })
    }

    store(resource) {
        return axios.post(API_URL + 'status', resource, { headers: authHeader() })
    }

    update(resource) {
        return axios.put(API_URL + 'status/' + resource.id, resource, { headers: authHeader() })
    }

    delete(resource) {
        return axios.delete(API_URL + 'status/' + resource.id, { headers: authHeader() })
    }
}

export default new StatusService()