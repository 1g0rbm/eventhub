import React from 'react'
import { render } from '@testing-library/react'
import Welcome from './Welcome'

test('renders app', () => {
  const { getByText } = render(<Welcome/>)
  const h1Element = getByText(/Eventhub/i)
  expect(h1Element).toBeInTheDocument()
})
