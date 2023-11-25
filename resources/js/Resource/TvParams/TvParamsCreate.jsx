import React, {useState} from 'react';
import dataProvider from "../../dataProvider";
import {
    Create,
    SimpleForm,
    useNotify, useRedirect,
} from 'react-admin';
import {Box} from "@mui/material";
import TvParamsCard from "../../Resource/TvParams/TvParamsCard";

const TvParamsCreate = (props) => {
    const [error, setError] = useState(null);
    const [code, setCode] = useState('');
    const notify = useNotify();
    const redirect = useRedirect();

    const handleSubmit = async (values) => {
        const updatedValues = {
            ...values,
            setting: code,
        };
        await dataProvider.handleSubmitCreate('tv_params', updatedValues, setError, notify, redirect)
    };

    return (
        <Create
            {...props}
            title={<span>Создание TV параметра</span>}
        >
            <Box maxWidth="70em">
                <SimpleForm
                    {...props}
                    undoable="false"
                    onSubmit={handleSubmit}
                >
                    <TvParamsCard error={error} setCode={setCode} code={code} flag="create" />
                </SimpleForm>
            </Box>
        </Create>
    );
};

export default TvParamsCreate;
