<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">Example Component</div>
          <div class="card-body">I'm an example component. devcore, change</div>
          <p>
            <button @click="myMessage(params)">Reverse Message</button>
            <ul>
             <li v-for="item in languages" :key="item">{{ item }}</li>
            </ul>
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    console.log("Component mounted.");
  },

  data() {
    return {
      params: {name:"Luis",age:39},
      languages:['php','java','python']
    };
  },

  methods: {
    myMessage(msg) {
      console.log(msg);
    }
  }
};
</script>