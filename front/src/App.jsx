import { useState } from 'react'
import reactLogo from './assets/react.svg'
import viteLogo from '/vite.svg'
import './App.css'
import { ShowTorneos} from './components/ShowTorneos.jsx'
import { CreateTorneos } from './components/CreateTorneos.jsx'

import {EditTorneos} from './components/EditTorneos.jsx'
import {BrowserRouter,Routes,Route} from 'react-router-dom'
function App() {
  

  return (
    <>
      <BrowserRouter>
        <Routes>
          <Route path='/' element={<ShowTorneos/>}/>
          <Route path='/create' element={<CreateTorneos/>} />
          <Route path='/edit/:id' element={ <EditTorneos/> }/>
        </Routes>
      </BrowserRouter>
    </>
  )
}

export default App
