// Data sevice
import axios from 'axios'
import authHeader from './auth-header'

const API_URL = process.env.VUE_APP_API_URL;

class UsersService {
    
    index() {
        return axios.get(API_URL + 'users', { headers: authHeader() })
    }

    usersCount() {
        return axios.get(API_URL + 'all-users-count', { headers: authHeader() })
    }

}

export default new UsersService()