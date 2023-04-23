// Data sevice
import axios from 'axios'
import authHeader from './auth-header'

const API_URL = process.env.VUE_APP_API_URL;

class TasksService {
    
    index() {
        return axios.get(API_URL + 'tasks', { headers: authHeader() })
    }

    tasksCount() {
        return axios.get(API_URL + 'all-tasks-count', { headers: authHeader() })
    }

    assignedTasksCount() {
        return axios.get(API_URL + 'assigned-tasks-count', { headers: authHeader() })
    }

    recentTasks() {
        return axios.get(API_URL + 'recent-tasks', { headers: authHeader() })
    }

    show(resource) {
        return axios.put(API_URL + 'tasks/' + resource.id, resource, { headers: authHeader() })
    }

    store(resource) {
        return axios.post(API_URL + 'tasks', resource, { headers: authHeader() })
    }

    update(resource) {
        return axios.put(API_URL + 'tasks/' + resource.id, resource, { headers: authHeader() })
    }

    delete(resource) {
        return axios.delete(API_URL + 'tasks/' + resource.id, { headers: authHeader() })
    }
}

export default new TasksService()