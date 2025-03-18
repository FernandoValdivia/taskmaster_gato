import { useEffect } from "react";
import { useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axiosClient from "../axiosClient";

export default function UserForm(){
    const {id} = useParams();
    const navigate = useNavigate();
    const [user, setUsers] = useState({
        id: null,
        name: '',
        email: '',
        password: ''
    });
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState(null);
    
    if(id)
    {
        useEffect(() => {
            setLoading(true)
            axiosClient.get(`/users/${id}`)
              .then(({data}) => {
                setLoading(false)
                setUsers(data)
              })
              .catch(() => {
                setLoading(false)
              })
          }, [id])
    }

    const onSubmit = ev => {
        ev.preventDefault()
        if (user.id) {
          axiosClient.put(`/users/${user.id}`, user)
            .then(() => {
              navigate('/users')
            })
            .catch(err => {
              const response = err.response;
              if (response && response.status === 422) {
                setErrors(response.data.errors)
              }
            })
        } else {
          axiosClient.post('/users', user)
            .then(() => {
              navigate('/users')
            })
            .catch(err => {
              const response = err.response;
              if (response && response.status === 422) {
                setErrors(response.data.errors)
              }
            })
        }
      }

    return(
    <>
      {user.id && <h1>Actualizar usuario: {user.name}</h1>}
      {!user.id && <h1>Nuevo usuario</h1>}
      <div className="card animated fadeInDown">
        {loading && (
          <div className="text-center">
            Cargando...
          </div>
        )}
        {errors &&
          <div className="alert">
            {Object.keys(errors).map(key => (
              <p key={key}>{errors[key][0]}</p>
            ))}
          </div>
        }
        {!loading && (
          <form onSubmit={onSubmit}>
            <input value={user.name} onChange={ev => setUsers({...user, name: ev.target.value})} placeholder="Nombre"/>
            <input value={user.email} onChange={ev => setUsers({...user, email: ev.target.value})} placeholder="Correo"/>
            <input type="password" onChange={ev => setUsers({...user, password: ev.target.value})} placeholder="Contraseña"/>
            <button className="btn">Guardar</button>
          </form>
        )}
      </div>
    </>
    )
}