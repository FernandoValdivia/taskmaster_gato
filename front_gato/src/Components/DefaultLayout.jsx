import { useEffect } from "react";
import { Navigate, Outlet } from "react-router-dom";
import axiosClient from "../axiosClient";
import { useStateContext } from "../contexts/contextprovider";
import { Link } from "react-router-dom";

export default function DefaultLayout() {
    const { user, token, setUser, setToken } = useStateContext();

    useEffect(() => {
        axiosClient.get('/user')
            .then(({ data }) => {
                setUser(data);
            });
    }, [setUser]);

    if (!token) {
        return <Navigate to='/login' />;
    }

    const onLogout = (ev) => {
        ev.preventDefault();
        axiosClient.get('/logout')
            .then(() => {
                setUser(null);
                setToken(null);
            });
    };

    return (
        <div id="defaultLayout">
            <div className="content">
                <header>
                    <Link to="/" title="Inicio TaskMaster">
                        <div className="logo_navbar">
                            <img src="https://res.cloudinary.com/lvaldivia/image/upload/v1742326639/gato/gato_logo.webp" alt="Logo Agencia GATO" />
                            <h1>TaskMaster</h1>
                        </div>
                    </Link>
                    <div className="welcome_tool">
                        <h3>Hola, <span>{user.name}</span>!</h3>
                        <Link to={`/tasks/${user.id}`} className="btn-logout" title="Tareas">
                            <img src="https://res.cloudinary.com/lvaldivia/image/upload/v1742326639/gato/hrnz3jtuba1jn0osjev9.webp" alt="Boton de tareas" />
                        </Link>
                        <Link to={"/users"} className="btn-logout" title="Usuarios">
                            <img src="https://res.cloudinary.com/lvaldivia/image/upload/v1742326639/gato/cat.webp" alt="Boton de usuarios" />
                        </Link>
                        <a href="#" onClick={onLogout} className="btn-logout" title="Cerrar sesión">
                            <img src="https://res.cloudinary.com/lvaldivia/image/upload/v1742326639/gato/logout.webp" alt="Boton de logout" />
                        </a>
                    </div>
                </header>
                <main>
                    <Outlet />
                </main>
            </div>
        </div>
    );
}