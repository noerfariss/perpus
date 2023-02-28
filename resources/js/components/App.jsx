import React, { useEffect, useState } from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import About from './About';
import Home from './Home';
import Layout from './Layout';
import notFound from './notFound';

const App = () => {
    const [judul, setJudul] = useState('awal');

    const tekan = () => {
        setJudul('okkk');
    }

    return (
        <BrowserRouter>
            <Routes>
                <Route path="/" element={<Layout></Layout>}>
                    <Route index element={<Home></Home>}></Route>
                    <Route path='/about' element={<About></About>}></Route>
                    <Route path='*' element={<notFound></notFound>}></Route>
                </Route>
            </Routes>
        </BrowserRouter>
    );
};

export default App;
