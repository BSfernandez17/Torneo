import axios from 'axios'
import { useState,useEffect } from 'react'
import { Link } from 'react-router-dom'
import 'bootstrap/dist/css/bootstrap.min.css';

const enpoint ='http://localhost:8000/api'
export const ShowTorneos = () => {
    const [torneo,setTorneo] = useState([])
    useEffect( ()=>{
        getAllTorneos()
    },[])
    const getAllTorneos= async ()=>{
        const response =await axios.get(`${enpoint}/torneo`)
        setTorneo(response.data)
    }
    const deleteTorneo= async(id) => {
        axios.delete(`${enpoint}/torneo/${id}`)
        getAllTorneos()
    }
  return (
    <>
        <div className='d-gri gap-2'>
            <Link to="/create" className='btn btn-success btn-lg mt-2 mb-2 text-white'>Create</Link>
        </div>
        <table className='table table-striped'>
            <thead className='bg-primary text-white'>
                <tr>
                    <th>Nombre</th>
                    <th>fecha</th>
                </tr>
            </thead>
            <tbody>
            {torneo.map((torneo) => (
                <tr key={torneo.id}>
                    <td>{torneo.nombre}</td>
                    <td>{torneo.fecha}</td>
                    <td>
                    <Link to={`/edit/${torneo.id}`} className='btn btn-warning'> Editar </Link>
                    <button className='btn btn-danger' onClick={() => deleteTorneo(torneo.id)}>Eliminar</button>
                    </td>
                </tr>
                ))}
            </tbody>
        </table>
    </>
  )
}
