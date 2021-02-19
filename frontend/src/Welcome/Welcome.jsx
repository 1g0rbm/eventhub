import React from 'react'
import styles from './Welcome.module.css'
import FeatureFlag from '../FeatureToggle'

function Welcome() {
  return (
    <div data-test="welcome" className={styles.welcome}>
      <h1>Eventhub</h1>
      <FeatureFlag name="WE_ARE_HERE">
        <p>We are here</p>
      </FeatureFlag>

      <FeatureFlag name="WE_ARE_HERE" not>
        <p>We will be here soon</p>
      </FeatureFlag>
    </div>
  )
}

export default Welcome
