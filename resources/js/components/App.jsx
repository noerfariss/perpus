import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import About from './Pages/About';
import Home from './Pages/Home';
import Menu from './Navigasi/Menu';
import Fitur from './Pages/Fitur';
import Kontak from './Pages/Kontak';

const App = () => {
    return (
        <BrowserRouter>
           <Menu></Menu>
           <Routes>
                <Route path='/' element={<Home></Home>}></Route>
                <Route path='/profil' element={<About></About>}></Route>
                <Route path='/fitur' element={<Fitur></Fitur>}></Route>
                <Route path='/kontak' element={<Kontak></Kontak>}></Route>
           </Routes>
        </BrowserRouter>
    );
};

export default App;
