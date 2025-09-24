// import './App.css'
import Sample from './Sample'
import {BrowserRouter as Router, Route, Routes, Link} from 'react-router-dom'
import SSOLogin from './guest/SSOLogin'
import SSOCallback from './guest/SSOCallback'
import Dashboard from './pages/Dashboard'
import TicketForm from './component/tickets/TicketForm'

function App() {
  return(
    <>
      <Router>
        <Routes>
            <Route path='/login' element={<SSOLogin/>}/>
            <Route path='/sso/callback' element={<SSOCallback/>}/>
            <Route path='/dashboard' element={<Dashboard/>}/>

            <Route path='/create-ticket' element={<TicketForm/>}/>

            <Route path='*' element={<SSOLogin />}/>
        </Routes>
      </Router>

    </>
  )
}

export default App
