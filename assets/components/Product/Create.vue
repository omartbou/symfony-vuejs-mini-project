<template>
  <div>
    <Navbar/>

  </div>
  <div class="container">
    <div class="row">
      <div class="col-md-5 mx-auto">
        <div class="card">
          <div class="card-title">
    <h2>Create New Product</h2>
          </div>
          <div class="card-body">
    <form @submit.prevent="createProduct">
      <div class="mb-3">
        <label for="name">Product Name:</label>
        <input type="text"
               id="name"
               class="form-control"
               v-model="product.name"
               :class="product.name.length ? validClass:errorClass"
               required />
      </div>
      <div class="mb-3">
        <label for="description">Description:</label>
        <textarea id="description"
                  class="form-control"
                  v-model="product.description"
                  :class="product.description.length ? validClass:errorClass"
                  required></textarea>
      </div>
      <div class="mb-3">
        <label for="price">Price:</label>
        <input type="number"
               id="price"
               class="form-control"
               v-model="product.price"
               :class="product.price !== null ? validClass:errorClass"
               required />
      </div>

      <div class="mb-3">
        <input
            type="file"
            @change="onFileChange"
            class="form-control"
            :class="product.image !==null ? validClass:errorClass"
            required
        />
      </div>
      <button type="submit"
              class="btn btn-primary" >Create Product</button>
    </form>
  </div>
  </div>
  </div>
  </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Navbar from '../Navbar/Navbar.vue';
const product = ref({
  name: '',
  price: 0,
  description: '',
  image: null,
});

const onFileChange = (event) => {
  product.value.image = event.target.files[0]; // Get the first file
};

const message = ref('');
const validClass = ref('form-control is-valid');
const errorClass = ref('form-control is-invalid');
const createProduct = async () => {
  const formData = new FormData();
  formData.append('name', product.value.name);
  formData.append('description', product.value.description);
  formData.append('price', product.value.price);
  if (product.value.image) {
    formData.append('image', product.value.image);
  }
  console.log(product.value);

  try {
    const response = await fetch('/api/products/new', {
      method: 'POST',
      body: formData,
    });
    console.log(response);

    if (response.ok) {
        const data = await response.json();
      localStorage.setItem('successMessage',data.message);
        window.location.href = '/home';

    } else {
      message.value = 'Failed to create product.';
    }
  } catch (error) {
    console.error(error);
    message.value = 'An error occurred.';
  }
};
</script>

<style scoped>
h2 {
  color: blue;
}
</style>
