<template>
    <div class="w-full h-screen flex flex-col items-center">

        <div class="w-6/12 flex flex-col items-center border-2 border-black rounded-md mt-4">
            <div class="flex items-center justify-center mt-8">
                <input type="text" v-model="fields.title" class="text-3xl font-bold bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" :placeholder="post.title" >
            </div>
            <div class="w-9/12 mt-6 h-full flex flex-col">
                <textarea type="text" v-model="fields.text" class="text-xl h-full mb-8 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" cols="10" rows="10" :placeholder="post.text"/>
                <div class="flex flex-row items-center justify-center mb-2">
                    <button @click="editPost()" class="border-2 rounded-md border-green-800 bg-green-200 py-1 px-6 hover:bg-green-600 hover:text-white font-bold mr-2">EDIT</button>
                    <button @click="deletePost()" class="border-2 rounded-md border-red-800 bg-red-200 py-1 px-6 hover:bg-red-600 hover:text-white font-bold ml-2">DELETE</button>
                </div>
            </div>
        </div>

        <form @submit.prevent="addOrEditComment" class="flex flex-col items-center mt-8">
            <textarea type="text" name="text" id="text" placeholder="Text" v-model="comment_fields.text" cols="40" rows="4" class="bg-gray-50 border mt-4 border-gray-300 text-gray-900 text-sm rounded-md focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"/>
            <button type="submit" id="submitButton" class="mt-4 border-2 bg-blue-200 border-blue-800 hover:bg-blue-400 hover:text-white py-1 px-6 rounded-md">Add Comment</button>
        </form>

        <ul class="w-6/12 h-full flex flex-col items-center">
            <li v-for="comment in comments" :key="comment.id" class="mt-10 border-2 rounded-md w-8/12 h-full flex flex-col items-center">
                <div class="flex items-center justify-center mt-8">
                    <router-link :to="'/comment/' + comment.id" class="text-4xl font-bold">{{comment.title}}</router-link>
                </div>
                <div class="w-9/12 mt-6 h-full flex flex-col">
                    <p class="text-xl h-full mb-8">{{comment.text}}</p>
                    <div class="flex flex-row items-center justify-center mb-2">
                        <button @click="editComment(comment.id, comment.text)" class="border-2 rounded-md border-green-800 bg-green-200 py-1 px-6 hover:bg-green-600 hover:text-white font-bold mr-2">EDIT</button>
                        <button @click="deleteComment(comment.id)" class="border-2 rounded-md border-red-800 bg-red-200 py-1 px-6 hover:bg-red-600 hover:text-white font-bold ml-2">DELETE</button>
                    </div>
                </div>
            </li>
        </ul>

    </div>
</template>

<script>
    export default{
        data(){
            return {
                post_id: this.$route.params.id,
                comments: [],
                post: {},
                fields: {
                    'title': '',
                    'text': ''
                },
                comment_fields: {
                    'text': ''
                },
                comment_id: -1
            }
        },
        props: [
            'token' 
        ],
        created(){
            this.getPost();
        },
        methods: {
            getPost(){
                let url = window.location.origin;
                url += `/api/posts/${this.post_id}`;
                axios.get(url)
                .then(res => {
                    this.post = res.data.post;
                    this.fields.title = res.data.post.title;
                    this.fields.text = res.data.post.text;
                    this.comments = res.data.comments.data;
                })
                .catch(err => console.log(err));
            },
            // getComments(){
            //     let url = window.location.origin;
            //     url += `/api/comments/${this.post_id}`;
            //     axios.get(url)
            //     .then(res => console.log(res))
            //     .catch(err => console.log(err));
            // },
            editPost(){
                let url = window.location.origin;
                url += `/api/posts/${this.post_id}`;
                axios.put(url, {
                    'title': this.fields.title,
                    'text': this.fields.text
                }, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(res => {
                    console.log(res);
                    this.getPost();
                })
                .catch(err => console.log(err));
            },
            deletePost(){
                let url = window.location.origin;
                url += `/api/posts/${this.post_id}`;
                axios.delete(url, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(res => {
                    window.location.href = window.location.origin;
                })
                .catch(err => console.log(err));
            },
            addOrEditComment(){
                let url = window.location.origin;
                if(this.comment_id === -1){
                    url += `/api/comments/${this.post_id}`;
                    axios.post(url, this.comment_fields, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(res => {
                        this.comment_fields.text = '';
                        this.getPost();
                    })
                    .catch(err => console.log(err));
                }
                else{
                    url += `/api/comments/${this.comment_id}`;
                    axios.put(url, this.comment_fields, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(res => {
                        this.comment_id = -1;
                        this.comment_fields.text = '';
                        const button = document.querySelector("#submitButton");
                        button.innerText = "Add Comment";
                        this.getPost();
                    })
                    .catch(err => console.log(err));
                }
            },
            editComment(id, text){
                this.comment_id = id;
                this.comment_fields.text = text;
                const button = document.querySelector("#submitButton");
                button.innerText = "Edit Comment";
            },
            deleteComment(id){
                let url = window.location.origin;
                url += `/api/comments/${id}`;

                axios.delete(url, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(res => {
                    this.getPost();
                })
                .catch(err => console.log(err));
            }
        }
    }
</script>