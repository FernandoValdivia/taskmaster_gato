import { useRef } from "react";
import { Link } from "react-router-dom";
import axiosClient from "../axiosClient";
import { useStateContext } from "../contexts/contextprovider";

export default function Register(){

    const nameRef = useRef();
    const emailRef = useRef();
    const passwordRef = useRef();

    const {setUser, setToken} = useStateContext();

    const Submit =  (ev) =>{
        ev.preventDefault();
        const payload = {
            name: nameRef.current.value,
            email: emailRef.current.value,
            password: passwordRef.current.value,
        }
        axiosClient.post("/register",payload).then(({data})=>{
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
                    Registro
                </h1>
                <form onSubmit={Submit}>
                    <input ref={nameRef} type="name" placeholder="Nombre" />
                    <input ref={emailRef} type="email" placeholder="Correo" />
                    <input ref={passwordRef} type="password" placeholder="Contraseña" />
                    <button className="btn btn-block">Registrarme</button>
                    <p className="message">
                        ¿Ya tienes una cuenta? <Link to= '/login'>Ingresa</Link>
                    </p>
                </form>
            </div>
        </div>
    )
}