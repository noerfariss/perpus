import axios from 'axios';
import React, { useEffect, useState } from 'react';

function Kontak() {
    const domain = document.head.querySelector('meta[name="base-url"]').content;
    const [alamat, setAlamat] = useState('');

    useEffect(() => {
        async function getData(){
            try {
                const res = await axios.get(`${domain}/api/sekolah`)
                const req = await res.data;
                const data = req.data;

                const dataAlamat = `${data.alamat} \n ${data.kecamatan.kecamatan} - ${data.kota.kota} \n ${data.provinsi.provinsi}`;
                setAlamat(dataAlamat);

            } catch (err) {
                console.log(err);
            }
        }
        getData();
    }, []);

    return (
        <div className='bg-dark-down text-light'>
            <div className='container py-5'>
                <h1 className='mb-5'>Kontak</h1>

                <div className='row'>
                    <div className='col-sm-4'>
                        <h4>Alamat</h4>
                        <p className='new-line'>{alamat}</p>
                    </div>
                </div>
            </div>
        </div>
    );
}

export default Kontak;
