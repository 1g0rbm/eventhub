import React from 'react'
import { MemoryRouter } from 'react-router-dom'
import { render } from '@testing-library/react'
import NotFound from './NotFound'

test('render not found page', () => {
  const { getByText } = render(
    <MemoryRouter>
      <NotFound />
    </MemoryRouter>
  )

  expect(getByText(/Page is not found/i)).toBeInTheDocument()
})
