import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex)
axios.defaults.baseURL = '/api'

export const store = new Vuex.Store({
  state: {
    token: localStorage.getItem('access_token') || null,
    userName: localStorage.getItem('user_name') || null,
    userType: localStorage.getItem('user_type') || null,
    userAvatar: localStorage.getItem('avatar_image') || null,
    userId: localStorage.getItem('user_id') || null,
    users:[],
    authUser: localStorage.getItem('auth_user') || null,
    search:[],
    notificationCounter:0,
  },
  getters: {
    getNotification(state){
        return state.notificationCounter;
    },
    loggedIn(state) {
      return state.token !== null
    },
    getUserName(state){
      return state.userName
    },
    getUserType(state){
      return state.userType
    },
    getUserAvatar(state){
      return state.userAvatar
    },
    getUserId(state){
      return state.userId
    },
    getUsers(state){
        return state.users
    },
    getSearch(state){
      return state.search
    },
    getAuthUser(state){
        return state.authUser
    },
    getToken(state){
        return state.token
    }


  },
  mutations: {
    pushNotification(state,notificationCounter){
        state.notificationCounter = notificationCounter;
    },
    search(state,search){
      state.search=search
    },
    searchUser(state,users){
        state.users = users
    },

    retrieveUserName(state, userName) {
      state.userName = userName
    },
    retrieveAuthUser(state,authUser)
    {
        state.authUser = authUser;
    },
    retrieveToken(state, token) {
      state.token = token
    },
    retrieveUserType(state,userType){
      state.userType=userType
    },
    retrieveUserAvatar(state,userAvatar){
      state.userAvatar = userAvatar
    },
    retrieveUserId(state,userId){
      state.userId = userId
    },
    destroyToken(state) {
      state.token = null
    },
    destroyUserName(state) {
      state.userName = null
    },
    destroyUserType(state) {
      state.userType = null
    },
    destroyUserAvatar(state) {
      state.userAvatar = null
    },
    destroyUserId(state) {
      state.userId = null
    },
    destroyAuthUser(state){
        state.authUser = null
    }


  },
  actions: {
    pushNotification(context,notification){
        context.commit('pushNotification',notification);

    },
    search(context,search){
       axios.get("/findUser?q="+search.search)
       .then(response => {
      context.commit('search',response.data.data)
      });

    },
    searchUser(context,search){
        axios.defaults.headers.common['Authorization'] = 'Bearer ' +context.state.token
        axios.get('/findUser',{
            q:search.search
        })
        .then(response=>{
            context.commit('searchUser',response.data.data)
        })
    },
    retrieveUsers(context){
        axios.defaults.headers.common['Authorization'] = 'Bearer ' +context.state.token
        return new Promise((resolve, reject) => {
            axios.get('/users')
                 .then(response =>{
                     this.users = response.data
                 })
                 .catch(error => {
                     reject(error)
                 })
        })
    },
    retrieveName(context) {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token

      return new Promise((resolve, reject) => {
        axios.get('/user')
          .then(response => {
            localStorage.setItem('user_name', response.data.name)
            context.commit('retrieveUserName', response.data.name)
            localStorage.setItem('user_type',response.data.type)
            context.commit('retrieveUserType',response.data.type)
            localStorage.setItem('avatar_image', response.data.avatar_image)
            context.commit('retrieveUserAvatar',response.data.avatar_image)
            localStorage.setItem('user_id', response.data.id)
            context.commit('retrieveUserId', response.data.id)
            localStorage.setItem('auth_user',JSON.stringify(response.data))
            context.commit('retrieveAuthUser', response.data)
            resolve(response)
          })
          .catch(error => {
            reject(error)
          })
      })
    },

    destroyToken(context) {
      axios.defaults.headers.common['Authorization'] = 'Bearer ' + context.state.token

      if (context.getters.loggedIn) {
        return new Promise((resolve, reject) => {
          axios.post('/logout')
            .then(response => {
              localStorage.removeItem('access_token')
              localStorage.removeItem('user_name')
              localStorage.removeItem('user_type')
              localStorage.removeItem('avatar_image')
              localStorage.removeItem('user_id')
              localStorage.removeItem('auth_user')
              context.commit('destroyAuthUser')
              context.commit('destroyToken')
              context.commit('destroyUserName')
              context.commit('destroyUserType')
              context.commit('destroyUserAvatar')
              context.commit('destroyUserId')
              resolve(response)
              // console.log(response);
              // context.commit('addTodo', response.data)
            })
            .catch(error => {
              localStorage.removeItem('access_token')
              localStorage.removeItem('user_name')
              localStorage.removeItem('user_type')
              localStorage.removeItem('avatar_image')
              context.commit('destroyToken')
              context.commit('destroyUserName')
              context.commit('destroyUserType')
              context.commit('destroyUserAvatar')
              context.commit('destroyUserId')
              reject(error)
            })
        })
      }
    },
    retrieveToken(context, credentials) {

      return new Promise((resolve, reject) => {
        axios.post('/login', {
          username: credentials.username,
          password: credentials.password,
        })
          .then(response => {
            const token = response.data.access_token
            console.log(response);
            localStorage.setItem('access_token', token)
            context.commit('retrieveToken', token)
            resolve(response)
            // console.log(response);
            // context.commit('addTodo', response.data)
          })
          .catch(error => {
            console.log(error)
            reject(error)
          })
        })
    },


  }
})
