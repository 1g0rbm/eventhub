import React from 'react'
import ReactDOM from 'react-dom'
import './index.css'
import App from './App/App'
import cookie from 'cookie'
import mergeFeatures from './FeatureToggle/megeFeatures'
import defaultFeatures from './features'

const cookies = cookie.parse(document.cookie)
const cookieFeatures = (cookies.features || '')
  .split(/\s*,\s*/g)
  .filter(Boolean)

const features = mergeFeatures(defaultFeatures, cookieFeatures)

ReactDOM.render(
  <React.StrictMode>
    <App features={features} />
  </React.StrictMode>,
  document.getElementById('root')
)
