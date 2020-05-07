_ = require('lodash');

/**
 * get user token from sessionStorage
 */
export const getUserToken = function () {

    let token = sessionStorage.userToken;
    if (token) {
        return token;
    }
    return false;
}

/**
 * persist user token
 * @param {string} token 
 */
export const saveUserToken = function (token) {
    sessionStorage.userToken = token;
    sessionStorage.updated = new Date().getTime();
}