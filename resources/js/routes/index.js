import { createWebHistory,  createRouter } from 'vue-router';
import Home from '../components/Home.vue';
import Register from '../components/auth/Register.vue';
import Login from '../components/auth/Login.vue';
import Post from '../components/posts/Post.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/register',
        name: 'Register',
        component: Register
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/post/:id',
        name: Post,
        component: Post
    }
]

const router = createRouter({
    routes: routes,
    mode: 'history',
    history: createWebHistory()
});

export default router;