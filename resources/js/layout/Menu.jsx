import * as React from 'react';
import {useState, useMemo, useEffect} from 'react';
import Box from '@mui/material/Box';
import IconComponent from '@mui/icons-material/Folder';
import AdminPanelSettingsIcon from '@mui/icons-material/AdminPanelSettings';
import {AiOutlineSetting} from 'react-icons/ai';
import {MenuItemLink, useRedirect} from 'react-admin';
import SubMenuParent from './SubMenuParent';
import SubMenu from './SubMenu';
import menuStore from "../Stores/MenuStore";
import {ListItemIcon, ListItemButton} from '@mui/material';
import {FaFolderOpen, FaFolder, FaFile} from 'react-icons/fa';
import {AiOutlineFileAdd, AiOutlineFileDone} from 'react-icons/ai'
import {Inertia} from '@inertiajs/inertia'
import {BsFileText} from 'react-icons/bs';

const Menu = ({dense = false}) => {
    const redirect = useRedirect();
    const {items, handleToggle} = menuStore();
    const [anchorEl, setAnchorEl] = useState(true);

    const [stateSubMenuParent, setStateSubMenuParent] = useState(
        localStorage.getItem('stateSubMenuParent') === 'true' // Извлечение значения из локального хранилища
    );

    useEffect(() => {
        localStorage.setItem('stateSubMenuParent', stateSubMenuParent); // Сохранение значения в локальное хранилище
    }, [stateSubMenuParent]);

    const handleToggleAll = () => {
        setStateSubMenuParent(!stateSubMenuParent);
    };


    const DriveFileMoveIconToggle = ({handleToggle}) => (
        <FaFolder onClick={handleToggle} style={{fill: '#f8f8f8'}}>
            <IconComponent />
        </FaFolder>
    );

    const ExpandMoreToggle = ({handleToggle}) => (
        <FaFolderOpen onClick={handleToggle} style={{fill: '#8e8ea0'}}>
            <IconComponent />
        </FaFolderOpen>
    );

    const handleContextMenu = (event) => {
        event.preventDefault(); // предотвращаем открытие стандартного контекстного меню
        setAnchorEl(event.currentTarget);
    };

    const renderMenuItems = (items, parentItems) => {
        return items.map((item) => {
            const itemId = item.id;
            const slug = `pages/${item.id}`;
            const title = item.menutitle || item.title;

            if (item.children.length > 0) {
                return (
                    <SubMenu
                        key={itemId}
                        subMenuId={itemId}
                        to={slug}
                        isOpen={item.is_open}
                        name={title}
                        style={{
                            color: '#7cb2dc',
                            marginRight: 5
                        }}
                        onClick={() => Inertia.get(`/admin#/pages/${itemId}`, {}, {})}
                        driveFileMoveIcon={<DriveFileMoveIconToggle handleToggle={() => handleToggle(item)} />}
                        expandMoreIcon={<ExpandMoreToggle handleToggle={() => handleToggle(item)} />}
                        dense={dense}
                    >
                        {renderMenuItems(item.children, [...parentItems, item], itemId)}
                    </SubMenu>
                );
            } else {
                return (
                    <ListItemButton
                        key={itemId}
                        dense={dense}
                        onContextMenu={handleContextMenu}
                        className="list-item-button"
                    >
                        <ListItemIcon>
                            <BsFileText style={{fill: '#f8f8f8'}} />
                        </ListItemIcon>
                        <div
                            style={{
                                marginRight: 15,
                                fontSize: 14
                            }}
                            color="textSecondary"
                        >
                        <span
                            className={item.active ? 'yellowtttt' : ''}
                            onClick={() => {
                                redirect(`/pages/${itemId}`)
                            }}
                            style={{
                                color: '#7cb2dc',
                                marginRight: 5
                            }}> {title}
                        </span>
                           <span  className={'whiteMenu'}>
                                ({itemId})
                           </span>
                        </div>
                        <ListItemIcon
                            style={{fill: "#f8f8f8"}}
                            className="add-icon"
                            onClick={() => Inertia.get(`/admin#/pages/create?itemId=${itemId}`, {}, {})}>
                            <AiOutlineFileAdd style={{fill: '#f8f8f8'}} />
                        </ListItemIcon>
                        <ListItemIcon
                            className="edit-icon"
                            onClick={() => Inertia.get(`/admin#/pages/${itemId}`, {}, {})}
                        >
                            <AiOutlineFileDone style={{fill: '#f8f8f8'}} />
                        </ListItemIcon>
                    </ListItemButton>
                );
            }
        });
    };


    const menuItems = useMemo(() => {
        return renderMenuItems(items, items);
    }, [dense, items, anchorEl]);

    return (
        <Box
            sx={{
                width: open ? "100%" : 50,
                marginTop: 1,
                marginBottom: 1,
                transition: (theme) =>
                    theme.transitions.create("width", {
                        easing: theme.transitions.easing.sharp,
                        duration: theme.transitions.duration.leavingScreen,
                    }),
            }}
            className="subMenuLeft"
        >
            <SubMenuParent
                handleToggle={() => handleToggleAll()}
                isOpen={stateSubMenuParent}
                name="Админка"
                icon={<AdminPanelSettingsIcon style={{fill: '#f8f8f8', color: "#f8f8f8"}} />}
                iconOpen={<AdminPanelSettingsIcon style={{fill: '#f8f8f8', color: "#f8f8f8"}} />}
            >
                <MenuItemLink
                    to={`/`}
                    className="menuCollr"
                    primaryText="Дашборд"
                    leftIcon={<AiOutlineSetting style={{fill: '#f8f8f8'}} />}
                />
                <MenuItemLink
                    to={`/templates`}
                    className="menuCollr"
                    primaryText="Шаблоны"
                    leftIcon={<AiOutlineSetting style={{fill: '#f8f8f8'}} />}
                />
                <MenuItemLink
                    to={`/tv_param_categories`}
                    className="menuCollr"
                    primaryText="TV категории"
                    leftIcon={<AiOutlineSetting style={{fill: '#f8f8f8'}} />}
                />
                <MenuItemLink
                    to={`/tv_params`}
                    className="menuCollr"
                    primaryText="TV параметры"
                    leftIcon={<AiOutlineSetting style={{fill: '#f8f8f8'}} />}
                />
            </SubMenuParent>
            {menuItems}
        </Box>
    );
};


export default Menu;
