import React from 'react'
import { render } from '@testing-library/react'
import Welcome from './Welcome'
import { FeaturesProvider } from '../FeatureToggle'

test('renders old welcome', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={[]}>
      <Welcome />
    </FeaturesProvider>
  )

  expect(getByText(/We will be here soon/i)).toBeInTheDocument()
  expect(queryByText(/We are here/i)).not.toBeInTheDocument()
})

test('renders new welcome', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={['WE_ARE_HERE']}>
      <Welcome />
    </FeaturesProvider>
  )

  expect(queryByText(/We will be here soon/i)).not.toBeInTheDocument()
  expect(getByText(/We are here/i)).toBeInTheDocument()
})
