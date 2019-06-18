<template>
    <div class="tag_frame">
        <input type="text" placeholder="...ещё тэг" v-model="title">
        <span class="add_tag" title="новый тэг" @click="addTagEvent"><i class="fa fa-plus"></i></span>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                title: ''
            };
        },
        methods: {
            addTagEvent() {
                if (this.title) {
                    this.$emit('add_tag', this.title);
                    this.title = '';
                }
            }
        }
    }
</script>

<style scoped>
    .tag_frame {
        min-width: 30px;
        background-color: darkorange;
        border-radius: 3px;
        display: inline;
        padding: 5px 5px;
        margin-left: 7px;
    }
    input {
        width: 80px;
        background-color: darkorange;
        color: #fff;
        border: 0;
        outline: none;
    }
    input::-webkit-input-placeholder {
        color: #fff;
    }
    input::-moz-placeholder {
        color: #fff;
    }
    .add_tag {
        color: #fff;
        font-size: 16px;
        cursor: pointer;
    }
</style>