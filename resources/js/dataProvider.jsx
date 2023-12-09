import {URL_API} from "./settings";
import queryString from 'query-string';
import {Inertia} from "@inertiajs/inertia";
import axios from 'axios';

const apiClient = axios.create({
    baseURL: URL_API,
});

apiClient.interceptors.request.use(config => {
    return config;
});

const dataProvider = {
    getList: async (resource, params) => {
        let url = `${resource}?${queryString.stringify(params.query)}`;
        if (resource === 'pages') {
            url = `${url}&menu_id=${localStorage.getItem('menu_id')}`
        }

        const response = await apiClient.get(url);
        const data = Array.isArray(response.data) ? response.data : [response.data];
        const formattedData = data.map(item => ({...item, id: item.id ? item.id.toString() : null}));
        return {
            data: formattedData,
            total: formattedData.length,
        };
    },

    getMany: async (resource, params) => {
        const url = `${resource}?${queryString.stringify({id: params.ids})}`;
        const response = await apiClient.get(url);
        const data = Array.isArray(response.data) ? response.data : [response.data];
        const formattedData = data.map(item => ({...item, id: item.id ? item.id.toString() : null}));
        return {
            data: formattedData,
        };
    },

    getOne: async (resource, params) => {
        const url = `${resource}/${params.id}`;
        const {data} = await apiClient.get(url);
        return {
            data: data,
        };
    },

    create: async (resource, params) => {
        const url = `${resource}`;
        const {data} = await apiClient.post(url, params.data);
        return {
            data: {...data, id: data.id},
        };
    },

    update: async (resource, params) => {
        const url = `${resource}/${params.id}`;
        const {data} = await apiClient.put(url, params);
        Inertia.reload();
        return {
            data: data,
        };
    },

    delete: async (resource, params) => {
        const url = `${resource}/${params.id}`;
        await apiClient.delete(url);
        Inertia.reload();
        return {
            data: params.previousData,
        };
    },

    deleteMany: async (resource, params) => {
        const {ids} = params;
        await Promise.all(ids.map(async (id) => {
            const url = `${resource}/${id}`;
            await apiClient.delete(url);
        }));
        return {
            data: ids,
        };
    },

    handleSubmitCreate: async (resource, values, setError, notify, redirect) => {
        setError(null);
        try {
            const {data} = await dataProvider.create(resource, {
                data: values
            });
            if (data.status) {
                notify(data.message, {type: 'success', undoable: false});
                // Получение идентификатора только что созданного элемента
                const itemId = data.id;
                // Перенаправление на страницу редактирования созданного элемента
                redirect(`/${resource}/${itemId}`);
                Inertia.reload();
            }
        } catch (error) {
            notify(error.response.data.message, {type: 'warning', undoable: false});
            setError(error.response.data.errors);
        }
    },

    handleSubmitEdit: async (resource, values, setError, notify) => {
        setError(null);
        try {
            const {data} = await dataProvider.update(resource, values);
            if (data.status) {
                notify(data.message, {type: 'success', undoable: false});
                Inertia.reload();
            }
        } catch (error) {
            notify(error.response.data.message, {type: 'warning', undoable: false});
            setError(error.response.data.errors);
        }
    },
};

export default dataProvider;
