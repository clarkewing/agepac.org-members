<template>
    <div>
        <div v-for="(post, index) in items" :key="post.id">
            <post :post="post" @deleted="remove(index)"></post>
        </div>

        <pagination :data="dataSet" @pagination-change-page="fetch"></pagination>

        <div class="d-flex text-danger px-5 py-3 rounded border-placeholder border-danger align-items-center" v-if="$parent.locked">
            <svg class="bi bi-lock-fill mr-2" width="1.5em" height="1.5em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <rect width="11" height="9" x="2.5" y="7" rx="2"/>
                <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 117 0v3h-1V4a2.5 2.5 0 00-5 0v3h-1V4z" clip-rule="evenodd"/>
            </svg>
            <p class="mb-0">Cette discussion a été vérouillée. Il n'est plus possible d'y répondre.</p>
        </div>

        <div class="d-flex text-danger px-5 py-3 rounded border-placeholder border-danger align-items-center" v-else-if="! signedIn">
            <svg class="bi bi-exclamation-triangle-fill mr-2" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 00-.9.995l.35 3.507a.552.552 0 001.1 0l.35-3.507A.905.905 0 008 5zm.002 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
            </svg>
            <p class="mb-0">Seuls les membres peuvent participer aux discussions.</p>
        </div>

        <div class="d-flex text-danger px-5 py-3 rounded border-placeholder border-danger align-items-center" v-else-if="! authorize('isVerified')">
            <svg class="bi bi-exclamation-triangle-fill mr-2" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M8.982 1.566a1.13 1.13 0 00-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5a.905.905 0 00-.9.995l.35 3.507a.552.552 0 001.1 0l.35-3.507A.905.905 0 008 5zm.002 6a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
            </svg>
            <p class="mb-0">Tu dois <a href="/email/verify">vérifier ton adresse email</a> avant de pouvoir participer aux discussions.</p>
        </div>

        <new-post @created="add" v-else></new-post>
    </div>
</template>

<script>
    import Post from './Post.vue';
    import NewPost from './NewPost.vue';
    import Pagination from './Pagination';
    import collection from '../mixins/collection';

    export default {
        components: { Post, NewPost, Pagination },

        mixins: [collection],

        data() {
            return { dataSet: {} }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page)).then(this.refresh);

                if (page) {
                    history.pushState(null, null, '?page=' + page);
                    window.scrollTo(0, 0);
                }
            },

            url(page) {
                if (! page) {
                    let query = location.search.match(/page=(\d+)/);

                    page = query ? query[1] : 1;
                }
                return `${location.pathname}/posts?page=${page}`;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;
            }
        }
    }
</script>
