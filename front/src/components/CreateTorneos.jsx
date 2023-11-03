import React from 'react'
import axios from 'axios'
import { useState } from 'react'
import { useNavigate } from 'react-router-dom'
const endpoint ='http://localhost:8000/api/torneo'
export const CreateTorneos = () => {
    const [nombre,setNombre]=useState('')
    const [fecha,setFecha]=useState('')
    const navigate= useNavigate()
const store = async(e) =>{
    e.preventDefault()
    await axios.post(endpoint,{nombre:nombre,fecha:fecha})
    navigate('/')
}
  return (
    <>
    <h3>Create Product </h3>
    <form  onSubmit={store}>
        <div className='mb-3'>
        <label className='form-label'>Nombre</label>
        <input 
            value={nombre}
            onChange={ (e)=>setNombre(e.target.value)}
            type='text'
            className='form-control'
            />
        </div>
        <div className='mb-3'>
        <label className='form-label'>Fecha</label>
        <input 
            value={fecha}
            onChange={ (e)=>setFecha(e.target.value)}
            type='date'
            className='form-control'
            />
        </div>
        <button type='submit' className='btn btn-primary'>Crear</button>
    </form>
    </>
  )
}
