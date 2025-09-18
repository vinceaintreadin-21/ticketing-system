import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import Sample from './Sample'
import {BrowserRouter as Router, Route, Routes, Link} from 'react-router-dom'

function App() {
  return(
    <>
      <Router>
        <nav>
          <Link to='/sample-hello'>Sample</Link>
        </nav>

        <Routes>
          <Route path='/sample-hello' element={<Sample />}/>
        </Routes>
      </Router>
      
    </>
  )
}

export default App
