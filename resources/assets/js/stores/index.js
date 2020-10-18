import Vue from 'vue'
import Vuex from 'vuex'
import managerStore from './manager'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        storeText: "this is a test",
    },
    getters: {
        'test': state => state.storeText
    },
    actions: {

    },
    mutations: {

    },
    modules: {
        manager: managerStore
    }
})