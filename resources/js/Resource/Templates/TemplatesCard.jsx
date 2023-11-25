import React from 'react';
import {Card, CardContent, Grid, Tab, Tabs} from "@mui/material";
import {TextInput} from "react-admin";

const TemplatesCard = ({error, flag, ...props}) => {
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
                <Grid container spacing={1}>
                    <Grid item xs={12} sm={12}>
                        <Grid container spacing={2}>
                            <TextInputId flag={flag} />
                            <Grid item xs={12}>
                                <TextInput
                                    source="name"
                                    label="Имя шаблона (всегда уникальный)"
                                    fullWidth
                                />
                                {error && <div className="error-text">{error.name}</div>}
                            </Grid>
                            <Grid item xs={12}>
                                <TextInput source="description"
                                           label="Описание"
                                           fullWidth
                                />
                                {error && <div className="error-text">{error.description}</div>}
                            </Grid>
                            <Grid item xs={12}>
                                <TextInput source="header_code"
                                           label="Подключение внешних стилей, скриптов или счетчиков посещений (будет помещен в header)"
                                           multiline={true}
                                           fullWidth
                                />
                                {error && <div className="error-text">{error.header_code}</div>}
                            </Grid>
                            <Grid item xs={12}>
                                <TextInput source="footer_code"
                                           label="Подключение внешних стилей, скриптов или счетчиков посещений (будет помещен в footer)"
                                           multiline={true}
                                           fullWidth
                                />
                                {error && <div className="error-text">{error.footer_code}</div>}
                            </Grid>
                        </Grid>
                    </Grid>
                </Grid>
            </CardContent>
        </Card>
    );
};

export default TemplatesCard;
