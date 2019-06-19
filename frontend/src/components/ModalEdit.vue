<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-comment-container" @click.stop>
                <div class="m-header">
                    <div class="modal-close" @click="$emit('close')">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </div>
                </div>
                <small class="d-block">Название фильма:</small>
                <input type="text" class="search_suggest_form_q" v-model="movie.title">
                <small class="mt-2 d-block">Год премьеры:</small>
                <input type="text" class="search_suggest_form_q" v-model="movie.year">
                <small class="mt-2 d-block">Теги:</small>
                <div class="mt-5">
                    <tag v-for="(tag, i) in movie.tags" :tag="tag" :key="tag.id" :title="'удалить тэг'" @select_tag="deleteTag($event, i)"></tag>
                    <new-tag @add_tag="addTag($event)"></new-tag>
                    <!--<span class="add_tag" title="новый тэг" @click="addTag"><i class="fa fa-plus"></i></span>-->
                </div>
                <button v-if="Object.keys(edit).length" class="btn btn-light btn-block w-100 mt-4" @click="editMovie">Редактировать</button>
            </div>
        </div>
    </transition>
</template>

<script>
    import Tag from './Tag.vue';
    import NewTag from './NewTag.vue';
    export default {
        components: {
            Tag,
            NewTag
        },
        props: {
            /**
             * @property number id
             * @property string title,
             * @property number year,
             * @property Array tags
             */
            movie: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                movieData: { ...this.$props.movie },
                id: this.$props.movie.id,
                edit: {}
            };
        },
        watch: {
          'movie.title'(val) {
              if (val !== this.movieData.title) {
                  this.$set(this.edit, 'title', val);
              } else {
                  this.$delete(this.edit, 'title');
              }
          },
          'movie.year'(val) {
              if (val !== this.movieData.year) {
                  this.$set(this.edit, 'year', val);
              } else {
                  this.$delete(this.edit, 'year');
              }
          }
        },
        methods: {
            deleteTag(id, pos) {
                axios.post('/tag/delete/' + this.movie.id + '/' + id)
                  .then(() => {
                    this.movie.tags.splice(pos, 1);
                    if (this.movie.tags.length === 0) {
                      this.$emit('delete_movie');
                      this.$emit('close');
                      toastr.info(`Фильм ${this.movie.title} был удален по причине отстутвия тэгов`);
                    }
                  })
                  .catch(console.error);
            },
            addTag(title) {
                const titles = this.movie.tags.map(tag => tag.title);
                if (title && !titles.includes(title)) {
                  axios.post('/tag/create/' + this.id, { tags: [title] })
                    .then(({ data }) => {
                      this.movie.tags.push(data);
                      console.log(data);
                    })
                    .catch(console.error);
                } else {
                    console.warn('crap!');
                }
            },
            editMovie() {
                axios.post('/movie/edit/' + this.id, this.edit)
                .then(resp => {
                    if (resp.status !== 200) {
                        console.error('whooops!');
                    } else {
                        this.$emit('close');
                        console.log(resp.data);
                    }
                })
                .catch(console.error);
            }
        }
    }
</script>

<style scoped>
    .add_tag {
        margin-left: 10px;
        color: gainsboro;
        font-size: 16px;
        cursor: pointer;
    }
    small {
        float: left;
    }
    .m-header {
        display: flex;
        align-items: flex-start;
        justify-content: flex-end;
        padding: 1rem 1rem;
        border-top-left-radius: 0.3rem;
        border-top-right-radius: 0.3rem;
    }
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        transition: opacity .3s ease;
    }
    .modal-comment-container {
        width: 800px;
        margin: 40px auto 0;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        font-family: Helvetica, Arial, sans-serif;
    }
    .modal-close {
        margin-top: 0;
        float: left;
        cursor: pointer;
    }
    .modal-close i {
        font-size: 24px;
        color: coral;
    }
    .modal-header h4 {
        margin-top: 0;
        color: #42b983;
    }
    .modal-body {
        margin: 20px 0;
    }
    .text-right {
        text-align: right;
    }
    .form-label {
        display: block;
        margin-bottom: 1em;
    }
    .form-label > .form-control {
        margin-top: 0.5em;
    }
    .form-control {
        display: block;
        width: 100%;
        padding: 0.5em 1em;
        line-height: 1.5;
        border: 1px solid #ddd;
    }
    .modal-enter {
        opacity: 0;
    }
    .modal-leave-active {
        opacity: 0;
    }
    .modal-enter .modal-comment-container,
    .modal-leave-active .modal-comment-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
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
        outline: none;
    }
</style>