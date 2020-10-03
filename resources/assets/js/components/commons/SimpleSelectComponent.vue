<template>
  <div class="select is-info">
    <select :disabled="disable"  @input="$emit('input', $event.target.value)">
      <option v-if="select" value="">{{select}}</option>
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
      type: String,
      default: null
    },
    value: {
      type: String,
      default: null
    },
    disable:{
      type:Boolean,
      default:false
    }
  },
  data() {
    return {
      select_text: "seleccionar"
    };
  }
};
</script>
