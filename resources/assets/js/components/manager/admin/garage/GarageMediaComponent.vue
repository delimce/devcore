<template>
  <div>
    <div class="card">
      <div class="card-content">
        <div class="field">
          <label class="label">{{label_images}}</label>
        </div>

  <!--       <vue-dropzone :options="dropzoneOptions" :useCustomSlot="true">
          <div class="dropzone-custom-content">
            <i class="fas fa-cloud-upload-alt"></i>
            <h3 class="dropzone-custom-title">{{dropzone1}}</h3>
            <div class="subtitle">{{dropzone2}}</div>
          </div>
        </vue-dropzone> -->

      </div>
    </div>
  </div>
</template>
<script>
import vueDropzone from "vue2-dropzone";
export default {
  data() {
    return {
      label_images: "Imágenes de taller",
      instructions: "Agregue las imágenes para su taller",
      dropzone1: "Drag and drop to upload content!",
      dropzone2: "...or click to select a file from your computer",
      messageType: "",
      preloading: false,
      message: "",
      label_save: "Guardar",
      id: "",
      total: "",
      files: {},
      dropzoneOptions: {
        url: "https://httpbin.org/post",
        thumbnailWidth: 200,
        addRemoveLinks: true
      }
    };
  },
  methods: {
    saveImages() {}
  },

  components: {
    vueDropzone
  }
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

.dropzone-custom-content {
  padding-top: 40px;  
  position: absolute;
  border: 1px solid slategray;
  width: 96%;
  min-height: 240px;
  text-align: center;
  box-shadow: 5px 5px 4px #888888;
}

.dropzone-custom-title {
  margin-top: 0;
  color: #00b782;
}

.subtitle {
  color: #314b5f;
}
</style>