<template>
  <div class="container">
    <form method="post" @submit.prevent="create">
      <input type="text" name="title" data-vv-validate-on="none" v-validate="'required|alpha_spaces|max:255'"
         v-model="movie.title" placeholder="Title">
      <input type="text" name="year" data-vv-validate-on="none" v-validate="'required|numeric|length:4'"
         v-model.number="movie.year" placeholder="Year">
      <input type="text" name="tag" data-vv-validate-on="none" v-validate="'required|max:255'"
        v-model="movie.tags" placeholder="Tags">
      <ul>
        <li v-for="error in errors.all()">{{ error }}</li>
      </ul>
      <input type="submit" class="btn btn-success">
    </form>
  </div>
</template>

<script>
  import { ValidationProvider } from 'vee-validate';

  export default {
    name: 'Creator',
    components: {
      'validation-provider': ValidationProvider
    },
    data () {
      return {
        movie: {
          title: "",
          year: "",
          tags: ""
        }
      };
    },
    methods: {
      create() {
        this.$validator.validate().then(valid => {
          if (valid) {
            axios.post('/movie/create', this.movie)
              .then(({ data }) => (console.log(data)))
              .catch(console.error);
            this.movie = {};
          }
        });
      }
    },
    created() {
      //this.$validator.localize('ru');
    }
  }
</script>

<style scoped>
  h1, h2 {
    font-weight: normal;
  }
  ul {
    list-style-type: none;
    padding: 0;
  }
  li {
    display: inline-block;
    margin: 0 10px;
  }
  a {
    color: #42b983;
  }
</style>
