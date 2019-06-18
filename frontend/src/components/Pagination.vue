<template>
    <div class="ibox-footer mb-4">
        <ul class="pagination flex" v-if="totalPages > 1">
            <li class="page-item" v-if="curr > 1">
                <span class="page-link btn" href="#" aria-label="Previous" @click.prevent="moveTo(--curr)"><span aria-hidden="true"><<</span></span>
            </li>
            <li class="page-item" v-for="n in getPages" :key="n" :class="{ 'active': n == curr }">
                <span class="page-link btn" href="#" @click.prevent="moveTo(n)">{{ n }}</span>
            </li>
            <li class="page-item" v-if="perPage < totalPages">
                <span class="page-link btn" href="#" aria-label="Next" @click.prevent="moveTo(++curr)"><span aria-hidden="true">>></span></span>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        page: {
            type: Number,
            required: true
        },
        totalPages: {
            type: Number,
            required: true
        },
        perPage: {
            type: Number,
            required: true
        }
    },
    data() {
      return {
          curr: this.page
      };
    },
    computed: {
        getPages() {
            if (this.perPage === -1) {
                return 0;
            }
            if (this.perPage === 0) {
                return this.totalPages;
            }
            let start = this.curr - this.perPage,
                end   = this.curr + this.perPage + 1,
                pages = [],
                index;
            start = start < 1 ? 1 : start;
            end   = end >= this.totalPages ? this.totalPages + 1 : end;
            for (index = start; index < end; index++) {
                pages.push(index);
            }
            return pages;
        }
    },
    methods: {
        selectPage(page) {
            this.$emit('pagination-change-page', page);
        },
        moveTo(page) {
            this.$emit('page', page);
            console.log('move to page' + page);
            this.curr = page;
            //this.$nextTick(() => (this.curr = page));
        }
    }

}
</script>

<style>
    .pagination{
        list-style: none;
        margin: 0;
        padding: 0;
    }
    .pagination li{
        margin: 0 3px;
        cursor: pointer;
    }
    .pagination li:first-child{
        margin-left: 0;
    }
    .pagination .page-item.active span {
        font-weight: bold;
    }
</style>