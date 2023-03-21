import React from 'react';
import { Link } from 'react-router-dom';

const Menu = () => {
    return (
        <>
        <nav className="navbar navbar-expand-lg navbar-light bg-primary mb-5">
            <div className="container">
                <a className="navbar-brand" href="#">Perpustakaan</a>
                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>
                <div className="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul className="navbar-nav me-auto mb-2 mb-lg-0">
                        <li className="nav-item">
                            <Link to="/" className="nav-link active">Home</Link>
                        </li>
                        <li className="nav-item">
                            <Link to="/about" className="nav-link active">About</Link>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        </>
    );
};

export default Menu;
