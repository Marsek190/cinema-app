<template>
  <div class="users-page">
    <navbar></navbar>
    <h2 class="mb-3 mt-3" style="text-align: center">Добавить фильм</h2>
    <form @submit.prevent="createMovie" class="mb-3">
      <div class="form-group">
        <small v-if="inputErrors.title" id="titleErrors" class="text-danger">
          {{ inputErrors.title }}
        </small>
        <input type="text" class="form-control mb-2 mt-2" :class="{ 'error-field': inputErrors.title }"
          name="title" v-validate="'required|alpha_spaces|max:255'" placeholder="Название" v-model="movie.title">
        <small v-if="inputErrors.year" id="yearErrors" class="text-danger">
          {{ inputErrors.year }}
        </small>
        <input type="text" class="form-control mb-2 mt-2" :class="{ 'error-field': inputErrors.tags }"
          name="year" v-validate="'required|numeric|length:4'" placeholder="Год" v-model.number="movie.year">
        <small v-if="inputErrors.tags" id="tagsErrors" class="text-danger" style="display: block">
          {{ inputErrors.tags }}
        </small>
        <!--<input type="text" class="form-control mb-2 mt-2" :class="{ 'error-field': errors.first('tag') }"
          name="tag" v-validate="'required|max:255'" placeholder="Теги через ," v-model="movie.tags">-->
        <div class="mt-1">
          <tag v-for="(title, i) in movie.tags" :tag="{title, id: i}" :key="i"
            @select_tag="deleteTitle(i)" :title="'удалить тэг'"></tag>
          <new-tag @add_tag="addTitle($event)"></new-tag>
        </div>
      </div>
      <button type="submit" class="btn btn-light btn-block">Сохранить</button>
    </form>
    <button @click="clearForm" class="btn btn-danger btn-block">Сброс</button>
    <input type="text" class="search_suggest_form_q mt-4" placeholder="Поиск по названию фильма" v-model="search">
    <!--<FiltersDropdown class="mt-3 mb-2" :keys="['initials', 'birthday', 'city', 'created_at']" @change-filter="sort($event)"></FiltersDropdown>-->
    <div v-if="Object.keys(filter).length > 0" class="d-inline-block mt-2">
      <span class="space_gray">Выбранные вами фильтры:</span>
      <FilterLabel v-for="(key, i) in Object.keys(filter)"
        :title="key" :key="i" @remove="removeFilter($event)"></FilterLabel>
    </div>
    <br>
    <div v-if="tags.length > 0" class="d-inline-block mt-2">
      <span class="space_gray">Выбранные вами теги:</span>
      <FilterLabel v-for="(title, i) in tags"
        :title="title" :key="i" @remove="removeTag($event)"></FilterLabel>
    </div>
    <div class="card card-body mb-2 mt-3" v-for="(movie, pos) in movies" :key="movie.id">
      <span class="filter-tag m-auto space_gray" @click="addFilter('title')" title="">Название</span><h3> {{ movie.title }} </h3>
      <span class="filter-tag m-auto space_gray" @click="addFilter('year')" title="">Год премьеры</span><p class="mb-2"> {{ movie.year }} г.</p>
      <span class="space_gray">Жанр</span>
      <div class="mt-2">
        <tag v-for="tag in movie.tags" :tag="tag" :key="tag.id" :title="'выбрать тэг'" @click.prevent="addTag(tag.title)"></tag>
      </div>
      <hr>
      <button @click="open(movie.id)" class="btn btn-warning mb-2">Редактировать</button>
      <button @click="deleteMovie(pos, movie.id)" class="btn btn-danger">Удалить</button>
      <modal-edit v-if="edit && edit_id === movie.id"
        :movie.sync="movie" @close="edit = false"></modal-edit>
    </div>
    <div v-show="download">
      <Pagination :page="page" :total-pages="totalPages" :per-page="perPage" @page="getAllMovies($event)"></Pagination>
    </div>
  </div>
</template>

<script>
import Navbar from "./Navbar.vue";
import ModalEdit from "./ModalEdit.vue";
import Pagination from "./Pagination.vue";
import FilterLabel from "./FilterLabel.vue";
import * as _ from "lodash";
import Tag from './Tag.vue';
import NewTag from './NewTag.vue';

export default {
  components: {
    'navbar': Navbar,
    'modal-edit': ModalEdit,
    Pagination,
    FilterLabel,
    Tag, NewTag
  },
  data() {
    return {
      movies: [],
      inputErrors: {},
      movie: {
        title: "",
        year: null,
        tags: []
      },
      oldInput: [],
      edit: false,
      edit_id: null,
      totalPages: 1,
      perPage: 5,
      page: 1,
      download: false,
      filter: {},
      search: '',
      tags: []
    };
  },
  watch: {
    search(newVal, oldVal) {
      if (newVal.length >= 3 || oldVal.length > 0) {
        this.searchByInitials();
      }
    }
  },
  methods: {
    addTitle(title) {
      if (!this.movie.tags.includes(title)) {
        this.movie.tags.push(title);
      }
    },
    deleteTitle(pos) {
      this.movie.tags.splice(pos, 1);
    },
    sort(target) {
      console.log('sort by field: ' + target);
    },
    addFilter(target) {
      if (target in this.filter) {
            this.filter[target] = (this.filter[target] === 'DESC') ? 'ASC' : 'DESC';
      } else {
          this.filter = {};
          this.filter[target] = 'ASC';
      }
      console.log(this.filter);
      this.getAllMovies();
    },
    addTag(title) {
      if (!this.tags.includes(title)) {
        this.tags.push(title);
        this.getAllMovies();
      }
    },
    removeTag(title) {
      let pos = this.tags.indexOf(title);
      this.tags.splice(pos, 1);
      this.getAllMovies();
    },
    removeFilter(target) {
      this.$delete(this.filter, target);
      console.log(this.filter);
      this.getAllMovies();
    },
    open(id) {
      this.edit = true;
      this.edit_id = id;
    },
    clearForm() {
      this.movie = {
        title: "",
        year: "",
        tags: ""
      };
      this.inputErrors = {};
    },
    query() {
      let query = '';
      for (const [key, value] of Object.entries(this.filter)) {
        query += `&${key}=${value}`;
      }
      return query;
    },
    titles() {
      return this.tags.length ? '&tag=' + ','.concat(this.tags).slice(1) : '';
    },
    searchMovie() {
      return '&search=' + this.search;
    },
    getAllMovies(move = this.page || 1) {
      this.download = false;
      const page = window.location.search.split('page=')[1] || 1;
      this.movies.splice(0);
      axios.get("/movie/all?page=" + move + this.query() + this.searchMovie() + this.titles())
        .then(({ data }) => {
          const {
            total_count,
            current_page_number,
            num_items_per_page,
            items } = data;
          this.movies = items;
          if (current_page_number > 0) {
            this.totalPages = total_count / num_items_per_page;
            this.perPage = num_items_per_page;
          }
          //console.log(data);
          //console.log(move);
        })
        .catch(console.warn)
        .finally(() => (this.download = true));
    },
    deleteMovie(pos, id) {
      axios.post('/movie/delete/'+ id)
      .then(() => {
        this.movies.splice(pos, 1);
        console.log('delete user with id = '+ id);
      }).catch(console.log);
    },
    createMovie() {
      axios.post("/movie/create", this.movie)
        .then(({ data }) => {
          if (data.errors) { //вспоминая творчество этой парочки
            this.inputErrors = data.errors;
            //this.oldInput = data.old_input;
          } else {
            //this.movies.push(data);

            if (this.oldInput.length) {
              this.oldInput.splice(0);
            }
            this.getAllMovies();
            this.clearForm();
          }
          console.log(data);
        })
        .catch(console.log);
    }
  },

  created() {
    console.log("run");
    this.searchByInitials = _.debounce(this.getAllMovies, 500);
    this.getAllMovies();
  }
};
</script>


<style scoped>
  small {
    float: left;
  }
  .error-field {
    border-color: red;
  }
  h3 {
    text-transform: full-width;
  }
  h5 {
    text-transform: lowercase;
    cursor: pointer;
  }
  h5:hover {
    color: orangered;
  }
  .users-page {
    width: 800px;
    margin: auto;
  }
  .space_gray {
    font-size: 14px;
    color: darkgray;
  }
  .filter-tag {
    cursor: pointer;
  }
  .filter-tag:hover {
    color: #000;
  }
  .search_suggest_form_q {
    border: 0;
    color: #34495e;
    font-size: 15px;
    line-height: 20px;
    padding: 8px 10px;
    padding-left: 40px;
    margin: 0;
    width: 100%;
    background-color: #eeeeee;
    background-image: url(https://dr.habracdn.net/mk/assets/search.input-32f03d5c8a273993216158fb48092e02.png);
    background-repeat: no-repeat;
    background-position: 10px 10px;
    outline: none;
  }
</style>
