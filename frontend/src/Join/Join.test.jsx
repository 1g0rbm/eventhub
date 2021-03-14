import React from 'react'
import { MemoryRouter } from 'react-router-dom'
import { render } from '@testing-library/react'
import Join from './Join'

test('renders join page', () => {
  const { getByText } = render(
    <MemoryRouter>
      <Join />
    </MemoryRouter>
  )

  expect(getByText(/Join us/i)).toBeInTheDocument()
})
