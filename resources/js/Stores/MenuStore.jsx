import {create} from 'zustand';
import {devtools} from 'zustand/middleware';
import {useRedirect} from "react-admin";
import {URL_API} from "../settings.jsx";
import axios from 'axios';

const toggleItem = (items, id) => {
    let result = items.map((item) => {
        if (item.id === id) {
            return {...item, is_open: !item.is_open, active: true};
        } else if (item.children) {
            return {...item, children: toggleItem(item.children, id)};
        } else {
            return item;
        }
    });

    const findItemById = (items, id) => {
        for (let item of items) {
            if (item.id === id) {
                return item;
            } else if (item.children) {
                const foundItem = findItemById(item.children, id);
                if (foundItem) {
                    return foundItem;
                }
            }
        }
        return null;
    };

    let item = findItemById(result, id);

    if (item !== null && item.id === id) {
        try {
            axios.put(URL_API + `/pages/${item.id}`, item);
        } catch (error) {
            console.log('error', error);
        }
    }

    return result;
};

const menuStore = create(
    devtools(
        (set, get) => ({
            items: [],
            menu_id: null,
            handleToggle: (item) => {
                if (item) {
                    set((state) => ({
                        items: toggleItem(state.items, item.id, (itemId) => {
                            const redirect = useRedirect();
                            // Обновление свойства id после сохранения itemId
                            localStorage.setItem('menu_id', subMenuId);
                            redirect(`pages`);
                            set({menu_id: itemId});
                        }),
                    }));
                }
            },
            handleContextMenu: async (title) => {
                let parentItems = get().findItemsByTitle(title);
                const titleChildren = window.prompt('Введите название страницы');
                try {
                    await axios.post(URL_API + '/pages', {
                        title: titleChildren,
                        parentItems: parentItems.length === 0 ? null : parentItems[0].id,
                    });
                } catch (error) {
                    console.error('Failed to create page', error);
                }
            },
            setItems: (newItems) => {
                set((state) => ({...state, items: newItems}));
            },
            findItemsByTitle: (title) => {
                function search(items) {
                    for (let i = 0; i < items.length; i++) {
                        let item = items[i];

                        if (item.title === title) {
                            return item.id;
                        }

                        if (item.children) {
                            let result = search(item.children);
                            if (result !== null) {
                                return result;
                            }
                        }
                    }

                    return null;
                }

                return search(get().items);
            },
        }))
)

export default menuStore;
