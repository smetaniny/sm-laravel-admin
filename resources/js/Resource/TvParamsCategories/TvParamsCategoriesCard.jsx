import React from 'react';
import {Card, CardContent, Grid, Tab, Tabs} from "@mui/material";
import {TextInput} from "react-admin";

const TvParamsCategoriesCard = ({error, flag}) => {
    const TextInputId = ({flag}) => {
        if (flag !== "create") {
            return <Grid item xs={12}>
                <TextInput
                    source="id"
                    label="Индитификатор"
                    disabled={true}
                    fullWidth
                /></Grid>
        } else {
            return null;
        }
    };

    return (
        <Card>
            <CardContent>
                <Tabs value={0} style={{
                    marginBottom: 30
                }}>
                    <Tab label="Общие" />
                </Tabs>
                <Grid item xs={12} sm={12}>
                    <Grid container spacing={2}>
                        <TextInputId flag={flag} />
                        <Grid item xs={12}>
                            <TextInput source="name" label="Имя категории" fullWidth />
                            {error && <div className="error-text">{error.name}</div>}
                        </Grid>
                    </Grid>
                </Grid>
            </CardContent>
        </Card>
    );
};

export default TvParamsCategoriesCard;
