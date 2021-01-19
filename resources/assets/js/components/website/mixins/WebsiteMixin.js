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
                { id: "TYRE", label: "Neumáticos" },
                { id: "OIL", label: "Lubricantes" },
                { id: "FILTER", label: "Filtros" },
                { id: "BRAKES", label: "Frenos" },
                { id: "BATTERY", label: "Baterias" },
                { id: "CHECK", label: "Mantenimiento" },
                { id: "AC", label: "A/C" },
                { id: "WORKFORCE", label: "Mano/Obra" },
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