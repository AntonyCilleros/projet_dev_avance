<template>
  <div id="app">
    <header>
      <h1>{{ title }}</h1>
    </header>
    <nav>
      <ul>
        <li><a href="#">Accueil</a></li>
        <li><a href="login">Se connecter</a></li>
        <li><a href="#">À propos</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </nav>
    <main>
      <p>{{ message }}</p>
    </main>
    <footer>
      <p>&copy; 2025 Mon Application Vue</p>
    </footer>
  </div>
</template>

<script>
export default {
  name: 'App',
  data() {
    return {
      title: 'Mon Application Vue',
      message: 'Bienvenue dans mon application Vue.js!'
    }
  }
}
</script>

<style scoped>
#app {
  font-family: Arial, sans-serif;
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

header {
  background-color: #f4f4f4;
  padding: 10px;
  text-align: center;
}

nav ul {
  list-style-type: none;
  padding: 0;
}

nav ul li {
  display: inline;
  margin-right: 10px;
}

main {
  margin-top: 20px;
}

footer {
  margin-top: 20px;
  text-align: center;
  font-size: 0.8em;
}
</style>
