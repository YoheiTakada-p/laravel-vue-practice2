const state = {
  user: null,
  apiStatus: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}

const getters = {
  check: state => !!state.user,
  username: state => state.user ? state.user.name : '',
}

const mutations = {
  setUser: function (state, user) {
    state.user = user
  },
  setApiStatus: function (state, apiStatus) {
    state.apiStatus = apiStatus
  },
  setLoginErrorMessages: function (state, loginErrorMessages) {
    state.loginErrorMessages = loginErrorMessages
  },
  setRegisterErrorMessages: function (state, registerErrorMessages) {
    state.registerErrorMessages = registerErrorMessages
  }
}

const actions = {
  //会員登録
  async register(context, data) {

    context.commit('setApiStatus', null)

    const response = await axios.post('/api/register', data)
      .catch((error) => error.response)

    if (response.status === 201) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    context.commit('setApiStatus', false)

    if (response.status === 422) {
      context.commit('setRegisterErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }


  },
  //ログイン
  async login(context, data) {

    context.commit('setApiStatus', null)

    const response = await axios.post('/api/login', data)
      .catch((error) => error.response)

    if (response.status === 200) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
      return false
    }
    context.commit('setApiStatus', false)

    if (response.status === 422) {
      context.commit('setLoginErrorMessages', response.data.errors)
    } else {
      context.commit('error/setCode', response.status, { root: true })
    }
  },
  //ログアウト
  async logout(context) {

    context.commit('setApiStatus', null)
    const response = await axios.post('/api/logout')
      .catch((error) => error.response)

    if (response.status === 200) {
      context.commit('setApiStatus', true)
      context.commit('setUser', null)
      return false
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  },
  //ログインユーザーチェック
  async currentUser(context) {

    context.commit('setApiStatus', null)
    const response = await axios.get('/api/user')
      .catch((error) => error.response)

    if (response.status === 200) {
      context.commit('setApiStatus', true)
      const user = response.data || null
      context.commit('setUser', user)
    }

    context.commit('setApiStatus', false)
    context.commit('error/setCode', response.status, { root: true })
  }
}


export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}
