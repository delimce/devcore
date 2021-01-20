const WebsiteMixin = {
    data() {
        return {
            label_info: "Información",
            label_services: "Servicios",
            label_welcome: "¿Buscas un taller de confianza?",
            mediaPath: api_url + "/storage/media/",
            baseDetailPath: "garages/",
            nofoundImage: "https://bulma.io/images/placeholders/640x320.png",
            loading: true,
            days: ["LUN", "MAR", "MIE", "JUE", "VIE", "SAB", "DOM"],
            segments: [
                { id: "CAR", label: "turismo" },
                { id: "VAN", label: "Furgoneta" },
                { id: "TRUCK", label: "Camión" },
                { id: "SUV", label: "4X4" },
                { id: "MOTORCYCLE", label: "Moto" }
            ],
            types: [
                { id: "WORKFORCE", label: "Mano/Obra", order: 1 },
                { id: "TYRE", label: "Neumáticos", order: 2 },
                { id: "OIL", label: "Lubricantes", order: 4 },
                { id: "FILTER", label: "Filtros", order: 3 },
                { id: "BRAKES", label: "Frenos", order: 5 },
                { id: "BATTERY", label: "Baterias", order: 6 },
                { id: "CHECK", label: "Mantenimiento", order: 7 },
                { id: "AC", label: "A/C", order: 8 },

            ]
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
                    this.loading = false;
                }.bind(this),
                500
            );
        },
        currencyOf(mount) {
            return (mount).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '&,').concat(" €");
        },
        setAltImg(event) {
            event.target.src = this.nofoundImage
        }
    }
}
export default WebsiteMixin