import { useEffect } from "react";
import { useState } from "react"
import { Link } from "react-router-dom";
import axiosClient from "../axiosClient";

export default function users(){
  const [users, setUsers] = useState([]);
  const [loading, setLoading] = useState(false);
  
  useEffect(()=> {
    getUsers();
  }, [])

  const onDeleteClick = user => {
      if (!window.confirm("Seguro que quieres borrar al usuario?")) {
        return
      }
      axiosClient.delete(`/users/${user.id}`)
        .then(() => {
          getUsers()
        })
    }

  const getUsers = () => {
      setLoading(true)
      axiosClient.get('/users')
        .then(({ data }) => {
          setLoading(false)
          setUsers(data.data)
        })
        .catch(() => {
          setLoading(false)
        })
    }

    return(
        <div>
        <div style={{display: 'flex', justifyContent: "space-between", alignItems: "center"}}>
          <h1>Usuarios registrados</h1>
          <Link className="btn-add" to="/users/new">Nuevo +</Link>
        </div>
        <div className="card animated fadeInDown">
          <table>
            <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Correo</th>
              <th>Acciones</th>
            </tr>
            </thead>
            {loading &&
              <tbody>
              <tr>
                <td colSpan="5" className="text-center">
                  Cargando...
                </td>
              </tr>
              </tbody>
            }
            {!loading &&
              <tbody>
              {users.map(u => (
                <tr key={u.id}>
                  <td>{u.id}</td>
                  <td>{u.name}</td>
                  <td>{u.email}</td>
                  <td>
                    <Link className="btn-edit" to={'/users/' + u.id}>Editar</Link>
                    &nbsp;
                    <button className="btn-delete" onClick={() => onDeleteClick(u)}>Borrar</button>
                  </td>
                </tr>
              ))}
              </tbody>
            }
          </table>
        </div>
      </div>
    )
}