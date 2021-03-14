import React from 'react'
import { render } from '@testing-library/react'
import Home from './Home'
import { FeaturesProvider } from '../FeatureToggle'
import { MemoryRouter } from 'react-router-dom'

test('renders old welcome', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={[]}>
      <MemoryRouter>
        <Home />
      </MemoryRouter>
    </FeaturesProvider>
  )

  expect(getByText(/We will be here soon/i)).toBeInTheDocument()
  expect(queryByText(/We are here/i)).not.toBeInTheDocument()
})

test('renders new welcome', () => {
  const { getByText, queryByText } = render(
    <FeaturesProvider features={['JOIN_US']}>
      <MemoryRouter>
        <Home />
      </MemoryRouter>
    </FeaturesProvider>
  )

  expect(queryByText(/We will be here soon/i)).not.toBeInTheDocument()
  expect(getByText(/We are here/i)).toBeInTheDocument()
})
