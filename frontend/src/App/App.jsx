import React from 'react'
import './App.css'
import Home from '../Home'
import PropTypes from 'prop-types'
import { FeaturesProvider } from '../FeatureToggle'
import { BrowserRouter, Route, Switch } from 'react-router-dom'
import NotFound from '../Error'
import Join from '../Join'

function App({ features }) {
  return (
    <FeaturesProvider features={features}>
      <BrowserRouter>
        <div className="app">
          <Switch>
            <Route exact path="/">
              <Home />
            </Route>
            {features.includes('JOIN_US') ? (
              <Route exact path="/join">
                <Join />
              </Route>
            ) : null}
            <Route exact path="*">
              <NotFound />
            </Route>
          </Switch>
        </div>
      </BrowserRouter>
    </FeaturesProvider>
  )
}

App.propTypes = {
  features: PropTypes.array.isRequired,
}

export default App
