<template>
    <div class="w-full h-screen flex flex-col items-center mt-4">
        <div v-if="successMessage" class="border-2 border-green-800 bg-green-200 py-2 px-10 mb-4 rounded-md">
            {{successMessage}}
        </div>

        <div v-if="errorMessage" class="border-2 border-red-800 bg-red-200 py-2 px-10 mb-4 rounded-md">
            {{errorMessage}}
        </div>

        <form @submit.prevent="login" class="flex flex-col items-center border-2 rounded-lg px-32 py-10 w-8/12">
            <input type="email" name="email" id="email" placeholder="Email" v-model="fields.email" class="bg-gray-50 border mt-4 border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
            <input type="password" name="password" id="password" placeholder="Password" v-model="fields.password" class="bg-gray-50 border mt-4 border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
            <button type="submit" class="mt-4 border-2 bg-blue-200 border-blue-800 hover:bg-blue-400 hover:text-white py-1 px-6 rounded-md">Log In</button>
        </form>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                fields: {
                    'email': '',
                    'password': ''
                },
                successMessage: '',
                errorMessage: ''
            }
        },
        props: [
            'token'
        ],
        methods: {
            login(){
                axios.post('api/login', this.fields, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(res => {
                    console.log(res.data.success);
                    this.$emit('changeToken');
                    this.successMessage = res.data.success;
                    this.fields.email = '';
                    this.fields.password = '';
                    // window.location.href = window.location.origin;
                })
                .catch(err => {
                    this.errorMessage = err.response.data.error;
                })
            }
        }
    }
</script>