import React from 'react'
import PropTypes from 'prop-types'
import styles from './System.module.css'

function System({ children }) {
  return (
    <div className={styles.layout}>
      <div className={styles.content}>{children}</div>
    </div>
  )
}

System.propTypes = {
  children: PropTypes.any,
}

export default System
