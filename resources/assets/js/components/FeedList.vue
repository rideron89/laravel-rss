<template>
<div class="panel panel-default">
    <div class="panel-heading">
        Dashboard
    </div>

    <div class="panel-body">
        <div>
            <form @submit="addFeed">
                <h4>Add A New Field</h4>

                <div style="height: 10px;"></div>

                <div class="row">
                    <div class="col-md-4">
                        <label for="title">Label:</label>

                        <input type="text" class="form-control" name="title" id="title" v-model="newFeed.title" />
                    </div>

                    <div class="col-md-8">
                        <label for="url">URL:</label>

                        <input class="form-control" type="text" name="url" id="url" v-model="newFeed.url" />
                    </div>
                </div>

                <div style="height: 15px;"></div>

                <fieldset>
                    <button class="btn btn-sm btn-success" type="submit"><i class="glyphicon glyphicon-plus"></i> Add</button>
                </fieldset>
            </form>
        </div>

        <hr />

        <p v-if="userFeeds.length < 1">No feeds.</p>

        <ul class="list-group" v-if="userFeeds.length > 0">
            <li class="list-group-item" v-for="userFeed in userFeeds">
                <form @submit="updateFeed($event, userFeed)">
                    <div class="row">
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="title" :value="userFeed.title" />
                        </div>

                        <div class="hidden-md" style="height: 10px;"></div>

                        <div class="col-md-8">
                            <input class="form-control" type="text" name="url" disabled="disabled" :value="userFeed.feed.url" />
                        </div>
                    </div>

                    <div style="height: 10px;"></div>

                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-default" type="submit"><i class="glyphicon glyphicon-floppy-disk"></i> Update</button>

                            <button class="btn btn-sm btn-danger" type="button" @click="removeFeed(userFeed.id)"><i class="glyphicon glyphicon-trash"></i> Remove</button>
                        </div>
                    </div>
                </form>
            </li>
        </ul>
    </div>
</div>
</template>

<script>
    export default {
        props: {},

        data: function() {
            return {
                // holds the object values for the feed the user is trying to add
                newFeed: {},

                // holds the list of UserFeed objects
                userFeeds: []
            }
        },

        mounted() {
            this.loadFeeds();
        },
        updated() {},

        methods: {
            addFeed: function(ev) {
                let params = new URLSearchParams();

                ev.preventDefault();

                Object.keys(this.newFeed).forEach((key, index) => {
                    params.append(key, this.newFeed[key]);
                });

                axios({
                    method: 'post',
                    url: '/api/user-feeds',
                    params: params
                }).then((response) => {
                    if (response.data && response.data.status == 'ok') {
                        this.loadFeeds();

                        this.newFeed = {};
                    } else {
                        console.warn('could not add feed:', response.statusText);
                    }
                });
            },

            updateFeed: function(ev, userFeed) {
                let $titleInput = ev.currentTarget.querySelector('input[name="title"]');
                let params = new URLSearchParams();

                ev.preventDefault();

                userFeed.title = $titleInput.value;

                Object.keys(userFeed).forEach((key) => {
                    params.append(key, userFeed[key]);
                });

                axios({
                    method: 'put',
                    url: '/api/user-feeds/' + userFeed.id,
                    params: params
                }).then((response) => {
                    if (response.data && response.data.status !== 'ok') {
                        console.warn('error updating feed:', response.statusText);
                    }
                });
            },

            removeFeed: function(userFeedId) {
                axios({
                    method: 'delete',
                    url: '/api/user-feeds/' + userFeedId
                }).then((response) => {
                    this.userFeeds = this.userFeeds.filter((userFeed) => userFeed.id != userFeedId);

                    console.log(response);
                });
            },

            loadFeeds: function() {
                axios({
                    method: 'get',
                    url: '/api/user-feeds'
                }).then((response) => {
                    if (response.data && response.data.status === 'ok') {
                        this.userFeeds = response.data.items;
                    } else {
                        this.userFeeds = [];

                        console.warn('could not load userFeeds:', response.statusText);
                    }
                });
            }
        }
    }
</script>