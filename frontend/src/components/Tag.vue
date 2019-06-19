<template>
    <div class="tag_frame mt-2" :title="title" @click.prevent="$emit('select_tag', tag.id)">
        <span>{{ tag.title }}</span>
    </div>
</template>

<script>
    export default {
        props: {
            tag: {
                type: Object,
                required: true
            },
            title: {
                type: String,
                required: false,
                default: ''
            }
        }
    }
</script>

<style scoped>
    .tag_frame {
        min-width: 60px;
        background-color: grey;
        border-radius: 3px;
        display: inline-table;
        padding: 5px 5px;
        margin-left: 7px;
        cursor: pointer;
    }
    .tag_frame:hover {
        background-color: coral;
    }
    span {
        color: #fff;
    }
</style>