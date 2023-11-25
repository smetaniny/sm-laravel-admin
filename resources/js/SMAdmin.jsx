import React, {useEffect, useState} from 'react';
import {Admin, Resource} from 'react-admin';
import dataProvider from "./dataProvider.jsx";

const App = () => {
    return (
        <Admin
            dataProvider={dataProvider}
        >
        </Admin>
    );
};


