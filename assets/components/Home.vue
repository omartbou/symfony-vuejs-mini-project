<template>
<div >
<Navbar/>
</div>
  <div>
    <h1>Hello, World!</h1>
    <div class="col-md-9">
    <div v-if="successMessage" class="alert alert-success">
      {{ successMessage }}
    </div>
    </div>
<!--    <product-list></product-list>-->
      <ProductList /><!-- Use kebab-case for component names -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import ProductList from './Product/ProductList';
import Navbar from './Navbar/Navbar.vue';

const successMessage = ref('');

onMounted(() => {
  const message = localStorage.getItem('successMessage');
  if (message) {
    successMessage.value = message; // Set the message to display
    localStorage.removeItem('successMessage'); // Clear message after displaying
  }
});
</script>

<style scoped>
h1 {
  color: blue;
}

</style>
