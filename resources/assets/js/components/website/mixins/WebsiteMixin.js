const WebsiteMixin = {
    data() {
        return {
            label_info: "Información",
            label_services: "Servicios",
            label_welcome: "¿Buscas un taller de confianza?",
            loading: true,
            days: ["LUN", "MAR", "MIE", "JUE", "VIE", "SAB", "DOM"],
            segments: ["CAR", "VAN", "TRUCK", "SUV", "MOTORCYCLE"],
            types: []
        }
    },
    methods: {
        getNameOfDay(index) {
            return this.days[index];
        },
        waitToLoad() {
            setTimeout(
                function () {
                    this.loadData();
                }.bind(this),
                1000
            );
        },
    }
}
export default WebsiteMixin