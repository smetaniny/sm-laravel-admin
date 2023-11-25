import * as React from 'react';
import {forwardRef} from 'react';
import {AppBar, UserMenu, MenuItemLink, useTranslate, Logout} from 'react-admin';
import {Typography} from '@mui/material';
import SettingsIcon from '@material-ui/icons/Settings';
import {makeStyles} from '@material-ui/core/styles';
import DescriptionOutlinedIcon from "@material-ui/icons/DescriptionOutlined";


const ConfigurationMenu = forwardRef((props, ref) => {
    const translate = useTranslate();
    return (
        <>
            <MenuItemLink
                ref={ref}
                to="/configuration"
                primaryText={translate('pos.configuration')}
                leftIcon={<SettingsIcon />}
                onClick={props.onClick}
                sidebarIsOpen
            />
        </>
    );
});

const CustomUserMenu = (props) => (
    <UserMenu {...props}>
        <ConfigurationMenu />
        <Logout />
    </UserMenu>
);

const CustomAppBar = (props) => {
    const classes = useStyles();
    return (
        <AppBar {...props} elevation={1} userMenu={<CustomUserMenu />}>
            <Typography
                variant="h6"
                color="inherit"
                className={classes.title}
                id="react-admin-title"
            />
            <a href={location.origin}
               target={`_blank`}
            style={{
                textTransform: 'uppercase',
                color: 'white',
                fontWeight: 'bold',
                fontSize: '22px',
                lineHeight: '1.5',
                textDecoration: 'none'
            }}>
                Студия Сметаниных
            </a>
            <span className={classes.spacer} />
            <MenuItemLink to={`/pages/create`} >
                <DescriptionOutlinedIcon
                    className={classes.filesHeaderIcons}
                />
            </MenuItemLink>
        </AppBar>
    );
};

const useStyles = makeStyles({
    title: {
        flex: 1,
        textOverflow: 'ellipsis',
        whiteSpace: 'nowrap',
        overflow: 'hidden',
    },
    spacer: {
        flex: 1,
    },
    filesHeaderIcons: {
        cursor: 'pointer'
    }
});

export default CustomAppBar;
