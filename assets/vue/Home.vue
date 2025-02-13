<template>
  <v-container class="fill-height d-flex justify-center align-center">
    <v-card class="pa-6 text-center" elevation="10" max-width="400">
      <v-card-title class="text-h5 font-weight-bold">
        {{ $t('home') }}
      </v-card-title>
      <v-card-text>
        {{ $t('home_message') }}
      </v-card-text>
      <v-btn color="primary" size="large" class="mt-4" :to="{ name: 'Emby' }" :disabled="!$root.user">
        EMBY
      </v-btn>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: 'LoginPage',
  data() {
    return {};
  },
  mounted() {
    // Si on est en localhost, expose le composant dans la console
    if (window.location.hostname === 'localhost') {
      window.app = this;
    }
  }
};
</script>

<style scoped>
.fill-height {
  min-height: 95vh;
  background: linear-gradient(135deg, #2196F3, #E91E63);
  color: white;
  overflow: hidden;
}
</style>
