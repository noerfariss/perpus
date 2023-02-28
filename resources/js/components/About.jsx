import React, { useEffect } from 'react';

const About = () => {
    useEffect(() => {
        console.log('load About');
    }, []);

    return (
        <div>
           About us
        </div>
    );
};

export default About;
