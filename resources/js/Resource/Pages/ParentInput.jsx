import React from 'react';
import {Grid} from "@mui/material";
import {AutocompleteInput, useRecordContext} from "react-admin";
import menuStore from "../../Stores/MenuStore";
import {useLocation} from "react-router-dom";

const ParentInput = ({flag, error}) => {
    const record = useRecordContext();
    const {items} = menuStore();
    const location = useLocation();
    const itemId = new URLSearchParams(location.search).get('itemId');

    const flatten = (arr, result = []) => {
        arr.forEach(item => {
            result.push({id: item.id, name: item.title});
            if (item.children) {
                flatten(item.children, result);
            }
        });
        return result;
    };

    const pages = flatten(items);

    return (
        <Grid item xs={12}>
            <AutocompleteInput
                label="Родительская страница"
                source="parent_id"
                choices={pages}
                optionText="name"
                optionValue="id"
            />
            {error && <div className="error-text">{error.parent_id}</div>}
        </Grid>
    );
};

export default ParentInput;
