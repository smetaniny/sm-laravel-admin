import React from 'react';
import { createRoot } from 'react-dom/client';
import SMAdmin from './SMAdmin';

const root = createRoot(document.getElementById('app'));

root.render(<React.StrictMode><SMAdmin /></React.StrictMode>);
