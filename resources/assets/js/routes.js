
import Home from './components/manager/admin/DashboardComponent';
import Profile from './components/manager/admin/ProfileComponent';
import Support from './components/manager/admin/SupportComponent';
import Garage from './components/manager/admin/GarageComponent';
import Config from './components/manager/admin/ConfigComponent';
import agenda from './components/manager/admin/AppointmentComponent';

export const routes = [
    {
        path: '*',
        component: Home,
        name: 'Home'
    },
    {
        path: '/profile',
        component: Profile,
        name: 'Profile'
    },
    {
        path: '/garage',
        component: Garage,
        name: 'Garage'
    },
    {
        path: '/appointments',
        component: agenda,
        name: 'Agenda'
    },
    {
        path: '/config',
        component: Config,
        name: 'Config'
    },
    {
        path: '/support',
        component: Support,
        name: 'Support'
    }
];