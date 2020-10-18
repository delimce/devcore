const ManagerMixin = {
    data() {
        return {
            loading: true,
        }
    },
    methods: {
        async getManager() {
            let response = await axios.get("/manager/auth/");
            let manager = response.data.info.user;
            this.loading = false;
            return manager;
        }
    }
}
export default ManagerMixin