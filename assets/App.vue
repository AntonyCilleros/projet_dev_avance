<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <v-app-bar-title>Mon Application</v-app-bar-title>
      <v-spacer></v-spacer>
      <v-btn to="/" text>Accueil</v-btn>
      <v-btn to="/login" text>Connexion</v-btn>
    </v-app-bar>

    <v-main>
      <v-container fluid>
        <router-view></router-view>
      </v-container>
    </v-main>

    <v-footer app>
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  name: 'App',
  data: () => ({
    //
  }),
}
</script>

<style>
/* Vous pouvez ajouter des styles globaux ici si nécessaire */
</style>
