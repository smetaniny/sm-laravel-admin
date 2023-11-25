import React from 'react';
import {Box, Card, CardContent, Tab, Tabs} from "@mui/material";
// import TabOneCard from "../../Resource/Pages/TabOneCard";
// import TabThreeCard from "../../Resource/Pages/TabThreeCard";
// import TabFourCard from "../../Resource/Pages/TabFourCard";
// import TabFiveCard from "../../Resource/Pages/TabFiveCard";


const PageCard = ({error, flag, setValue, value, handleChange, ...props}) => {
    return (
        <Box maxWidth="70em">
            <Card>
                <CardContent>
                    <Tabs value={value} onChange={handleChange} style={{marginBottom: 30}}>
                        <Tab label="Общие" />
                        <Tab label="Соцсети" />
                        <Tab label="Настройка страницы" />
                        {flag === 'edit' && <Tab label="TV Параметры" />}
                    </Tabs>
                    {/*{value === 0 && (*/}
                    {/*    <TabOneCard error={error} flag={flag} setValue={setValue} {...props} />*/}
                    {/*)}*/}
                    {/*{value === 1 && (*/}
                    {/*    <TabThreeCard error={error} flag={flag} setValue={setValue} {...props} />*/}
                    {/*)}*/}
                    {/*{value === 2 && (*/}
                    {/*    <TabFourCard error={error} flag={flag} setValue={setValue} {...props} />*/}
                    {/*)}*/}
                    {/*{value === 3 && (*/}
                    {/*    <TabFiveCard error={error} flag={flag} setValue={setValue} {...props} />*/}
                    {/*)}*/}
                </CardContent>
            </Card>
        </Box>
    );
};


export default PageCard;
