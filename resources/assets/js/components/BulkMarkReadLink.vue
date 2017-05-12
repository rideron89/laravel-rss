<template>
    <a :href="href" @click="click">{{ text }}</a>
</template>

<script>
    export default {
        props: {
            href: {
                required: true,
                type: String
            },

            text: {
                required: true,
                type: String
            },

            time: {
                required: true,
                type: String
            }
        },

        methods: {
            click: function(ev) {
                ev.preventDefault();

                var params = new URLSearchParams();

                params.append('time', this.time);

                axios({
                    method: 'post',
                    url: this.href,
                    data: params
                }).then((response) => {
                    console.log(response);
                    return;

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
