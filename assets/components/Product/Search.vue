<!-- SearchBar.vue -->
<template>
  <div class="mb-4">
    <input
        type="text"
        class="form-control"
        v-model="searchQuery"
        placeholder="Search products..."
        @input="updateSearchQuery"
    />
  </div>
</template>

<script setup>
import { ref, defineEmits } from 'vue';

const emit = defineEmits(); // Emitting events to the parent
const searchQuery = ref('');

// Emit the search query to the parent component
const updateSearchQuery = () => {
  emit('search', searchQuery.value);
};

</script>
