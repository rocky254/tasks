// Authentication service
import axios from 'axios'
import authHeader from './auth-header'

const API_URL = process.env.VUE_APP_API_URL;

class AuthService {
    // login function
    login(user) {
        return axios.post(API_URL + 'auth/login', {
            email: user.email,
            password: user.password
        }).then(response => {
            return response
        });
    }
    // logout function
    logout() {
        localStorage.removeItem('user')
    }
    // register function
    register(user) {
        return axios.post(API_URL + 'auth/register', {
            name: user.name,
            email: user.email,
            password: user.password,
            password_confirmation: user.confirmPassword,
            callback: user.callback
        }).then(response => {
            return response
        });
    }
    // update profile function
    update(user) {
        return axios.post(API_URL + 'auth/update-profile/' + user.id, {
            name: user.name,
            email: user.email,
            current_password: user.current_password,
            password: user.password,
            password_confirmation: user.password
        }, { headers: authHeader() }).then(response => {
            return response
        });
    }
}

export default new AuthService()