<template>
  <v-container>
    <v-row justify="center">
      <v-col cols="12" sm="8" md="6">
        <v-card>
          <v-card-title>Connexion</v-card-title>
          <v-card-text>
            <v-form @submit.prevent="login" v-if="!registerMode">
              <v-text-field v-model="email" label="Email" type="text" required></v-text-field>
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
*
<script>

import axios from 'axios'
import { printError } from '@/js/utils';

export default {
  name: 'LoginPage',
  data() {
    return {
      email: '', // email or username
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
      axios.post('/api/connection/login', { email: this.email, password: this.password }).then(response => {
        if (response.status === 200) {
          console.log('Connexion réussie');
          this.$router.push({ name: 'Home' });
          this.$emit('updateUser');
        } else {
          printError('Login', response)
        }
      }).catch(error => {
        printError('Login - catch', error.response);
      });
    },
    createAccount() {
      if (this.register.password !== this.register.passwordConfirmation) {
        console.error('Les mots de passe ne correspondent pas');
        return;
      }
      axios.post('/api/connection/register', this.register).then(response => {
        if (response.status === 201) {
          console.log('Compte créé avec succès');
          this.$router.push({ name: 'Home' });
          this.$emit('updateUser');
        } else {
          printError('Register', response)
        }
      }).catch(error => {
        printError('Register - catch', error.response)
      });
    }
  },
  mounted() {
    // if localhost then export this component to console
    if (window.location.hostname === 'localhost') {
      window.app = this;
    }
  }
}

</script>