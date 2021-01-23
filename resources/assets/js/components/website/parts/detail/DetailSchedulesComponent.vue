<template>
  <div>
    <div class="title">{{ label_schedules }}</div>

    <div
      v-for="schedule in schedulesData"
      :key="schedule.id"
      v-show="isWorkingDay(schedule)"
      class="columns"
    >
      <div class="column">
        <b>{{ getNameOfDay(schedule.day) }}</b>
      </div>
      <div class="column">{{ truncateSeg(schedule.am1) }}</div>
      <div class="column">{{ truncateSeg(schedule.am2) }}</div>
      <div class="column">{{ truncateSeg(schedule.pm1) }}</div>
      <div class="column">{{ truncateSeg(schedule.pm2) }}</div>
    </div>
  </div>
</template>
<script>
import WebsiteMixin from "@/components/website/mixins/WebsiteMixin";
import GarageDetailImageComponent from "./DetailImageComponent.vue";
export default {
  components: { GarageDetailImageComponent },
  name: "DetailSchedulesComponent",
  props: ["schedules"],
  mixins: [WebsiteMixin],
  data() {
    return {
      label_schedules: "Horarios",
      schedulesData:[]
    };
  },
  methods: {
    truncateSeg(time) {
      return time ? time.substring(0, 5) : "";
    },
    isWorkingDay(sc) {
      return !(!sc.am1 && !sc.am2 && !sc.pm1 && !sc.pm2);
    },
    loadData() {
      this.schedulesData = this.schedules;
    },
  },
  mounted() {
    this.waitToLoad();
  },
};
</script>
<style scoped>
</style>