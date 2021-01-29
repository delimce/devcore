<template>
  <div>
    <div class="account-data">
      <div class="columns">
        <div class="column label">Nombre</div>
        <div class="column is-three-quarters">{{user.name}}</div>
      </div>

      <div class="columns">
        <div class="column label">Apellido</div>
        <div class="column is-three-quarters">{{user.lastname}}</div>
      </div>

      <div class="columns">
        <div class="column label">Email</div>
        <div class="column is-three-quarters">{{user.email}}</div>
      </div>

      <div class="columns">
        <div class="column label">Creado:</div>
        <div class="column is-three-quarters">{{user.created_at}}</div>
      </div>
    </div>
    <p class="close">
      <a @click="sessionClose()"> {{ label_close }}</a>
    </p>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import EventBus from "@/bus";
export default {
  mixins: [WebsiteMixin],
  props:["user"],
  data() {
    return {
    };
  },
  methods: {},
  computed: {},
  mounted(){

  }
};
</script>
<style scoped>
.account-data {
  overflow: hidden;
  margin-top: 22px;
  margin-left: 7px;
  padding: 4px;
  line-height: 3px;
}

.account-data .column {
  margin-top: 4px;
}

.account-data .column .label {
  font-weight: bold;
  text-transform: capitalize;
}

.close {
  position: fixed;
  height: auto;
  bottom: 10%;
  right: 38%;
}
</style>