<template>
    <div class="container">
        <div v-for="movie in movies" :key="movie.id">
            <h1>"{{ movie.title }}"</h1>
            <h3>{{ movie.year_release }} г.</h3>
            <h5 v-for="tag in movie.tags" :key="tag.id" class="d-inline-block ml-2">
                {{ tag.title }}
            </h5>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                movies: [],
            };
        },
        methods: {
            fetch() {
                const page = location.href.split('page=')[1] || 1;
                const sort = '&title=DESC';
                axios.get('/movie/all?page=' + page)
                    .then(({ data }) => {
                        this.movies = data.items;
                        console.log(data);
                    });
            }
        },
        created() {
            this.fetch();
        }
    }
</script>

<style>
    h1 {
        text-transform: full-width;
    }
    h5 {
        text-transform: lowercase;
    }
</style>