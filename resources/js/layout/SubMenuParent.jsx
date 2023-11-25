import * as React from 'react';
import {
    List,
    MenuItem,
    ListItemIcon,
    Typography,
    Collapse,
    Tooltip,
} from '@mui/material';
import {useSidebarState} from 'react-admin';


const SubMenuParent = (props) => {
    const {handleToggle, isOpen, name, icon, children, dense, iconOpen} = props;
    const [sidebarIsOpen] = useSidebarState();
    const header = (
        <MenuItem dense={dense} onClick={handleToggle}>
            <ListItemIcon sx={{minWidth: 5}}>
                {isOpen ? iconOpen : icon}
            </ListItemIcon>
            <Typography variant="inherit" color="textSecondary" className="menuCollr">
                {name}
            </Typography>
        </MenuItem>
    );

    return (
        <div>
            {sidebarIsOpen || isOpen ? (
                header
            ) : (
                <Tooltip title={name} place="right">
                    {header}
                </Tooltip>
            )}
            <Collapse in={isOpen} timeout="auto" unmountOnExit>
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


export default SubMenuParent;
