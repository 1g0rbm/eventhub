import React from 'react'
import System from '../Layout/System'
import { Link } from 'react-router-dom'
import JoinForm from './JoinForm'

function Join() {
  return (
    <System>
      <h1>Join us</h1>
      <JoinForm />
      <p>
        <Link to="/">Back Home</Link>
      </p>
    </System>
  )
}

export default Join
