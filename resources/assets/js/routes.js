
import Home from './components/manager/admin/DashboardComponent';
import Profile from './components/manager/admin/ProfileComponent';

export const routes = [
    {
        path: '/home',
        component: Home,
        name: 'Home'
    },
    {
        path: '/profile',
        component: Profile,
        name: 'Profile'
    }
];