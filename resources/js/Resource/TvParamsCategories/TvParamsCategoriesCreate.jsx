import React, {useState} from 'react';
import {Create, SimpleForm, TextInput, useNotify, useRedirect} from 'react-admin';
import dataProvider from '../../dataProvider';
import {Box, Card, CardContent, Grid} from "@mui/material";

const TvParamsCategoriesCreate = ({...props}) => {
    const [error, setError] = useState(null);
    const notify = useNotify();
    const redirect = useRedirect();
    const handleSubmit = async (values) => {
        await dataProvider.handleSubmitCreate('tv_param_categories', values, setError, notify, redirect);
    };

    return (
        <>
            <Create {...props} title={<span>Создание категории для TV параметра</span>}>
                <Box maxWidth="70em">
                    <SimpleForm {...props} undoable={false} onSubmit={handleSubmit}>
                        <Card>
                            <CardContent>
                                <Grid item xs={12}>
                                    <TextInput source="name" label="Имя категории" fullWidth />
                                    {error && <div className="error-text">{error.name}</div>}
                                </Grid>
                            </CardContent>
                        </Card>
                    </SimpleForm>
                </Box>
            </Create>
        </>
    );
};

export default TvParamsCategoriesCreate;
