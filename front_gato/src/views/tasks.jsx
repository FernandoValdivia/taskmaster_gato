import { useEffect, useState } from "react";
import { Link, useParams } from "react-router-dom";
import axiosClient from "../axiosClient";

export default function Tasks() {
  const {user_id} = useParams();
  const [tasks, setTasks] = useState([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    getTasks();
  }, []);


  const onDeleteClick = (task) => {
    if (!window.confirm("¿Seguro que quieres eliminar esta tarea?")) {
      return;
    }
    axiosClient.delete(`/tasks/${task.id}`).then(() => {
      getTasks();
    });
  };

  const getTasks = () => {
    setLoading(true);
    const userId = user_id

    axiosClient
      .get(`/tasks`, {
        params: {
          user_id: userId,
        },
      })
      .then(({ data }) => {
        //console.log(data);
        setLoading(false);
        setTasks(data.data);
      })
      .catch((error) => {
        setLoading(false);
        console.error("Error fetching tasks:", error);
      });
  };

  if (!user_id) {
    console.error("User ID no encontrado en localStorage");
  }

  return (
    <div>
      <div style={{ display: "flex", justifyContent: "space-between", alignItems: "center" }}>
        <h1>Mis Tareas</h1>
        <Link className="btn-add" to={`/tasks/new/${user_id}`}>
          Nueva Tarea +
        </Link>
      </div>
      <div className="card animated fadeInDown">
        <table>
          <thead>
            <tr>
              <th>Título</th>
              <th>Descripción</th>
              <th>Estado</th>
              <th>Fecha Límite</th>
              <th>ID usuario</th>
              <th>Acciones</th>
            </tr>
          </thead>
          {loading && (
            <tbody>
              <tr>
                <td colSpan="5" className="text-center">
                  Cargando...
                </td>
              </tr>
            </tbody>
          )}
          {!loading && (
            <tbody>
              {tasks.map((task) => (
                <tr key={task.id}>
                  <td>{task.title}</td>
                  <td>{task.description || "Sin descripción"}</td>
                  <td>
                    <span className={`status-badge ${task.status}`}>{task.status}</span>
                  </td>
                  <td>{new Date(task.due_date).toLocaleDateString()}</td>
                  <td>{task.user_id}</td>
                  <td>
                    <Link className="btn-edit" to={`/tasks/edit/${task.id}`}>
                      Editar
                    </Link>
                    &nbsp;
                    <button className="btn-delete" onClick={() => onDeleteClick(task)}>
                      Eliminar
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          )}
        </table>
      </div>
    </div>
  );
}