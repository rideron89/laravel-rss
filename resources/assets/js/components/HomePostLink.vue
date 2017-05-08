<template>
    <a :href="href" :target="target" @click="click">{{ text }}</a>
</template>

<script>
    export default {
        props: {
            href: {
                required: true,
                type: String
            },

            postId: {
                require: true,
                type: String
            },

            target: {
                default: '_self',
                type: String
            },

            text: {
                require: true,
                type: String
            }
        },

        computed: {
            computedPostId: function() {
                return this.postId;
            }
        },

        mounted() {
        },

        methods: {
            click: function(ev) {
                if (window.User === undefined) {
                    return true;
                }

                axios({
                    method: 'post',
                    url: '/api/post/' + this.computedPostId + '/read'
                }).then((response) => {
                    if (response.data.read) {
                        var $item = this.$el;

                        // find the closest parent node with .list-group-item
                        while ($item.className.indexOf('list-group-item') === -1) {
                            $item = $item.parentNode;
                        }

                        // if the .list-group-item was found, hide it
                        if ($item.className.indexOf('list-group-item') !== -1) {
                            $item.style.display = 'none';
                        }
                    }
                });
            }
        }
    }
</script>
