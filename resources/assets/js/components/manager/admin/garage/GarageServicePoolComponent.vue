<template>
  <div>
    <div class="card">
      <div class="card-content">
        <div class="columns">
          <div class="column is-4">
            <div class="field">
              <label class="label">{{ label_segment }}</label>
              <div class="control">
                <simple-select-component
                  :list="segments"
                  v-model="segment"
                ></simple-select-component>
              </div>
            </div>
          </div>
          <div class="column is-5">
            <span class="black-ad">
              Todos los precios a cumplimentar deberán incluir los impuestos
              (Ecotasa, IVA, etc.)
            </span>
          </div>
        </div>

        <BulmaAccordion
          :dropdown="false"
          :slide="{
            duration: '.1s',
            timerFunc: 'ease',
          }"
        >
          <BulmaAccordionItem class="zone1">
            <h4 slot="title">{{ label_work }} {{ segment_desc }}</h4>
            <div slot="content">
              <div class="columns">
                <div class="column is-4">Precio hora mano de obra.</div>
                <div class="column is-2">
                  <money
                    v-model="pool.workforce[0].price"
                    v-bind="money"
                    class="input is-primary price-mini"
                  ></money>
                </div>
              </div>
            </div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone2">
            <h4 slot="title">{{ label_tyre }}</h4>
            <div v-if="isEnable()" slot="content">
              <pool-tyre-component
                :categories="categories"
                :brands="
                  brands.filter((item) => {
                    return item.type == types.tyre.code;
                  })
                "
                :segment="segment"
                :pool="pool.tyre"
              ></pool-tyre-component>

              <pool-tyre-component :pool="pool.tyre"></pool-tyre-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone1">
            <h4 slot="title">Lubricantes</h4>
            <div v-if="isEnable()" slot="content">
              <pool-oil-component
                :brands="
                  brands.filter((item) => {
                    return item.type == types.oil.code;
                  })
                "
                :segment="segment"
                :pool="pool.oil"
              ></pool-oil-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone2">
            <h4 slot="title">Filtros</h4>
            <div v-if="isEnable()" slot="content">
              <pool-battery-component
                :brands="
                  brands.filter((item) => {
                    return item.type == types.filter.code;
                  })
                "
                :pool="pool.filter"
              ></pool-battery-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone1">
            <h4 slot="title">Frenos</h4>
            <div v-if="isEnable()" slot="content">
              <pool-brake-component
                :brands="
                  brands.filter((item) => {
                    return item.type == types.brake.code;
                  })
                "
                :pool="pool.brake"
              ></pool-brake-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone2">
            <h4 slot="title">Baterias</h4>
            <div v-if="isEnable()" slot="content">
              <pool-battery-component
                :brands="
                  brands.filter((item) => {
                    return item.type == types.battery.code;
                  })
                "
                :pool="pool.battery"
              ></pool-battery-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone1">
            <h4 slot="title">Aire Acondicionado</h4>
            <div v-if="isEnable()" slot="content">
              <pool-ac-component :pool="pool.ac"> </pool-ac-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
          <BulmaAccordionItem class="zone2">
            <h4 slot="title">Revisiones</h4>
            <div v-if="isEnable()" slot="content">
              <pool-check-component :pool="pool.check"> </pool-check-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
           <BulmaAccordionItem class="zone1">
            <h4 slot="title">Otros servicios</h4>
            <div v-if="isEnable()" slot="content">
              <pool-other-component :pool="pool.other"> </pool-other-component>
            </div>
            <div v-else slot="content">{{ label_disable }}</div>
          </BulmaAccordionItem>
        </BulmaAccordion>
        <pool-save-component
          :segments="segments"
          :code="segment"
          :pool="pool"
          :garage="garage"
          :enable="isEnable()"
        ></pool-save-component>
      </div>
    </div>
  </div>
</template>

<script>
import _ from "lodash";
import EventBus from "@/bus";
import segmentEnum from "@/enums/segments.json";
import categoriesEnum from "@/enums/categories.json";
import GarageMixin from "@/components/manager/mixins/GarageMixin.js";
import { BulmaAccordion, BulmaAccordionItem } from "vue-bulma-accordion";
import money from "v-money";
export default {
  name: "garageServicePool",
  props: ["garage"],
  mixins: [GarageMixin],
  components: {
    BulmaAccordion,
    BulmaAccordionItem,
  },
  data() {
    return {
      messageType: "",
      preloading: false,
      message: "",
      label_segment: "Segmento de vehículo:",
      label_work: "Mano de obra",
      label_tyre: "Neumáticos",
      label_disable:
        "Debe llenar el precio de la mano de obra para editar esta sección",
      segment: null,
      segment_desc: null,
      categories: [],
      segments: [],
      brands: [],
      services: [],
      garageServices: [],
      pool: {
        workforce: [{ price: 0.0 }],
      },
    };
  },
  methods: {
    isEnable() {
      return this.pool.workforce[0].price > 0.5;
    },
    getPoolServices() {
      let url =
        "/manager/garage/services/pool/" + this.garage.id + "/" + this.segment;
      axios
        .get(url)
        .then((response) => {
          this.pool = response.data.info;
        })
        .catch((error) => {});
    },
    getServiceCategories() {
      axios
        .get("/manager/garage/services/categories")
        .then((response) => {
          this.categories = response.data.info.list;
        })
        .catch((error) => {});
    },
  },
  watch: {
    segment: function () {
      this.getPoolServices();
      if (this.segment != null) {
        this.segment_desc = this.findInSegmentByCode(this.segment);
      }
    },
  },

  created: function () {
    this.getSegments();
    this.getBrands();
    this.segment = segmentEnum.car.code;
    this.getServiceCategories();
  },
  mounted: function () {
    setTimeout(
      function () {
        this.segment_desc = this.findInSegmentByCode(this.segment);
      }.bind(this),
      400
    );
  },
};
</script>

<style scoped>
.is-1 {
  width: 3%;
}

.black-ad {
  color: black;
  margin-top: 20px;
  font-weight: bold;
  display: block;
}

.zone1 {
  background-color: snow;
}

.zone2 {
  background-color: mintcream;
}

.card-content .columns {
  margin-left: 14px;
}

h4 {
  font-weight: bold;
  cursor: pointer;
  width: 90%;
  color: steelblue;
}
</style>