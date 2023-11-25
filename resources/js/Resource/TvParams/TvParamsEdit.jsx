import React, {useState} from 'react';
import {Edit, SimpleForm, useRefresh, useNotify} from 'react-admin';
import dataProvider from "../../dataProvider";
import TvParamsCard from "../../Resource/TvParams/TvParamsCard";

const TvParamsEdit = () => {
    const [error, setError] = useState(null);
    const [code, setCode] = useState('');
    const refresh = useRefresh();
    const notify = useNotify();

    const handleSubmit = async (values) => {
        const updatedValues = {
            ...values,
            setting: code,
        };
        await dataProvider.handleSubmitEdit('tv_params', updatedValues, setError, notify)
    };

    const onSuccess = () => {
        refresh();
        notify('Изменения сохранены');
    };

    return <Edit
        mutationOptions={{onSuccess}}
        title={<span>Редактирование TV параметра</span>}
        component="div">
        <SimpleForm
            undoable="false"
            onSubmit={handleSubmit}>
            <TvParamsCard error={error} setCode={setCode} code={code} flag="edit" />
        </SimpleForm>
    </Edit>
};

export default TvParamsEdit;
