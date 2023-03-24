import './bootstrap';
import React from 'react';
import ReactDOM from 'react-dom/client';
import 'boxicons';

import App from './components/App';

const root = ReactDOM.createRoot(document.getElementById("app"));
root.render(
  <React.StrictMode>
    <App />
  </React.StrictMode>
);
