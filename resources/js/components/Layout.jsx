import React from 'react';
import { Link, Outlet } from 'react-router-dom';

const Layout = () => {
    return (
        <>
            <nav>
                <ul>
                    <li>
                        <Link to="/">Home</Link>
                    </li>
                    <li>
                        <Link to="/about">Blogs</Link>
                    </li>
                    <li>
                        <Link to="/contact">Contact</Link>
                    </li>
                </ul>
            </nav>

            <Outlet></Outlet>
        </>
    );
};

export default Layout;
