<template>
    <div class="flex flex-row items-center h-screen border-2 border-green-200">
        <div class="flex flex-col items-center justify-evenly border-2 border-black h-screen w-2/12 sticky">
            <router-link @click="deleteMessages" to="/" class="py-1 px-6 border-2 rounded-md hover:bg-gray-200 hover:border-black">Home</router-link>
            <router-link @click="deleteMessages" v-if="!this.logged_in" to="/register" class="py-1 px-6 border-2 rounded-md hover:bg-gray-200 hover:border-black">Register</router-link>
            <router-link @click="deleteMessages" v-if="!this.logged_in" to="/login" class="py-1 px-6 border-2 rounded-md hover:bg-gray-200 hover:border-black">Log In</router-link>
            <button @click="logout" v-else class="py-1 px-6 border-2 rounded-md hover:bg-gray-200 hover:border-black">Log Out</button>
        </div>

        <div class="w-full h-screen flex flex-col items-center justify-center border-2 border-pink-200">
            <div v-for="success in successMessages" :key="success" class="border-2 border-green-800 bg-green-200 py-2 px-10 mb-4 rounded-md mt-2">
                {{success}}
            </div>
                
            <router-view @changeToken="getToken" :token="token" class="w-full h-screen"></router-view>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

    export default {
        data(){
            return {
                token: '',
                successMessages: [],
                logged_in: false
            }
        },
        created(){
            this.getToken();
        },
        methods: {
            getToken(){
                axios.get('api/get-token')
                .then(res => {
                    console.log(res.data.token);
                    this.token = res.data.token;
                    window.localStorage.setItem('token', this.token)
                    if(this.token){
                        this.logged_in = true;
                    }
                })
            },
            logout(){
                axios.post('api/logout', {}, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(res => {
                    this.token = '';
                    this.successMessages.push(res.data.success);
                    this.logged_in = false;
                })
                .catch(err => console.log(err))
            },
            deleteMessages(){
                this.successMessages = [];
            }
        }
    }
</script>