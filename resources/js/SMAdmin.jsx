import React, {useEffect, useState} from 'react';
import {Admin, Resource} from 'react-admin';
import dataProvider from "./dataProvider";
import menuStore from "./Stores/MenuStore";
import Layout from "./layout";
import i18nProvider from "./i18nProvider";
import authProvider from "./authProvider";
import themeReducer from "./themeReducer";
import Dashboard from "./dashboard/Dashboard";
import {smetaninyTheme} from "./layout/Themes";
import Loader from "./Components/Loader";
import Login from "./Login";
import PagesList from "./Resource/Pages/PagesList";
import PagesEdit from "./Resource/Pages/PagesEdit";
import PagesCreate from "./Resource/Pages/PagesCreate";
import TemplatesCreate from "./Resource/Templates/TemplatesCreate";
import TemplatesList from "./Resource/Templates/TemplatesList";
import TemplatesEdit from "./Resource/Templates/TemplatesEdit";
import TvParamsList from "./Resource/TvParams/TvParamsList";
import TvParamsCreate from "./Resource/TvParams/TvParamsCreate";
import TvParamsEdit from "./Resource/TvParams/TvParamsEdit";
import TvParamsCategoriesList from "./Resource/TvParamsCategories/TvParamsCategoriesList";
import TvParamsCategoriesEdit from "./Resource/TvParamsCategories/TvParamsCategoriesEdit";
import TvParamsCategoriesCreate from "./Resource/TvParamsCategories/TvParamsCategoriesCreate";
import { usePage } from "@inertiajs/inertia-react";

const App = () => {
    const { props } = usePage();

    // Ваши действия с данными от Inertia.js
    console.log('Data from Inertia:', props);

    // или через деструктуризацию
    const {pages} = props;
    const {setItems} = menuStore();

    useEffect(() => {
        setItems(pages);
    }, [pages, setItems]);


    const [showLoader, setShowLoader] = useState(false);

    useEffect(() => {
        if (!props.canLogin) {
            const timeout = setTimeout(() => {
                setShowLoader(false);
            }, 3000);

            setShowLoader(true);

            return () => {
                clearTimeout(timeout);
            };
        }
    }, [props.canLogin]);

    return <>
        {showLoader && <Loader />}
        <Admin
            dataProvider={dataProvider}
            theme={smetaninyTheme}
            i18nProvider={i18nProvider}
            customReducers={{theme: themeReducer}}
            layout={(props) => <Layout {...props} />}
            authProvider={authProvider(props.canLogin)}
            dashboard={Dashboard}
            loginPage={Login}
        >
            <Resource
                name="templates"
                list={TemplatesList}
                edit={TemplatesEdit}
                create={TemplatesCreate}
            />
            <Resource
                name="tv_param_categories"
                list={TvParamsCategoriesList}
                create={TvParamsCategoriesCreate}
                edit={TvParamsCategoriesEdit}
            />
            <Resource
                name="tv_params"
                list={TvParamsList}
                create={TvParamsCreate}
                edit={TvParamsEdit}
            />
            <Resource
                name="pages"
                list={PagesList}
                edit={PagesEdit}
                create={PagesCreate}
            />
        </Admin>
    </>
};

export default App;
