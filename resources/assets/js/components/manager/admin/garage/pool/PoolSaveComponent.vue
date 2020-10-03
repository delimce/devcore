<template>
  <div class="field is-grouped">
    <div @mouseout="clearText()" class="control">
      <button
        :disabled="!enable"
        type="submit"
        @click="savePool()"
        class="button is-link"
      >
        {{ showSaveButtonText() }}
      </button>
    </div>
    <div class="mini">
      <pre-loader v-show="preloading"></pre-loader>
      <div v-show="!preloading" v-bind:class="[messageType]">{{ message }}</div>
    </div>
  </div>
</template>
<script>
import GarageMixin from "@/components/manager/mixins/GarageMixin.js";
export default {
  name: "poolSaveComponent",
  props: ["segments", "pool", "code", "enable", "garage"],
  mixins: [GarageMixin],
  data() {
    return {
      label_save_of: "Guardar servicios de ",
      label_error: "Error en precio de ",
    };
  },
  methods: {
    savePool: _.debounce(function () {
      this.preloading = true;
      let validate = this.validatePool();
      if (validate) {
        this.message = this.label_error + validate.name;
        this.messageType = "message-error";
        this.preloading = false;
      } else {
        // has no error
        this.savingPool();
      }
    }, 180),
    clearText() {
      setTimeout(() => (this.message = ""), 3100);
    },
    validatePool() {
      let services = this.pool.workforce

        .concat(this.pool.tyre)
        .concat(this.pool.oil)
        .concat(this.pool.filter)
        .concat(this.pool.brake)
        .concat(this.pool.battery)
        .concat(this.pool.ac)
        .concat(this.pool.check);

      let wrongRow = services
        .filter((el) => {
          return el.select;
        })
        .find((el) => {
          return el.price == 0;
        });

      if (!_.isUndefined(wrongRow)) {
        return wrongRow;
      } else {
        return false;
      }
    },
    savingPool() {
      //set on workforce
      this.pool.workforce[0].select = true;
      let url = "/manager/garage/services/pool";
      axios
        .post(url, {
          garage_id: this.garage.id,
          segment: this.code,
          pool: this.pool,
        })
        .then((response) => {
          let result = response.data.info;
          this.message = result.message;
          this.messageType = "message-ok";
          this.preloading = false;
        })
        .catch((error) => {
          this.messageType = "message-error";
          this.message = this.label_error_default;
          this.preloading = false;
        });
    },
    showSaveButtonText() {
      let desc = this.findInSegmentByCode(this.code);
      return this.label_save_of + desc;
    },
  },
  mounted: function () {},
};
</script>
<style scoped>
</style>