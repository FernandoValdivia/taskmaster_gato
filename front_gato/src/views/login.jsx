import { useRef } from "react";
import { Link } from "react-router-dom";
import axiosClient from "../axiosClient";
import { useStateContext } from "../contexts/contextprovider";

export default function Login(){

    const emailRef = useRef();
    const passwordRef = useRef();

    const {setUser, setToken} = useStateContext();

    const Submit =  (ev) =>{
        ev.preventDefault();
        const payload = {
            email: emailRef.current.value,
            password: passwordRef.current.value,
        }
        axiosClient.post("/login",payload).then(({data})=>{
            setUser(data.user);
            setToken(data.token);
    }).catch(err => {
        const response = err.response;
        if(response && response.status === 422){
            console.log(response.data.errors);
        }
    });
    }

    return(
        <div className="login-signup-form animated fadeinDown">
            <div className="form">
                <h1 className="title">
                    Inicio de sesión
                </h1>
                <form onSubmit={Submit}>
                <input ref={emailRef} type="email" placeholder="Correo" />
                    <input ref={passwordRef} type="password" placeholder="Contraseña" />
                    <button className="btn btn-block">Ingresar</button>
                    <p className="message">
                        ¿No tiene cuenta? <Link to= '/register'>Regístrate</Link>
                    </p>
                </form>
            </div>
        </div>
    )
}