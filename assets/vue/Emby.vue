<template>
  <v-container class="fill-height d-flex justify-center align-center">

  </v-container>
</template>

<script>
export default {
  name: 'LoginPage',
  data() {
    return {};
  },
  methods: {

  },
  mounted() {
    // Si on est en localhost, expose le composant dans la console
    if (window.location.hostname === 'localhost') {
      window.app = this;
    }
  }
};
</script>

<style>

</style>
