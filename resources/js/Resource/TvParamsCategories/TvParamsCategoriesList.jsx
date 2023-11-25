import React from 'react';
import {DatagridConfigurable, List, TextField,} from "react-admin";
import {Fragment} from "react";
import {ListActions, OrderFilters} from "../../layout/BaseList";
import {Divider} from "@mui/material";

const TvParamsCategoriesList = (props) => (
    <List
        {...props}
        filterDefaultValues={{status: 'ordered'}}
        sort={{field: 'created_at', order: 'DESC'}}
        perPage={10}
        filters={OrderFilters}
        actions={<ListActions />}
    >
        <Fragment>
            <Divider />
            <DatagridConfigurable
                rowClick="edit"
                omit={['total_ex_taxes', 'delivery_fees', 'taxes']}
            >
                <TextField source="id" label="Индитификатор" />
                <TextField source="name" label="Имя" />
            </DatagridConfigurable>
        </Fragment>
    </List>
);

export default TvParamsCategoriesList;
