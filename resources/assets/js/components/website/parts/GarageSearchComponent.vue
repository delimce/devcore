<template>
  <div class="box is-centered">
    <div class="rows">
      <div class="control">
        <input
          class="input"
          type="text"
          :placeholder="label_search_placeholder"
        />
      </div>
      <br />
      <div class="columns">
        <div class="column is-two-thirds">
          <div class="select">
            <select>
              <option>Select dropdown</option>
              <option>With options</option>
            </select>
          </div>
        </div>

        <div class="column">
          <input class="input" type="text" :placeholder="label_zip" />
        </div>
      </div>
      <div class="control">
        <a :href="registerUrl" class="button is-rounded button-signup">{{
          label_button
        }}</a>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "GarageSearchComponent",
  data() {
    return {
      label_search_placeholder:
        "Nombre del taller, producto, nombre del servicio...",
      label_button: "Buscar talleres",
      label_zip: "Cod. postal",
    };
  },
};
</script>
<style scoped>
.box {
  border: 1px solid rgb(156, 156, 156);
  background-color: white;
  opacity: 0.9;
  color: blanchedalmond;
  margin: auto;
  margin-top: 30px;
  margin-bottom: 400px;
  max-width: 540px;
}

.box input {
  opacity: 1;
}
</style>