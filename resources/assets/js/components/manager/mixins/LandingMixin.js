import { deleteUserData, redirectToManager, saveUserToken, redirectToAdmin } from "@/functions";
const LandingMixing = {
    data() {
        return {

        }
    },
    methods: {
        doLogout() {
            deleteUserData();
            redirectToManager();
        },
        saveToken(token) {
            saveUserToken(token)
        },
        goToAdmin() {
            redirectToAdmin()
        }
    }
}
export default LandingMixing