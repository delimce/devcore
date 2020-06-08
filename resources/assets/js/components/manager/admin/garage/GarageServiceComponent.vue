<template>
  <div>
    <div class="card">
      <div class="card-content">
        <div class="rows">
          <div class="row is-full">
            <div class="field">
              <label class="label">{{label_segment}}</label>
              <div class="control">
                <div class="select">
                  <select>
                    <option
                      v-for="item in segments"
                      :key="item.id"
                      v-bind:value="item.id"
                    >{{ item.name }}</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import EventBus from "../../../../bus";
export default {
  data() {
    return {
      label_segment: "Segmento Automotor:",
      garage: {},
      segments: []
    };
  },
  methods: {
    getSegments() {
      axios
        .get("/manager/garage/services/segments")
        .then(response => {
          this.segments = response.data.info.list;
        })
        .catch(error => {});
    }
  },
  created: function() {
    EventBus.$on("change-garage-info", garage => {
      this.garage = garage;
      this.getSegments();
    });
  }
};
</script>

<style scoped>
</style>