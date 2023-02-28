import React, { useState } from 'react';

const Home = () => {
    const [angka, setAngka] = useState(0);
    const submit = () => {
        console.log(angka + 1);
    }

    return (
        <div>
            <h1>HALOOOSSS</h1>
            <input type="text" value={angka} />

            <button type='button' className='btn btn-primary mt-4' onClick={submit}>Klik</button>
        </div>
    );
};

export default Home;
