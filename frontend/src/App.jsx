import './App.css'
import Sample from './Sample'
import {BrowserRouter as Router, Route, Routes, Link} from 'react-router-dom'
import SSOLogin from './guest/SSOLogin'
import SSOCallback from './guest/SSOCallback'
import Dashboard from './auth/Dashboard'

function App() {
  return(
    <>
      <Router>
        <Routes>
            <Route path='/login' element={<SSOLogin/>}/>
            <Route path='/sso/callback' element={<SSOCallback/>}/>
            <Route path='/dashboard' element={<Dashboard/>}/>

            <Route path='*' element={<SSOLogin />}/>
        </Routes>
      </Router>
      
    </>
  )
}

export default App
