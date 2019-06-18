<template>
    <div class="d-inline-block tag ml-1">
        {{ title }}
        <span class="" @click="remove"></span>
    </div>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                required: true
            }
        },
        methods: {
            remove() {
                console.log('remove tag');
                this.$emit('remove', this.title);
            }
        }
    }
</script>

<style scoped>
    .tag {
        padding: 2px 3px;
        font-size: 15px;
        color: #999999;
        line-height: 17px;
        display: inline-block;
        margin-right: 5px;
    }
    .tag span {
        display: inline-block;
        background: url(https://dr.habracdn.net/mk/assets/skill.remove-2f00eb83343074dc1db586a9d2c029b2.png) no-repeat center center;
        width: 12px;
        height: 12px;
        vertical-align: middle;
        cursor: pointer;
        margin-left: 5px;
    }
</style>