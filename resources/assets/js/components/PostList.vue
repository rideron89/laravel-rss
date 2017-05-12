<template>
    <div class="panel panel-default">
        <div class="panel-heading">
            Dashboard

            <a href="#" @click="loadPosts"><i class="glyphicon glyphicon-refresh"></i></a>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <h4>Filter by Feed</h4>

                    <p v-if="feeds.length < 1">No feeds</p>

                    <div class="list-group" v-if="feeds.length > 0">
                        <a class="list-group-item" href="#"
                            v-for="feed in feeds"
                            :class="{ 'active': feed.id == selectedFeed }"
                            @click="selectedFeed = (selectedFeed != feed.id) ? feed.id : null">{{ feed.title }} <span class="pull-right badge">{{ feed.post_count }}</span></a>
                    </div>
                </div>

                <div class="col-md-9">
                    <div>
                        Sort by:
                        <a href="#" data-order-by="date_published:desc" @click="orderBy = 'date_published:desc'">Newest first</a>
                        |
                        <a href="#" data-order-by="date_published:asc" @click="orderBy = 'date_published:asc'">Oldest first</a>
                    </div>

                    <div style="height: 15px;"></div>

                    <div class="post-list">
                        <p v-if="posts.length < 1">No posts here!</p>

                        <ul class="list-group" v-if="posts.length > 0" @click="listClick">
                            <li class="list-group-item" v-for="post in posts">
                                <a :href="post.url" v-html="post.title" target="_blank" :data-post-id="post.id"></a>

                                <p v-html="post.description"></p>

                                <p><em>Published on {{ post.date_published }} ({{ post.short_url }})</em></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {},

    data: function() {
        return {
            // specifies which UserFeed to load posts for
            selectedFeed: null,

            // specifies the Post field and direction to load posts
            orderBy: 'date_published:desc',

            // specifies the pagination page to load posts for
            page: 1,

            // contains all of the UserFeeds
            feeds: [],

            // contains all of the Posts
            posts: []
        }
    },

    computed: {
        controls() {
            return {
                selectedFeed: this.selectedFeed,
                orderBy: this.orderBy,
                page: this.page
            }
        }
    },

    /**
    * Once the DOM is ready, load required data for the component.
    */
    mounted() {
        this.loadFeeds();
        this.loadPosts();
    },

    watch: {
        /**
        * Watch the controls() method instead of using updated() so that we can watch specific
        * data items. Otherwise, we might run into an infinite loop trying to load and set posts
        * and/or feeds.
        */
        controls() {
            this.loadPosts();
        }
    },

    methods: {
        /**
        * When the list is clicked, request the server mark the Post as 'read'.
        *
        * @param Event ev
        */
        listClick: function(ev) {
            let post_id = ev.target.dataset.postId;

            if (undefined === post_id) { return; }

            axios({
                method: 'post',
                url: '/api/posts/' + post_id + '/read'
            }).then((response) => {
                this.posts = this.posts.filter((post) => post.id != post_id);
            });
        },

        /**
        *
        */
        loadFeeds: function() {
            axios({
                method: 'get',
                url: '/api/user-feeds'
            }).then((response) => {
                if (response.data && response.data.status === 'ok') {
                    this.feeds = response.data.items;
                } else {
                    this.feeds = [];

                    console.warn('could not load feeds:', response.statusText);
                }
            });
        },

        loadPosts: function() {
            let params = {};

            params.orderBy = this.orderBy;
            params.page = this.page;

            if (this.selectedFeed) {
                params.feed = this.selectedFeed;
            }

            axios({
                method: 'get',
                url: '/api/posts',
                params: params
            }).then((response) => {
                if (response.data && response.data.status === 'ok') {
                    this.posts = response.data.items.data;
                } else {
                    this.posts = [];

                    console.warn('could not load posts:', response.statusText);
                }
            });
        }
    }
}
</script>