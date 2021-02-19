import React from 'react'
import { FeaturesContext } from '../FeatureToggle'
import PropTypes from 'prop-types'

function FeaturesProvider({ features, children }) {
  return (
    <FeaturesContext.Provider value={features}>
      {children}
    </FeaturesContext.Provider>
  )
}

FeaturesProvider.propTypes = {
  features: PropTypes.array.isRequired,
  children: PropTypes.object,
}

export default FeaturesProvider
