import React from 'react';
import { Link } from 'react-router-dom';

const Menu = () => {
    return (
        <>
            <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                <div className="container">
                    <a className="navbar-brand" href="#">Perpustakaan</a>

                    <button className="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span className="navbar-toggler-icon"></span>
                    </button>

                    <div className="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul className="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li className="nav-item">
                                <Link to="/" className="nav-link active">
                                    <span className='float-start me-1'>
                                        <box-icon name='home-alt-2' color='white' pull='right'></box-icon>
                                    </span>
                                    Home
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link to="/profil" className="nav-link active">
                                    <span className='float-start me-1'>
                                        <box-icon name='building-house' color='white'></box-icon>
                                    </span>
                                    Profil
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link to="/fitur" className="nav-link active">
                                    <span className='float-start me-1'>
                                    <box-icon name='send' color='white'></box-icon>
                                    </span>
                                    Fitur
                                </Link>
                            </li>
                            <li className="nav-item">
                                <Link to="/kontak" className="nav-link active">
                                    <span className='float-start me-1'>
                                        <box-icon name='phone' color='white'></box-icon>
                                    </span>
                                    Kontak
                                </Link>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
        </>
    );
};

export default Menu;
