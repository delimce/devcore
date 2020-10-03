import typesEnum from "@/enums/serviceTypes.json";
const GarageMixin = {
    data() {
        return {
            preloading: false,
            message: "",
            messageType: "",
            types: typesEnum,
            label_error_default: "Ha ocurrido un error",
            money: {
                decimal: ",",
                thousands: ".",
                prefix: "",
                suffix: " €",
                precision: 2,
                masked: false,
            }
        }
    },
    methods: {
        findInSegmentByCode(code) {
            let segment = _.find(this.segments, function (o) {
                return o.id === code;
            });
            return !_.isUndefined(segment) ? segment.desc : "";
        },
        setUrlBrands() {
            let base = "/manager/garage/services/brands/?";
            return base;
        },
        getSegments() {
            axios
                .get("/manager/garage/segments")
                .then((response) => {
                    this.segments = response.data.info.list;
                })
                .catch((error) => { });
        },
        getBrands() {
            let url = this.setUrlBrands();
            axios
                .get(url)
                .then((response) => {
                    this.brands = _.map(response.data.info.list, (el) => {
                        return {
                            id: el.id,
                            desc: el.name,
                            type: el.type,
                            category: el.category,
                        };
                    });
                })
                .catch((error) => { });
        },
        resetPrice(service) {
            if (!service.select) {
                service.price = 0.0;
            }
        },
        getPoolById(id) {
            return this.pool.find(el => {
                return el.id == id
            })
        },
        poolNameList() {
            let list = this.pool.map(el => {
                return {
                    "id": el.id,
                    "desc": el.name
                }
            })
            return _.uniqBy(list, 'id');
        }
    }
}
export default GarageMixin