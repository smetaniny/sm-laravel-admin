import * as React from 'react';
import {Create, SimpleForm, useRedirect, useNotify} from 'react-admin';
import {Box} from '@mui/material';
import {useState} from "react";
import TemplatesCard from "@/Pages/Admin/Resource/Templates/TemplatesCard";

const TemplatesCreate = (props) => {
    const [error, setError] = useState(null);
    const notify = useNotify();
    const redirect = useRedirect();
    const handleSubmit = async (values) => {
        await dataProvider.handleSubmitCreate('templates', values, setError, notify, redirect)
    };

    return (
        <Create
            {...props}
            title={<span>Создание шаблона</span>}
        >
            <Box maxWidth="70em">
                <SimpleForm
                    {...props}
                    undoable="false"
                    onSubmit={handleSubmit}
                >
                    <TemplatesCard error={error} flag="create" />
                </SimpleForm>
            </Box>
        </Create>
    );
};

export default TemplatesCreate;
