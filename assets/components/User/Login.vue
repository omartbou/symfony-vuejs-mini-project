<template>
  <div class="container ">
  <div class="row my-5">
  <div class="col-md-5 mx-auto">
  <div class="card">
    <div class="card-title">
    <h2>Login</h2>
    </div>
    <div class="card-body">
    <form @submit.prevent="loginUser" >
      <div class="mb-3">
        <label for="email" >Email:</label>
        <input type="email" v-model="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="password">Password:</label>
        <input type="password" v-model="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <div v-if="message">{{ message }}</div>
  </div>
  </div>
  </div>
  </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const message = ref('');
const router = useRouter();

const loginUser = async () => {
  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        email: email.value,
        password: password.value,
      }),
    });

    if (response.ok) {
      const data = await response.json();
      // Store the token or user info in local storage
      localStorage.setItem('userToken', data.token);
      message.value = 'Login successful!';
      // Redirect to a protected route after successful login
      router.push('/home'); // Change this to your desired route
    } else {
      const errorData = await response.json();
      message.value = errorData.message || 'Login failed!';
    }
  } catch (error) {
    console.error('Error logging in:', error);
    message.value = 'An error occurred during login.';
  }
};
</script>

<style scoped>
/* Add any styles here if needed */
</style>
