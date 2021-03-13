import React from 'react'
import FeatureFlag from '../FeatureToggle'
import System from '../Layout/System'

function Home() {
  return (
    <System>
      <h1>Eventhub</h1>
      <FeatureFlag name="WE_ARE_HERE">
        <p>We are here</p>
      </FeatureFlag>

      <FeatureFlag name="WE_ARE_HERE" not>
        <p>We will be here soon</p>
      </FeatureFlag>
    </System>
  )
}

export default Home
