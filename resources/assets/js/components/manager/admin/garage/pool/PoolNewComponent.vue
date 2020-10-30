<template>
  <div>
    <!-- create new service -->
    <div class="rows" v-show="createNew">
      <div class="row is-full">
        <p class="title">{{ label_new_title }}</p>

        <div class="field has-addons">
          <div class="control">
            <simple-select-component
              v-model="newPool.id"
              :list="poolNameList()"
              select="Producto"
            ></simple-select-component>
          </div>

          <div v-show="categories" class="control">
            <simple-select-component
              :list="categories"
              v-model="categorySelected"
              select="Categoria"
            ></simple-select-component>
          </div>

          <div v-show="brands" class="control max-select">
            <simple-select-component
              :list="brandFiltered"
              v-model="newPool.brand"
              select="Marca"
            ></simple-select-component>
          </div>

          <div class="control">
            <money
              v-model="newPool.price"
              v-bind="money"
              class="input is-primary save-new"
            >
            </money>
          </div>

          <div class="control">
            <button
              :disabled="!(Number(newPool.id) > 0 && newPool.price > 0)"
              @click="saveNew()"
              class="button is-link"
            >
              {{ label_save }}
            </button>
          </div>
        </div>
      </div>
      <div class="row is-full">
        <div class="field is-grouped">
          <div class="mini">
            <div v-bind:class="[messageType]">
              {{ message }}
            </div>
          </div>
        </div>
      </div>
    </div>

    <hr />
    <div class="add-item">
      <a @click="showNew()">{{ label_new }}</a>
    </div>
  </div>
</template>
<script>
import money from "v-money";
import GarageMixin from "@/components/manager/mixins/GarageMixin.js";
import EventBus from "@/bus";
export default {
  name: "poolNewComponent",
  props: {
    pool: {
      type: Array,
      default: [],
    },
    brands: {
      type: Array,
      default: null,
    },
    categories: {
      type: Array,
      default: null,
    },
    segment: {
      type: String,
      default: null,
    },
  },
  mixins: [GarageMixin],
  data() {
    return {
      categorySelected: null,
      brandFiltered: [],
      label_new_title: "Añadir nuevo Product/servicio",
      label_save: "Guardar",
      label_add: "[Añadir nuevo +]",
      label_hide: "[Ocultar -]",
      label_new: "",
      label_error: "EL servicio ya se encuentra en el panel.",
      createNew: false,
      newPool: {},
    };
  },
  methods: {
    poolNameList() {
      let list = this.pool
        .filter((el) => {
          return el.segment == this.segment;
        })
        .map((el) => {
          return {
            id: el.id,
            desc: el.name,
          };
        });
      return _.uniqBy(list, "id");
    },
    showNew() {
      this.newPool = this.setNewPool();
      this.message = "";
      this.createNew = !this.createNew;
      this.label_new = !this.createNew ? this.label_add : this.label_hide;
    },
    setNewPool() {
      return {
        select: true,
        name: "",
        id: null,
        category: "",
        brand: "",
        price: 0.0,
        segment: this.segment,
      };
      this.categorySelected = null;
      this.brandFiltered = [];
    },
    saveNew() {
      this.newPool.name = this.getPoolById(this.newPool.id).name;
      if (this.isValidate()) {
        let temp = this.newPool;
        temp.category = this.categorySelected;
        this.pool.push(temp); //add new pool
        this.showNew();
        this.newPool = this.setNewPool();
        this.message = "";
      }
    },
    isValidate() {
      let current = this.newPool;
      let exist = this.pool.find((el) => {
        return (
          (el.name == current.name && el.select == false) ||
          (el.name == current.name &&
            el.category == current.category &&
            el.brand == current.brand)
        );
      });
      if (!_.isUndefined(exist)) {
        this.messageType = "message-error";
        this.message = this.label_error;
        return false;
      }
      return true;
    },
    hidePanel() {
      this.setNewPool();
      this.message = "";
      this.createNew = false;
      this.label_new = this.label_add;
    },
  },
  watch: {
    categorySelected() {
      this.brandFiltered = this.brands.filter((item) => {
        return item.category == this.categorySelected;
      });
    },
  },
  created: function () {
    this.label_new = this.label_add;
    this.brandFiltered = this.categories ? null : this.brands;
  },
  mounted: function () {
    EventBus.$on("hide-pool-new-service", () => {
      this.hidePanel();
    });
  },
};
</script>
<style scoped>
.rows {
  padding-left: 25px;
}

.row {
  padding: 8px;
}

.row .title {
  font-size: 15px;
  font-weight: bold;
}

.save-new{
  max-width: 100px;
}

.max-select{
  max-width: 200px;
  overflow: auto;
}
</style>