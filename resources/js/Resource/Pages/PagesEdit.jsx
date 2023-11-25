import * as React from 'react';
import {useState} from 'react';
import {Edit, SimpleForm, useRefresh, useNotify, useRecordContext} from 'react-admin';
import PageCard from "../../Resource/Pages/PageCard";
import dataProvider from "../../dataProvider";


const PagesEdit = () => {
    const [error, setError] = useState(null);
    const [value, setValue] = useState(0);
    const refresh = useRefresh();
    const notify = useNotify();

    const handleSubmit = async (values) => {
        await dataProvider.handleSubmitEdit('pages', values, setError, notify);
        setValue(0);
        refresh();
    };

    const onSuccess = () => {
        refresh();
        notify('Изменения сохранены');
    };

    const handleChange = (event, newValue) => {
        setValue(newValue);
    };

    const PagesTitle = () => {
        const record = useRecordContext();
        return record ? (
            <span>
            {record.title}
        </span>
        ) : null;
    };

    return <Edit
        mutationOptions={{onSuccess}}
        title={<PagesTitle />}
        component="div">
        <SimpleForm
            undoable="false"
            onSubmit={handleSubmit}>
            <PageCard error={error} setValue={setValue} value={value} handleChange={handleChange} flag="edit" />
        </SimpleForm>
    </Edit>

};


export default PagesEdit;
