<img src="https://res.cloudinary.com/lvaldivia/image/upload/v1742326639/gato/gato_logo.webp">

## Como inicializar el proyecto
1.	Clonar el repositorio https://github.com/FernandoValdivia/taskmaster_gato.git
2.	Ejecutar los comandos:
- cd taskmaster_gato
- composer update
3.	Crear la base de datos 
- DB_DATABASE=bdtaskmaster
- Tanto en MySQL o el gestor de bases de datos que se le acomode y configuar el .env con el mismo nombre creado.
4.	Posterior a ello, ejecutar:
- php artisan migrate
- Para poder crear las tablas del proyecto
- php artisan serve
- Para iniciar el proyecto Backend.
5.	En una consola aparte, ejecutar:
- cd front_gato
- Para ingresar al proyecto Frontend y correr el comando
- npm i
- npm run dev
- Para iniciar el proyecto Frontend.
6.	Con ello se tendrá el backend con la base de datos y el frontend listos para ser usados en sus rutas, tales como:
- Frontend:
- http://localhost:5173/login
- Backend:
- http://localhost:8000/
- Para ver los endpoints, ir a la dirección de Swagger
- http://localhost:8000/api/documentation
