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

export const deleteUserData = function () {
    sessionStorage.clear();
}

export const redirectToAdmin = function () {
    let url = api_url + "/admin/home";
    window.open(url, "_self");
}

export const redirectToManager = function () {
    let url = api_url + "/manager";
    window.open(url, "_self");
}

export const gotoSection = function (sectionId) {
    let url = location.href;
    location.href = "#" + sectionId;
    history.replaceState(null, null, url);
}