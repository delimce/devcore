<template>
  <div class="select is-info">
    <select  @input="$emit('input', $event.target.value)">
      <option v-if="select" value>{{select_text}}</option>
      <option
        v-for="item in list"
        v-bind:selected="item.id==value"
        :key="item.id"
        :value="item.id"
      >{{ item.desc }}</option>
    </select>
  </div>
</template>
<script>
export default {
  props: {
    list: {
      type: Array,
      default: []
    },
    select: {
      type: Boolean,
      default: false
    },
    value: {
      type: String,
      default: null
    }
  },
  data() {
    return {
      select_text: "seleccionar"
    };
  }
};
</script>
