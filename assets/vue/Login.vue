<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="6">
        <v-card>
          <v-card-title>Connexion</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="login" v-if="!registerMode">
              <v-text-field v-model="user" label="Email ou nom d'utilisateur" type="text" required></v-text-field>
              <v-text-field v-model="password" label="Mot de passe" type="password" required></v-text-field>
              <v-btn type="submit" color="primary" class="mb-3" block>Se connecter</v-btn>
              <v-btn color="primary" block @click="registerMode = true">Créer un compte</v-btn>
            </v-form>
            <v-form @submit.prevent="createAccount" v-else>
              <v-text-field v-model="register.email" label="Email" type="email" required></v-text-field>
              <v-text-field v-model="register.username" label="Nom d'utilisateur" type="text" required></v-text-field>
              <v-text-field v-model="register.password" label="Mot de passe" type="password" required></v-text-field>
              <v-text-field v-model="register.passwordConfirmation" label="Confirmation du mot de passe" type="password" required></v-text-field>
              <v-btn type="submit" color="primary" class="mb-3" block>Créer un compte</v-btn>
              <v-btn color="primary" block @click="registerMode = false">Se connecter</v-btn>
            </v-form>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>

import axios from 'axios'

export default {
  name: 'LoginPage',
  data() {
    return {
      user: '', // email or username
      password: '',
      registerMode: false,
      register: {
        email: '',
        username: '',
        password: '',
        passwordConfirmation: ''
      }
    }
  },
  methods: {
    login() {

    },
    createAccount() {
      axios.post('/user/create', register).then(response => {
            if (response.data.success) {
              console.log('Compte créé avec succès');
            } else {
              console.error('Erreur lors de la création du compte');
            }
          }).catch(error => {
            console.error('Erreur lors de la requête:', error);
          });
    }
  }
}
</script>
