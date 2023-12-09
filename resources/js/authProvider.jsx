import {AUTH_URL_API, TOKEN_URL_API} from "./settings";
import axios from 'axios';

const authProvider = (canLogin) => ({
    login: async ({username, password}) => {
        const response = await axios.post(AUTH_URL_API, {
            email: username,
            password: password,
        }, {
            headers: {
                'Content-Type': 'application/json;charset=utf-8'
            },
        });
        let result = await response.data;
        const {status = false, user = {}} = result;
        if (status) {
            return Promise.resolve();
        }

        return Promise.reject();
    },
    logout: async () => {
        // Inertia.post(route('logoutAdmin'));
    },
    checkAuth: async () => {
        try {
            if (canLogin) {
                return Promise.resolve();
            }

            const response = await axios.post(TOKEN_URL_API);
            const result = response.data;
            const {status = false} = result;
            if (status) {
                return Promise.resolve();
            }
            return Promise.reject();
        } catch (e) {
            return Promise.reject();
        }
    },
    checkError: async (error) => {
        // handle error logic here
    },
    getPermissions: async () => {
        // handle permissions logic here
    },
    getIdentity: async () => {
        // return null since user is not authenticated
        return Promise.resolve(null);
    },
});

export default authProvider;

