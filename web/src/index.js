import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';

const reactAppData = window.rpReactPlugin || {}
const { appSelector } = reactAppData
const appAnchorElement = document.querySelector(appSelector)

if (appAnchorElement) {
  ReactDOM.render(
    <React.StrictMode>
      <App />
    </React.StrictMode>,
    appAnchorElement
  )
}