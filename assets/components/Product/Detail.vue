<template>
  <div >
    <Navbar/>
  </div>
  <div class="container">
    <div class="row-my-5">
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <img v-if="product.image" :src="product.image" alt="Product Image" class="img-fluid" />
            <h5>{{product.name}}</h5>
            <p>{{product.price}}</p>
            <p>{{product.description}}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>
<script setup >
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router'; // Assuming you're using Vue Router to get the 'id'
import Navbar from '../Navbar/Navbar.vue';

const product =  ref({});;
const route = useRoute();
const id = route.params.id

onMounted(async () => {
  try {
    const response = await fetch(`/api/products/show/${id}`);
    if (response.ok) {
      product.value = await response.json(); // Store the product data

    } else {
      console.error('Failed to fetch product');
    }
  } catch (error) {
    console.error('Error fetching product:', error);
  }
});
</script>
