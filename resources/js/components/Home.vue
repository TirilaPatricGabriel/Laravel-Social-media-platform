<template>
    
    <div class="w-full h-screen flex flex-col items-center">

        <form @submit.prevent="addOrEditPost" class="flex flex-col items-center border-2 rounded-lg px-36 py-14 mt-8">
            <input type="text" name="title" id="title" placeholder="Title" v-model="fields.title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
            <textarea type="text" name="text" id="text" placeholder="Text" v-model="fields.text" cols="10" rows="4" class="bg-gray-50 border mt-4 border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
            <button type="submit" id="submitButton" class="mt-4 border-2 bg-blue-200 border-blue-800 hover:bg-blue-400 hover:text-white py-1 px-6 rounded-md">Add Post</button>
        </form>
        
        <ul class="w-6/12 h-full flex flex-col items-center">
            <li v-for="post in posts" :key="post" class="mt-10 border-2 rounded-md w-8/12 h-full flex flex-col items-center">
                <div class="flex items-center justify-center mt-8">
                    <router-link :to="'/post/' + post.id" class="text-4xl font-bold">{{post.title}}</router-link>
                </div>
                <div class="w-9/12 mt-6 h-full flex flex-col">
                    <p class="text-xl h-full mb-8">{{post.text}}</p>
                    <div class="flex flex-row items-center justify-center mb-2">
                        <button @click="editPost(post.id, post.title, post.text)" class="border-2 rounded-md border-green-800 bg-green-200 py-1 px-6 hover:bg-green-600 hover:text-white font-bold mr-2">EDIT</button>
                        <button @click="deletePost(post.id)" class="border-2 rounded-md border-red-800 bg-red-200 py-1 px-6 hover:bg-red-600 hover:text-white font-bold ml-2">DELETE</button>
                    </div>
                </div>
            </li>
        </ul>
    </div>

</template>

<script>
    export default {
        data(){
            return {
                fields: {
                    'title': '',
                    'text': '',
                },
                posts: [],
                edit_id: -1
            }
        },
        props: [
            'token'
        ],
        created(){
            this.getPosts();
        },
        methods: {
            getPosts(){
                axios.get('api/posts')
                .then(res => {
                    this.posts = res.data.posts.data;
                    console.log(this.posts);
                })
                .catch(err => console.log(err));
            },  
            addOrEditPost(){
                if(this.edit_id === -1){
                    axios.post('api/posts', this.fields, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(res => {
                        // this.posts.push(res.data.post);
                        this.fields.title = '';
                        this.fields.text = '';
                        console.log(res);
                        this.getPosts();
                    })
                    .catch(err => {
                        console.log(err);
                    })
                }
                else{
                    axios.put(`api/posts/${this.edit_id}`, this.fields, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(res => {
                        this.edit_id = -1;
                        const button = document.querySelector('#submitButton');
                        button.innerText = 'Add Post';
                        this.fields.title = '';
                        this.fields.text = '';
                        this.getPosts();
                    })
                    .catch(err => console.log(err));
                }
            },
            deletePost(id){
                console.log(id);
                axios.delete(`api/posts/${id}`, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(res => {
                    console.log(res);
                    this.getPosts();
                })
                .catch(err => console.log(err));
            },
            editPost(id, title, text){
                this.edit_id = id;
                const button = document.querySelector('#submitButton');
                button.innerText = 'Edit Post';
                this.fields.title = title;
                this.fields.text = text;
            }
        }
    }
</script>