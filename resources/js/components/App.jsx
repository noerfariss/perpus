import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import About from './Pages/About';
import Home from './Pages/Home';
import Menu from './Navigasi/Menu';

const App = () => {
    return (
        <BrowserRouter>
           <Menu></Menu>
           <Routes>
                <Route path='/' element={<Home></Home>}></Route>
                <Route path='/about' element={<About></About>}></Route>
           </Routes>
        </BrowserRouter>
    );
};

export default App;
