import React, {useState} from 'react';
import {Edit, useRecordContext, useNotify, SimpleForm, useRefresh, Toolbar, SaveButton, ListButton,} from 'react-admin';
import dataProvider from "@/Pages/Admin/dataProvider";
import TemplatesCard from "@/Pages/Admin/Resource/Templates/TemplatesCard";


const TemplatesEdit = (props) => {
    const [error, setError] = useState(null);
    const refresh = useRefresh();
    const notify = useNotify();

    const handleSubmit = async (values) => {
        await dataProvider.handleSubmitEdit('templates', values, setError, notify)
    };

    const onSuccess = () => {
        refresh();
        notify('Изменения сохранены');
    };

    return <Edit
        mutationOptions={{onSuccess}}
        title={<span>Редактирование шаблона</span>}
        component="div">
        <SimpleForm
            undoable="false"
            onSubmit={handleSubmit}>
            <TemplatesCard error={error} flag="edit" />
        </SimpleForm>
    </Edit>
};

export default TemplatesEdit;
