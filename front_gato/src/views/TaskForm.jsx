import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import axiosClient from "../axiosClient";

export default function TaskForm() {
    const { id, user_id } = useParams();
    const navigate = useNavigate();
    const [task, setTask] = useState({
        id: null,
        title: '', 
        description: '',
        status: 'pending', 
        due_date: '',
    });
    const [loading, setLoading] = useState(false);
    const [errors, setErrors] = useState(null);

    useEffect(() => {
        if (id) {
            setLoading(true);
            axiosClient.get(`/tasks/${id}`)
                .then(({ data }) => {
                    setLoading(false);
                    console.log("Datos de la tarea recibidos:", data);
                    setTask({
                        id: data.data.id || null,
                        title: data.data.title || '',
                        description: data.data.description || '',
                        status: data.data.status || 'pending',
                        due_date: data.data.due_date || '',
                    });
                })
                .catch((error) => {
                    setLoading(false);
                    console.error("Error al cargar la tarea:", error); 
                });
        }
    }, [id]);

    const onSubmit = (ev) => {
        ev.preventDefault();
    
        if (task.id) {
            const taskData = { ...task }; 
    
            axiosClient.put(`/tasks/${task.id}`, taskData)
                .then(() => {
                    navigate(`/tasks/${user_id}`);
                })
                .catch((err) => {
                    const response = err.response;
                    if (response && response.status === 422) {
                        setErrors(response.data.errors); 
                    }
                });
        } else {
            const taskData = {
                ...task,
                user_id: parseInt(user_id, 10), 
            };
    
            axiosClient.post('/tasks', taskData)
                .then(() => {
                    navigate(`/tasks/${user_id}`);
                })
                .catch((err) => {
                    const response = err.response;
                    if (response && response.status === 422) {
                        setErrors(response.data.errors);
                    }
                });
        }
    };

    return (
        <>
            {task.id && <h1>Actualizar tarea: {task.title}</h1>}
            {!task.id && <h1>Nueva tarea</h1>}
            <div className="card animated fadeInDown">
                {loading && (
                    <div className="text-center">
                        Cargando...
                    </div>
                )}
                {errors &&
                    <div className="alert">
                        {Object.keys(errors).map((key) => (
                            <p key={key}>{errors[key][0]}</p>
                        ))}
                    </div>
                }
                {!loading && (
                    <form onSubmit={onSubmit}>
                        <input
                            value={task.title}
                            onChange={(ev) => setTask({ ...task, title: ev.target.value })}
                            placeholder="Título"
                        />
                        <input
                            value={task.description}
                            onChange={(ev) => setTask({ ...task, description: ev.target.value })}
                            placeholder="Descripción"
                        />
                        <div className="container__doble">
                            <select
                                value={task.status}
                                onChange={(ev) => setTask({ ...task, status: ev.target.value })}
                            >
                                <option value="pending">Pendiente</option>
                                <option value="in_progress">En progreso</option>
                                <option value="completed">Completada</option>
                            </select>
                            <input
                                type="date"
                                value={task.due_date}
                                onChange={(ev) => setTask({ ...task, due_date: ev.target.value })}
                                placeholder="Fecha límite"
                            />
                        </div>
                        <button className="btn">Guardar</button>
                    </form>
                )}
            </div>
        </>
    );
}