import React from 'react'
import FeatureFlag from '../FeatureToggle'
import System from '../Layout/System'
import { Link } from 'react-router-dom'

function Home() {
  return (
    <System>
      <h1>Eventhub</h1>

      <FeatureFlag name="JOIN_US">
        <p>We are here</p>
        <p>
          <Link to="/join" data-test="join-link">
            Join us
          </Link>
        </p>
      </FeatureFlag>

      <FeatureFlag name="JOIN_US" not>
        <p>We will be here soon</p>
      </FeatureFlag>
    </System>
  )
}

export default Home
