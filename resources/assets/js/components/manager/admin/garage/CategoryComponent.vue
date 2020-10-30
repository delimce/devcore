<template>
  <div>
    <div :id="mname"></div>
    <BulmaAccordion
      :dropdown="false"
      :icon="plus-minus"
      :slide="{
        duration: '.1s',
        timerFunc: 'ease'
    }"
    >
      <!-- The wrapper component for all the items -->
      <BulmaAccordionItem>
        <h4 slot="title">Just a title</h4>
        <div slot="content">
          <div v-for="service in services" :key="service.id" class="tyre-zone columns">
            <div class="column">
              <label class="label">{{service.name}}</label>
            </div>
            <div class="column">
              <money
                v-model="pool.workforce"
                v-bind="money"
                style="width: 20%;"
                class="input is-primary price-mini"
              ></money>
            </div>
          </div>
        </div>
      </BulmaAccordionItem>
      <!-- add as many of these items as you need - fill them with content via the slots -->
      <BulmaAccordionItem>
        <h4 slot="title">A title with content</h4>
        <div slot="content">
          <div v-for="service in services" :key="service.id" class="tyre-zone columns">
            <div class="column">
              <label class="label">{{service.name}}</label>
            </div>
            <div class="column">
              <money
                v-model="pool.workforce"
                v-bind="money"
                style="width: 20%;"
                class="input is-primary price-mini"
              ></money>
            </div>
          </div>
        </div>
      </BulmaAccordionItem>
         <!-- add as many of these items as you need - fill them with content via the slots -->
      <BulmaAccordionItem>
        <h4 slot="title">A title with content</h4>
        <div slot="content">
          <div v-for="service in services" :key="service.id" class="tyre-zone columns">
            <div class="column">
              <label class="label">{{service.name}}</label>
            </div>
            <div class="column">
              <money
                v-model="pool.workforce"
                v-bind="money"
                style="width: 20%;"
                class="input is-primary price-mini"
              ></money>
            </div>
          </div>
        </div>
      </BulmaAccordionItem>
    </BulmaAccordion>
  </div>
</template>

<script>
import typesEnum from "@/enums/serviceTypes.json";
import segmentEnum from "@/enums/segments.json";
import categoriesEnum from "@/enums/categories.json";
import { BulmaAccordion, BulmaAccordionItem } from "vue-bulma-accordion";
export default {
  name: "categoryComponent",
  props: ["services", "pool", "mname"],
  components: {
    BulmaAccordion,
    BulmaAccordionItem,
  },
  data() {
    return {
      types: typesEnum,
      money: {
        decimal: ",",
        thousands: ".",
        prefix: "",
        suffix: " €",
        precision: 2,
        masked: false,
      },
    };
  },
  methods: {
    open_second: function () {
      this.$refs.my_group.elements[1].open(); // opens second element
    },
  },
  mounted: function () {},
};
</script>

<style scoped>
h4 {
  cursor: pointer;
  width: 90%;
}
</style>