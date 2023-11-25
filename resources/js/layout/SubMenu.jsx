import * as React from 'react';
import {
    List,
    MenuItem,
    ListItemIcon,
    Typography,
    Collapse,
    Tooltip,
} from '@mui/material';
import {useRedirect, useSidebarState} from 'react-admin';
import {AiOutlineFileAdd, AiOutlineFileDone} from "react-icons/ai";


const SubMenu = (props) => {
    const {isOpen, name, driveFileMoveIcon, expandMoreIcon, children, dense, to, onClick, subMenuId} = props;
    const [sidebarIsOpen] = useSidebarState();
    const redirect = useRedirect();
    const header = (
        <MenuItem dense={dense}
                  to={to}
                  className="list-item-button"

        >
            <ListItemIcon sx={{minWidth: 5}}  style={{fill: 'white'}}>
                {Boolean(isOpen) ? expandMoreIcon : driveFileMoveIcon}
            </ListItemIcon>
            <Typography
                variant="inherit"
                color="textSecondary"

                >
                <span
                    onClick={onClick}
                    style={{
                        marginRight: 5
                    }}> {name}
                        </span>
                ({subMenuId})
            </Typography>
            <div style={{marginLeft: 5}}>
                <ListItemIcon className="add-icon" onClick={() => redirect(`/pages/create?itemId=${subMenuId}`)}>
                    <AiOutlineFileAdd style={{fill: 'white'}} />
                </ListItemIcon>
                <ListItemIcon className="edit-icon" onClick={() => redirect(`/pages/${subMenuId}`)}>
                    <AiOutlineFileDone style={{fill: 'white'}} />
                </ListItemIcon>
            </div>
        </MenuItem>
    );

    return (
        <div>
            {sidebarIsOpen || Boolean(isOpen) ? (
                header
            ) : (
                <Tooltip title={name} place="right">
                    {header}
                </Tooltip>
            )}
            <Collapse in={Boolean(isOpen)} timeout="auto" unmountOnExit>
                <List
                    dense={dense}
                    component="div"
                    disablePadding
                    sx={{
                        "&": {
                            transition: "padding-left 195ms cubic-bezier(0.4, 0, 0.6, 1) 0ms",
                            paddingLeft: sidebarIsOpen ? 4 : 2,
                        },
                    }}
                >
                    {children}
                </List>
            </Collapse>
        </div>
    );
};


export default SubMenu;
