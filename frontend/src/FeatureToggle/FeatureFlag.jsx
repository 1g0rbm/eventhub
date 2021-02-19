import { useContext } from 'react'
import FeaturesContext from './FeaturesContext'
import PropType from 'prop-types'

function FeatureFlag({ name, not = false, children }) {
  const features = useContext(FeaturesContext)
  const isActive = features.includes(name)
  return (not ? !isActive : isActive) ? children : null
}

FeatureFlag.propTypes = {
  name: PropType.string.isRequired,
  not: PropType.bool,
}

export default FeatureFlag
