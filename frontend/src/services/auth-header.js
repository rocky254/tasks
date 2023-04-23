export default function authHeader() {
    let token = localStorage.getItem('token')
    if (token) {
        return {
            'Authorization': 'Bearer ' + token,
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    } else {
        return {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        }
    }
}