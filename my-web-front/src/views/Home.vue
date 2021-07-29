<template>
  <div class="home">
    <h1>Test de Fred !</h1>
    <img alt="Vue logo" src="../assets/logo.png" />
    <HelloWorld msg="Welcome to Your Vue.js App" />

    <hr />
    <p>Environment variables (from process.env):</p>
    <dl class="row">
      <dt class="col-sm-3">Node env :</dt>
      <dd class="col-sm-9">{{ nodeEnv }}</dd>
      <dt class="col-sm-3">Base URL:</dt>
      <dd class="col-sm-9">{{ baseUrl }}</dd>
      <dt class="col-sm-3">App Id:</dt>
      <dd class="col-sm-9">{{ appID }}</dd>
      <dt class="col-sm-3">App name:</dt>
      <dd class="col-sm-9">{{ appName }}</dd>
      <dt class="col-sm-3">App version:</dt>
      <dd class="col-sm-9">{{ appVersion }}</dd>
      <dt class="col-sm-3">App API URL:</dt>
      <dd class="col-sm-9">{{ appApiUrl }}</dd>
    </dl>
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from "@/components/HelloWorld.vue";

export default {
  name: "Home",
  components: {
    HelloWorld,
  },
  computed: {
    nodeEnv: function() {
      return process.env.NODE_ENV;
    },
    baseUrl: function() {
      return process.env.BASE_URL;
    },
    appID: function() {
      return process.env.VUE_APP_A2HS_APP_ID || "App ID not set";
    },
    appName: function() {
      return process.env.APP_NAME || "App name not set";
    },
    appVersion: function() {
      return process.env.APP_VERSION || "App version not set";
    },
    appApiUrl: function() {
      return process.env.VUE_APP_API_ROOT || "App API URL not set";
    }
  }
};
</script>
