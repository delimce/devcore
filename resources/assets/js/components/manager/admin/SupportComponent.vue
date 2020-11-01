<template>
  <div>
    <span class="title is-4">{{ title }}</span>
    <span>{{ description }}</span>
    <section>
      <div class="row" @click="message = ''">
        <simple-select-component
          :list="supportTypes"
          :select="label_type"
          v-model="support.type"
        ></simple-select-component>
      </div>
      <div class="row desc">
          <textarea
            v-model="support.description"
            class="textarea is-focused has-fixed-size"
            :placeholder="label_desc"
          ></textarea>
      </div>
    </section>

    <div class="field is-grouped">
      <div class="control">
        <button
          type="submit"
          @click="sendRequest()"
          :disabled="sent"
          class="button is-link"
        >
          {{ label_save }}
        </button>
      </div>
      <div class="mini">
        <pre-loader v-show="preloading"></pre-loader>
        <div v-show="!preloading" v-bind:class="[messageType]">
          {{ message }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import managerMixin from "@/components/manager/mixins/ManagerMixin.js";
import EventBus from "@/bus";
import _ from "lodash";
export default {
  name: "SupportComponent",
  mixins: [managerMixin],
  data() {
    return {
      title: "¿Algún problema con el uso de nuestra plataforma?",
      description: "Envíenos sus dudas y le atenderemos..",
      label_type: "Tipo de solicitud",
      label_desc: "Su comentario aquí",
      label_sent: "Enviado",
      sent: false,
      support: {
        garage: null,
        type: "",
        description: "",
      },
    };
  },
  methods: {
    sendRequest: _.debounce(function () {
      this.preloading = true;
      this.saveRequest();
    }, 205),

    saveRequest() {
      axios
        .post("/manager/support/request", this.support)
        .then((response) => {
          this.messageType = "message-ok";
          this.preloading = false;
          this.message = response.data.info.message;
          this.sent = true;
          this.label_save = this.label_sent;
        })
        .catch((error) => {
          this.messageType = "message-error";
          this.preloading = false;
          this.message = error.response.data.info.message;
        });
    },
  },
  mounted() {},
};
</script>
<style scoped>
span {
  padding: 6px;
  line-height: 22px;
  display: block;
}

div {
  margin-top: 15px;
}

.desc{
  max-width: 60%;
}
</style>