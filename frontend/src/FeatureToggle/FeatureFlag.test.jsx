import React from 'react'
import { render } from '@testing-library/react'
import FeaturesProvider from './FeaturesProvider'
import FeatureFlag from './FeatureFlag'

test('renders content if feature is active', () => {
  const features = ['FEATURE']

  const { container } = render(
    <FeaturesProvider features={features}>
      <FeatureFlag name="FEATURE">Content</FeatureFlag>
    </FeaturesProvider>
  )

  expect(container).toHaveTextContent('Content')
})

test('does not render content if feature is not active', () => {
  const { container } = render(
    <FeaturesProvider features={[]}>
      <FeatureFlag name="FEATURE">Content</FeatureFlag>
    </FeaturesProvider>
  )

  expect(container).not.toHaveTextContent(/Content/)
})

test('does not render content in not mode', () => {
  const features = ['FEATURE']

  const { container } = render(
    <FeaturesProvider features={features}>
      <FeatureFlag name="FEATURE" not>
        Content
      </FeatureFlag>
    </FeaturesProvider>
  )

  expect(container).not.toHaveTextContent(/Content/)
})
