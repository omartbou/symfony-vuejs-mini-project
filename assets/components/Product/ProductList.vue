<template>
  <div class="container">
    <div class="row my-5">
      <div class="col-md-9">
        <Search @search="handleSearch" />

        <div class="row">
          <div class="col-md-12">
            <h2 class="mb-4">Products</h2>
          </div>
          <div v-for="product in products" :key="product.id" class="col-md-4 mb-4">
            <div class="card h-100">
              <img :src="product.image" alt="Product Image" class="card-img-top" />

              <div class="card-body">
                <h5 class="card-title">{{ product.name }}</h5>
                <p class="card-text">{{ product.price }}</p>
                <router-link :to="{ name: 'product-details', params: { id: product.id } }" class="btn btn-primary">
                  View
                </router-link>
                <router-link :to="{ name: 'product-update', params: { id: product.id } }" class="btn btn-success">
                  Update
                </router-link>
                <button @click="deleteProduct(product.id)" class="btn btn-danger">
                  Delete
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Bootstrap Pagination Controls -->
        <nav aria-label="Page navigation example" class="mt-4">
          <ul class="pagination justify-content-center">
            <!-- Previous Button -->
            <li class="page-item" :class="{ disabled: page === 1 }">
              <a class="page-link" href="#" @click.prevent="fetchProducts(page - 1)">Previous</a>
            </li>

            <!-- Page Number Links -->
            <li
                v-for="pageNumber in totalPages"
                :key="pageNumber"
                class="page-item"
                :class="{ active: pageNumber === page }">
              <a class="page-link" href="#" @click.prevent="fetchProducts(pageNumber)">{{ pageNumber }}</a>
            </li>

            <!-- Next Button -->
            <li class="page-item" :class="{ disabled: page === totalPages }">
              <a class="page-link" href="#" @click.prevent="fetchProducts(page + 1)">Next</a>
            </li>
          </ul>
        </nav>
      </div>
      <div class="col-md-3">
        <Sort @sortChanged="sortProducts"/>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Search from './Search.vue'
import Sort from './Sort.vue'; // Importing the SortOptions component

// Variables for products, pagination info
const products = ref([]);
const page = ref(1);
const totalPages = ref(1);
const limit = 10; // Number of products per page
const searchQuery = ref('');
const sortOrder = ref('');

// Function to fetch products with pagination
const fetchProducts = async (newPage = 1) => {
  if (newPage < 1 || newPage > totalPages.value) return; // Prevent invalid page requests

  try {
    const response = await fetch(`/api/products?page=${newPage}&limit=${limit}&search=${encodeURIComponent(searchQuery.value)}&sort=${sortOrder.value}`);
    if (response.ok) {
      const data = await response.json();
      products.value = data.data;
      page.value = data.meta.current_page;
      totalPages.value = data.meta.total_pages;
    } else {
      console.error('Failed to fetch products');
    }
  } catch (error) {
    console.error('Error fetching products:', error);
  }
};
const handleSearch = (query) => {

  searchQuery.value =query;

  fetchProducts(1);
}
const sortProducts  = (order) => {

  sortOrder.value =order;
  console.log('Selected sort order:', order);

  fetchProducts(1);
}// Fetch products on component mount
const deleteProduct = async (id)=> {
  try {
    const response = await fetch(`/api/products/${id}`, {
      method: 'DELETE',
    });
    if(response.ok){

      alert('Product deleted successfully');

      products.value = products.value.filter(product => product.id !== id);

    }else {
      console.error('Failed to delete product');
    }
  }catch (error) {
    console.error('Error deleting product:', error);
  }
};

onMounted(() => {
  fetchProducts();
});

</script>
<style>
.product-item {
  margin-bottom: 20px;
}
.product-item img {
  max-width: 100%;
  height: auto;
}</style>