import supportEnum from "@/enums/supportProblems.json";
const ManagerMixin = {
    data() {
        return {
            preloading: false,
            messageType:"",
            message:"",
            label_save:"Guardar",
            supportTypes: supportEnum,
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