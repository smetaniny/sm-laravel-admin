import React, {useState} from 'react';
import {Edit, SimpleForm, useNotify, useRefresh} from "react-admin";
import dataProvider from "../../dataProvider";
import TvParamsCategoriesCard from "../../Resource/TvParamsCategories/TvParamsCategoriesCard";

const TvParamsCategoriesEdit = () => {
    const [error, setError] = useState(null);
    const refresh = useRefresh();
    const notify = useNotify();

    const handleSubmit = async (values) => {
        await dataProvider.handleSubmitEdit('tv_param_categories', values, setError, notify)
    };

    const onSuccess = () => {
        refresh();
        notify('Изменения сохранены');
    };

    return <Edit
        mutationOptions={{onSuccess}}
        title={<span>Редактирование категории для TV параметра</span>}
        component="div">
        <SimpleForm
            undoable="false"
            onSubmit={handleSubmit}>
            <TvParamsCategoriesCard error={error} flag="edit" />
        </SimpleForm>
    </Edit>
};

export default TvParamsCategoriesEdit;
