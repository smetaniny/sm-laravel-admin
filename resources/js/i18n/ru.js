import russianMessages from 'ra-language-russian';


export default {
    ...russianMessages,
    pos: {
        search: 'Search',
        configuration: 'Configuration',
        language: 'Language',
        theme: {
            name: 'Theme',
            light: 'Light',
            dark: 'Dark',
        },
        dashboard: {
            monthly_revenue: 'Monthly Revenue',
            month_history: '30 Day Revenue History',
            new_orders: 'New Orders',
            pending_reviews: 'Pending Reviews',
            all_reviews: 'See all reviews',
            new_customers: 'New Customers',
            all_customers: 'See all customers',
            pending_orders: 'Pending Orders',
            order: {
                items:
                    'by %{customer_name}, one item |||| by %{customer_name}, %{nb_items} items',
            },
            welcome: {
                title: 'Добро пожаловать в react-admin для электронной коммерции',
                subtitle:"Это администратор вашего магазина ",
                ra_button: 'react-admin site',
                demo_button: 'Source for this demo',
            },
        },
        menu: {
            admin: 'Админка',
            sites: 'Сайт',
            customers: 'Customers',
        },
    },
    resources: {

    },
    ra: {
        message: {
            not_found: 'Сообщение',
            invalid_form: 'Форма заполнена неверно, проверьте, пожалуйста, ошибки'
        },
        action: {
            back: 'Назад',
            export: 'Скачать',
            create: 'Создать',
            save: 'Сохранить',
            search: 'Поиск',
            cancel: 'Отменить',
            confirm: 'Подтвердить',
            undo: 'Отменить',
            unselect: 'Отменить выбор',
            sort: 'Сортировать',
            edit: 'Редактировать',
            delete: 'Удалить',

        },
        notification: {
            updated: 'Обновление',
            deleted: 'Удаление',
        },
        page: {
            not_found: 'Не найден',
            dashboard: 'Панель',
            edit: 'Редактирование',
            list: 'Список',
            create: 'Создать',
            eror: 'Ошибка',
        },
        configurable: {
            customize: 'Настроить',
        },
        navigation: {
            skip_nav: 'Пропустить навигацию',
            no_results: 'Создайте свою первую запись',
            page_rows_per_page: 'Количество строк на странице',
        },
        auth: {
            username: 'Имя',
            password: 'Пароль',
            sign_in: 'Авторизоваться',
        },
        validation: {
            required: 'Обязательно для заполнения'
        },
        input: {
            image: {
                upload_single: "Загрузите файл"
            }
        }
    },
};
