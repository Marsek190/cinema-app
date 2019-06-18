<template>
    <div class="dropdown_inline">
        <div class="toggle mb-2" @click="slide">Сортировать по соответствию <span class="fa fa-angle-down ml-2"></span></div>
        <div v-show="show" class="popup">
            <div class="radio_list">
                <div v-for="(sorter, i) in list" :key="i" class="radio_single">
                    <input type="radio" name="sort" :id="'sort_' + sorter.id" :value="sorter.id"
                        v-model="sortBy" @change="swipe(sortBy)">
                    <label class="label__radio" :for="'sort_' + sorter.id">{{ sorter.value }}</label>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            keys: {
                type: Array,
                required: true,
            }
        },
        data() {
            return {
                list: [
                    {id: 'initials', value: 'по инициалам'},
                    {id: 'birthday', value: 'по дате рождения'},
                    {id: 'city', value: 'по городу'},
                    {id: 'created_at', value: 'по времени создания'},
                ],
                show: false,
                sortBy: 'initials',
            };
        },
        methods: {
            slide() {
                this.show = !this.show;
            },
            swipe(target) {
                this.$emit('change-filter', target);
            },
            close() {
                this.show = false;
            }
        },
        mounted() {
            window.addEventListener('click', e => {
                if (!(e.target.parentNode.classList.contains('dropdown_inline') ||
                    e.target.parentNode.classList.contains('label__radio'))){
                    this.close();
                }
            }, false);
        }
    }
</script>

<style scoped>
    .radio_list .radio_single input[type="radio"] {
        display: none;
    }
    input[type="checkbox"], input[type="radio"] {
        box-sizing: border-box;
        padding: 0;
    }
    input {
        line-height: normal;
        font-family: inherit;
        font-size: 100%;
        margin: 0;
    }
    .radio_list .radio_single label {
        color: #464646;
        font-size: 15px;
        position: relative;
        line-height: 19px;
        display: inline-block;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        white-space: nowrap;
        cursor: pointer;
    }
    .toggle {
        font-family: "PT Sans", sans-serif;
        font-weight: bold;
        color: #000;
        cursor: pointer;
        font-size: 14px;
    }
    .toggle span {
        font-size: 18px;
    }
    .toggle:hover {
        color: #999999;
    }
    .radio_list {
        margin: 14px;
        min-width: 200px;
    }
    .icon-arrow {
        display: inline-block;
        vertical-align: middle;
        margin-left: 10px;
        font-size: 0.8em;
        speak: none;
        font-style: normal;
        font-weight: normal;
        font-variant: normal;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
    }
    .radio_list .radio_single input:checked + label:before {
        background-color: #65c178;
        box-shadow: inset 0px 0px 0px 3px #65c178, inset 0px 0px 0px 7px #fff;
    }
    .radio_list .radio_single label:before {
        background-color: #fff;
        content: "";
        width: 19px;
        height: 19px;
        border-radius: 19px;
        margin-right: 10px;
        display: inline-block;
        vertical-align: text-bottom;
        box-shadow: inset 0px 0px 0px 3px #d8d8d8;
    }
    .popup {
        position: relative;
        text-align: left;
        padding: 0;
        background: #fff;
        border-radius: 0px;
        overflow: hidden;
        box-shadow: 0px 3px 10px 0 rgba(174,174,174,0.5);
    }
</style>