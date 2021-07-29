import Vue from 'vue';
import VueRouter from 'vue-router';
import Login from './components/auth/Login'
import Welcome from './components/Welcome'
import Logout from './components/auth/Logout'
import Home from './pages/Home'
import Explore from './pages/Explore'
import Notifications from './components/Notifications'
import Groups from './pages/Groups'
import GroupProfile from './pages/GroupProfile'
import Level from './pages/Level'
import Settings from './pages/Settings'
import Messages from './pages/Messages'
import SideNavBar from './components/SideNavBar'
import Admin from './pages/Admin/Admin'
import Role from './pages/Admin/Role'
import Permission from './pages/Admin/Permission'
import Log from './pages/Admin/Log'
import User from './pages/Admin/User'
import UserProfile from './pages/UserProfile'
Vue.use(VueRouter);
const routes = [

    {
        path: '/',
        name: 'welcome',
        component: Welcome,
        props: true,
        meta: {
            requiresVisitor: true,
          }
    },

      {
          path:'/home',
          name: 'Home',
          component: Home,
          meta:{
              requiresAuth:true
                }
      },
      {
        path:'/sidenav',
        name: 'SideNavBar',
        component: SideNavBar,
    },
      {
          path:'/explore',
          name: 'Explore',
          component: Explore,
      },
      {
          path:'/notifications',
          name: 'Notifications',
          component: Notifications,
      },
      {
          path:'/groups',
          name: 'Groups',
          component: Groups,
          meta:{
            requiresAuth:true
              }
      },
      {
          path:'/group-profile',
          name: 'group-profile',
          component: GroupProfile,
          meta:{
            requiresAuth:true
              }
      },
      {
          path:'/messages',
          name: 'Messages',
          component: Messages,
      },
      {
          path:'/level',
          name: 'Level',
          component: Level,
      },
      {
          path:'/user/profile/',
          name: 'user-profile',
          component: UserProfile,
      },
      {
          path:'/settings',
          name: 'Settings',
          component: Settings,
      },
    {
        path:'/login',
        name:'Login',
        component: Login,

    },
    {
        path:'/logout',
        name:'Logout',
        component: Logout,

    },
    {
        path:'/user',
        name:'User',
        component: User,
        meta: {
            requiresAuth: true,
          }

    },
    {
        path:'/role',
        name:'Role',
        component: Role,
        meta: {
            requiresAuth: true,
          }

    },
    {
        path:'/log/:id',
        name:'Log',
        component: Log,
        meta: {
            requiresAuth: true,
          }

    }
]
export default routes
