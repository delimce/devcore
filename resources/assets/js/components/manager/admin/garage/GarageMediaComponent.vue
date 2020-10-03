<template>
  <div>
    <div v-if="access" class="card">
      <div class="card-content">
        <div class="field">
          <label class="label">{{label_images}}</label>
        </div>

        <vue-dropzone
          ref="myVueDropzone"
          :destroyDropzone="false"
          :id="id"
          :options="dropzoneOptions"
          v-on:vdropzone-sending="sendingEvent"
          v-on:vdropzone-error="failedUpload"
          v-on:vdropzone-removed-file="removeFile"
          v-on:vdropzone-mounted="waitToLoad"
        ></vue-dropzone>

        <div class="field is-grouped">
          <div class="mini">
            <pre-loader v-show="preloading"></pre-loader>
            <div v-show="!preloading" v-bind:class="[messageType]">{{message}}</div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="card no-garage">
      <div class="card-content">{{label_no_access}}</div>
    </div>
  </div>
</template>
<script>
import _ from "lodash";
import EventBus from "@/bus";
import vueDropzone from "vue2-dropzone";
import "vue2-dropzone/dist/vue2Dropzone.min.css";
import { getUserToken } from "@/functions";
export default {
  data() {
    return {
      access: false,
      label_no_access:
        "Debe guardar primero la información del taller, para poder tener acceso a esta sección.",
      label_images: "Imágenes de taller",
      label_images_save: "Guardar Imágenes",
      instructions: "Agregue las imágenes para su taller",
      messageType: "",
      preloading: false,
      message: "",
      garage: {},
      label_save: "Guardar",
      id: "myDropzone",
      total: "",
      files: {},
      dropzoneOptions: {
        url: api_url + "/api/manager/garage/media",
        thumbnailWidth: 222,
        addRemoveLinks: true,
        dictDefaultMessage: "Agregue las imágenes para su taller",
        acceptedFiles: "image/*",
        maxFilesSize: 2,
        maxFiles: 6,
        headers: { Authorization: getUserToken() },
      },
    };
  },
  methods: {
    saveImages() {},
    removeFile(file) {
      this.message = "";
      axios
        .delete("/manager/garage/media", {
          data: {
            garage: this.garage.id,
            path: file.name,
          },
        })
        .then((response) => {})
        .catch((error) => {
          this.messageType = "message-error";
          this.message = error.response.data.info.message;
        });
    },
    sendingEvent(file, xhr, formData) {
      formData.append("garage", this.garage.id);
    },
    failedUpload(file, message, xhr) {
      let response = xhr.response;
      let parse = JSON.parse(response, (key, value) => {
        return value;
      });
      this.messageType = "message-error";
      this.message = parse.info.message;
    },
    loadFiles() {
      axios
        .get("/manager/garage/media/" + this.garage.id)
        .then((response) => {
          let files = response.data.info;
          files.forEach((item) => {
            let file = { size: item.size, name: item.name, type: item.mime };
            let url = item.url;
            this.$refs.myVueDropzone.manuallyAddFile(file, url);
          });
        })
        .catch((error) => {
          this.preloading = false;
          this.messageType = "message-error";
          this.message = error.response.data.info.message;
        });
    },
    waitToLoad() {
      if (this.access) {
        setTimeout(
          function () {
            this.loadFiles();
          }.bind(this),
          1000
        );
      }
    },
  },
  components: {
    vueDropzone,
  },
  mounted: function () {
    EventBus.$on("change-garage-info", (garage) => {
      this.garage = garage;
      this.access = !_.isUndefined(this.garage.id);
    });
  },
};
</script>

<style scoped>
.card-content {
  min-height: 350px;
}
.image-instructions {
  margin: 10px;
  font-size: 14px;
}
.subtitle {
  color: #314b5f;
}
</style>