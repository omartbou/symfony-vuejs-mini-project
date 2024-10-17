<template>
  <div class="container ">
    <div class="row my-5">
      <div class="col-md-5 mx-auto">
        <div class="card">
          <div class="card-title">
            <h2>Register</h2>
          </div>
          <div class="card-body">
            <form @submit.prevent="registerUser" >
              <div class="mb-3">
                <label for="firstName" >First Name:</label>
                <input type="text" v-model="register.firstName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="lastName" >Last Name:</label>
                <input type="text" v-model="register.lastName" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="email" >Email:</label>
                <input type="email" v-model="register.email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label for="password">Password:</label>
                <input type="password" v-model="register.password" class="form-control" required />
              </div>
              <button type="submit" class="btn btn-primary">save</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';

const register = ref({
   firstName :'',
   lastName :'',
   email : '',
   password : '',
   message : '',
});

const router = useRouter();

const registerUser = async () => {
  try {
    const response = await fetch('/api/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        firstName:register.value.firstName,
        lastName: register.value.lastName,
        email: register.value.email,
        password: register.value.password,
      }),
    });
    if (response.ok) {
      register.value.message = 'Registration successful! Redirecting to login...';
      setTimeout(() => {
        router.push('/login'); // Redirect to login after successful registration
      }, 2000); // Delay to show the success message
    } else {
      register.value.message = 'Registration failed. Please try again.';
    }
  } catch (error) {
    console.error('Error registering in:', error);
  }
};
</script>

<style scoped>
/* Add any styles here if needed */
</style>
