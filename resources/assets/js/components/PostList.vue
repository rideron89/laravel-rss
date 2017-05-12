<template>
    <div>
        <div class="rpanel">
            <div class="rpanel-sidebar">
                <div class="rpanel-sidebar-links">
                    <a class="rpanel-sidebar-links-item first" href="#"
                        :class="{ 'active': selectedFeed == null }"
                        @click="selectedFeed = null"><i class="glyphicon glyphicon-home"></i> All Feeds</a>

                    <a class="rpanel-sidebar-links-item" href="#"
                        :class="{ 'active': feed.id == selectedFeed }"
                        v-for="feed in feeds"
                        @click="selectedFeed = (selectedFeed != feed.id) ? feed.id : null">
                        {{ feed.title }}
                        <span class="rbadge">{{ feed.post_count }}</span>
                    </a>
                    </li>
                </div>
            </div>

            <div class="rpanel-content">
                <div class="rpanel-content-metabar">
                    <div class="rpanel-content-metabar-sorting">
                        <a class="rpanel-content-metabar-sorting-link" href="#" data-order-by="date_published:desc"
                            :class="{ 'active': orderBy != 'date_published:asc' }"
                            @click="orderBy = 'date_published:desc'">Newest first</a>

                        <a class="rpanel-content-metabar-sorting-link" href="#" data-order-by="date_published:asc"
                            :class="{ 'active': orderBy == 'date_published:asc' }"
                            @click="orderBy = 'date_published:asc'">Oldest first</a>
                    </div>

                    <div class="rpanel-content-metabar-search">
                    </div>
                </div>

                <div class="rpanel-content-list">
                    <div class="rpanel-content-list-item" v-for="post in posts">
                        <a class="rpanel-content-list-item-link" target="_blank" v-html="post.title" :href="post.url"></a>

                        <p v-html="post.description">{{ post.description }}</p>

                        <div class="rpanel-content-list-item-extra">
                            <span class="feed">{{ post.feed.user_feed[0].title }}</span>
                            <span class="date">{{ post.date_published }}</span>
                        </div>
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

            searchQuery: '',

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

        loadPosts: function(ev) {
            let params = {};

            if (ev && ev.preventDefault) {
                ev.preventDefault();
            }

            params.orderBy = this.orderBy;
            params.page = this.page;

            if (this.selectedFeed) {
                params.feed = this.selectedFeed;
            }

            if (this.searchQuery) {
                params.search = this.searchQuery;
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