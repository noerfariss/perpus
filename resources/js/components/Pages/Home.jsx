import axios from 'axios';
import React, { useEffect, useState } from 'react';
import fileDownload from 'js-file-download';

const Home = () => {

    const domain = document.head.querySelector('meta[name="base-url"]').content;
    const imgURL = `${domain}/storage/foto/header.png`

    const [sekolah, setSekolah] = useState('');

    useEffect(() => {
        async function getData(){
            try {
                const res = await axios.get(`${domain}/api/sekolah`);
                const data = res.data.data;

                setSekolah(data.nama);

            } catch (error) {
                console.log(error);
            }
        }
        getData();
    }, []);

    const downloadFile = () => {
        // alert('File APK belum tersedia');
        axios({
            method : 'GET',
            url : `${domain}/storage/file/demo.apk`,
            responseType : 'blob'
        })
        .then((res) => {
            fileDownload(res.data, 'demo.apk');
        });
    }

    return (
        <div className='bg-dark-down text-light'>
            <div className='container py-5'>
                <div className='row'>
                    <div className='col-sm-6'>
                        <h3>Aplikasi perpustakaan</h3>
                        <h1>{sekolah}</h1>
                        <p className='mt-3'>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                            Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
                        <button type='button' className='btn btn-large btn-warning mt-5' onClick={downloadFile}>
                            <span className='float-start'>
                                <box-icon name='down-arrow-alt' className='text-light'></box-icon>
                            </span>
                            Donwload APK
                        </button>
                    </div>
                    <div className='col-sm-6'>
                        <img src={imgURL} className="img-fluid" />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Home;
