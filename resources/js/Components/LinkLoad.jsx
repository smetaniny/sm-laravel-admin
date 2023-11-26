import React from 'react';
import {Link} from "@inertiajs/inertia-react";
import Loader from "./Loader";

const LinkLoad = (
    {
        method = 'get',
        as = 'a',
        hrefRoute = null,
        hrefUrl = null,
        active = false,
        className = '',
        children,
        onMouseOver,
        dataSrc,
        onClick = () => {
        },
    }
) => {
    console.log('hrefRoute', hrefRoute);
    const [loading, setLoading] = React.useState(false);
    const handleClick = () => {
        setLoading(true);
        onClick();
    };
    const handleLoad = () => {
        setLoading(false);
    };
    React.useEffect(() => {
        window.addEventListener('load', handleLoad);
        return () => {
            window.removeEventListener('load', handleLoad);
        };
    }, []);

    try {
        const href = hrefRoute ? `${route(hrefRoute)}` : hrefUrl;

        return (
            <>
                {loading ? <Loader /> : null}
                <Link
                    method={method}
                    as={as}
                    href={href}
                    onClick={handleClick}
                    className={`${className} ${active ? 'active' : ''}`}
                    onMouseOver={onMouseOver}
                    data-src={dataSrc}
                    target="_blank"
                >
                    {children}
                </Link>
            </>
        );
    } catch (e) {
        // Обработка исключения, если маршрут не существует
        console.error(e);
        return null;
    }
};


export default LinkLoad;
