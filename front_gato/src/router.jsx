import {createBrowserRouter} from 'react-router-dom';
import Login from './views/Login.jsx';
import Register from './views/Register.jsx';
import DefaultLayout from './Components/DefaultLayout.jsx';
import GuestLayout from './Components/GuestLayout.jsx';
import Users from './views/Users.jsx';
import UserForm from './views/UserForm.jsx';
import TaskForm from './views/TaskForm.jsx';
import Tasks from './views/Tasks.jsx';

const router = createBrowserRouter ([
    {
        path: '/',
        element: <DefaultLayout />,
        children: [
            {
                path: '/users',
                element: <Users />,
            },
            {
                path: '/users/new',
                element: <UserForm key="userCreate"/>
            },
            {
                path: '/users/:id',
                element: <UserForm key="userUpdate" />
            },
            {
                path: '/tasks/:user_id',
                element: <Tasks />,
            },
            {
                path: '/tasks/new/:user_id',
                element: <TaskForm key="taskCreate"/>
            },
            {
                path: '/tasks/edit/:id',
                element: <TaskForm key="taskUpdate" />
            },
        ]
    },

    {
        path: '/',
        element: <GuestLayout />,
        children: [
            {
                path: '/login',
                element: <Login />,
            },
            {
                path: '/register',
                element:  <Register />,
            }
        ]
    },
]);

export default router;