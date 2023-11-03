import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { useNavigate, useParams } from 'react-router-dom';

const endpoint = 'http://localhost:8000/api/torneo/';

export const EditTorneos = () => {


  const [nombre, setNombre] = useState('');
  const [fecha, setFecha] = useState('');
  const navigate = useNavigate();
  const { id } = useParams(); // Obtén el valor de torneo_id desde la URL

  const update = async(e) =>{
    e.preventDefault()
    await axios.put(`${endpoint}${id}`,{
      nombre: nombre,
      fecha: fecha
    })
    navigate('/')
  }

useEffect ( ()=>{
  const getProductById= async ()=>{
    const response= await axios.get(`${endpoint}${id}`)
    setNombre(response.data.nombre)
    setFecha(response.data.fecha)
  }
  getProductById()
}, [])

  return (
    <>
    <h3>Edit Torneo </h3>
    <form  onSubmit={update}>
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
        <button type='submit' className='btn btn-primary'>Editar</button>
    </form>
    </>
  )
}
