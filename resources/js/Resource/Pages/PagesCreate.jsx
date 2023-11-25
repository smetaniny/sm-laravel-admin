import React, {useState} from 'react';
import {Create, SimpleForm, useNotify, useRedirect, useRefresh} from 'react-admin';
import {Box,} from '@mui/material';
import dataProvider from "../../dataProvider";
import PageCard from "../../Resource/Pages/PageCard";


const PagesCreate = (props) => {
    const [error, setError] = useState(null);
    const notify = useNotify();
    const redirect = useRedirect();
    const refresh = useRefresh();

    const handleSubmit = async (values) => {
        await dataProvider.handleSubmitCreate('pages', values, setError, notify, redirect);
        refresh();
    };

    return (
        <Create
            {...props}
            title={<span>Создание страницы</span>}
        >
            <Box maxWidth="70em">
                <SimpleForm
                    {...props}
                    undoable="false"
                    onSubmit={handleSubmit}
                >
                    <PageCard error={error} flag="create" />
                </SimpleForm>
            </Box>
        </Create>
    );
};

export default PagesCreate;
