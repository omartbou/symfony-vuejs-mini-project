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
    <form @submit.prevent="updateProduct">
      <div class="mb-3">
        <label for="name">Product Name:</label>
        <input type="text"
               id="name"
               class="form-control"
               v-model="product.name"
               required />
      </div>
      <div class="mb-3">
        <label for="description">Description:</label>
        <textarea id="description"
                  class="form-control"
                  v-model="product.description"
                  required></textarea>
      </div>
      <div class="mb-3">
        <label for="price">Price:</label>
        <input type="number"
               id="price"
               class="form-control"
               v-model="product.price"
               required />
      </div>

      <div class="mb-3">
        <input
            type="file"
            id="image"
            class="form-control"
            @change="handleFileUpload"
        />
      </div>
      <button type="submit"
              class="btn btn-primary" >Update Product</button>
    </form>
  </div>
  </div>
  </div>
  </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import Navbar from '../Navbar/Navbar.vue';

const product = ref({
  name: '',
  price: '',
  description: '',
  image: '',
});
const route = useRoute();
const router = useRouter();
const id = route.params.id;
const previewImage = ref(null); // To hold the image preview

onMounted(async () => {


  try {
    const response = await fetch(`/api/products/show/${id}`);
    if (response.ok) {
      product.value = await response.json(); // Load product data for editing
      previewImage.value = product.value.image; // Set initial image preview if needed

    } else {
      console.error('Failed to fetch product for update');
    }
  } catch (error) {
    console.error('Error fetching product for update:', error);
  }
});

const handleFileUpload = (event) => {
  const file = event.target.files[0]; // Get the selected file
  if (file) {
    product.value.image = file; // Update product with the file object

    // Create a preview URL for the selected image
    previewImage.value = URL.createObjectURL(file);
  }
};
const updateProduct = async () => {
  const formData = new FormData();
  formData.append('name', product.value.name); // Ensure this is not empty
  formData.append('description', product.value.description); // Ensure you're adding this
  formData.append('price', product.value.price); // Ensure this is a valid number

  // Append the image only if it exists
  if (product.value.image) {
    formData.append('image', product.value.image);
  }
  console.log('Sending data:', {
    name: product.value.name,
    description: product.value.description,
    price: product.value.price,
    image: product.value.image ? product.value.image.name : 'No image', // Log image name or 'No image'
  });
  try {
    const response = await fetch(`/api/products/${id}/edit`, {
      method: 'POST',
      body: formData,
      headers: {
        'Content-Type': 'application/json',
      },
    });

    if (response.ok) {
      alert('Product updated successfully');
      // Redirect or perform another action
    } else {
      console.error('Failed to update product');
      const errorData = await response.json();
      console.error('Error details:', errorData.errors);
    }
  } catch (error) {
    console.error('Error updating product:', error);
  }
};
</script>
<style scoped>
h2 {
  color: blue;
}
</style>
