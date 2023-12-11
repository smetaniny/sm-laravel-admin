import * as React from 'react';
import {Layout, Sidebar} from 'react-admin';
import {useState} from "react";
import AppBar from './AppBar';
import Menu from './Menu';
import {ResizableBox} from "react-resizable";
import 'react-resizable/css/styles.css';
import {isDesktop} from '../Helpers';

const CustomSidebar = (props) => {
    const [size, setSize] = useState({width: 500, height: 100});

    const handleResize = (event, {size}) => {
        setSize(size);
    };


    return (
        isDesktop() ? (
            <ResizableBox
                className="resizable-box"
                width={size.width}
                height={size.height}
                onResize={handleResize}
            >
                <Sidebar
                    {...props}
                    size={size}
                />
            </ResizableBox>
        ) : (
            <Sidebar
                {...props}
                size={size}
            />
        )
    );
}

export default (props) => (
    <Layout {...props} appBar={AppBar} menu={Menu} sidebar={CustomSidebar} />
);
