<template>
  <div class="card my-3">
    <div class="card-body d-flex justify-content-center">
      <div class="dropdown">
        <button class="btn btn-success dropdown-toggle"   data-bs-toggle="dropdown" >
          Sort By
        </button>
        <ul class="dropdown-menu" >

          <li>
            <a class="dropdown-item" @click="sortProducts('asc')" >
              Price Ascending
            </a>
          </li>
          <li>
            <a class="dropdown-item" @click="sortProducts('desc')">
              Price Descending
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script setup>
import { defineEmits } from 'vue';
const emit = defineEmits(['sortChanged']);
const sortProducts = (order) => {
  emit('sortChanged', order);
};
</script>
<style>
</style>
