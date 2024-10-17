<!-- App.vue -->

<template>

    <router-view></router-view> <!-- This renders the current route's component -->
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>
/* Add some basic styling */
nav {
  margin-bottom: 10px;
}

router-link {
  margin-right: 10px;
}
</style>
