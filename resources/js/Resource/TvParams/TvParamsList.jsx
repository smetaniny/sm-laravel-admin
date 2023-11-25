import React, {Fragment} from 'react';
import {List, TextField, DatagridConfigurable} from 'react-admin';
import {ListActions, OrderFilters} from "../../layout/BaseList";
import {Divider} from "@mui/material";

const TvParamsList = (props) => (
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
                <TextField source="caption" label="Заголовок" />
            </DatagridConfigurable>
        </Fragment>
    </List>
);

export default TvParamsList;
