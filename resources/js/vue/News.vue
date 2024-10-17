<template>
    <div class="wrapper">
        <h1>News</h1>
        <form @submit.prevent.stop="search">
            <input v-model="query" placeholder="Search..."/>
        </form>
        <div ref="feed" class="news">
            <div class="news-item" v-for="n in news">
                <div class="title">{{ n.title }}</div>
                <div class="description" v-html="n.description"></div>
                <div class="info">
                    <a :href="n.link">Read more</a>
                    <p>{{ n.published_at }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import Pusher from "pusher-js";

export default {
    components: {},
    computed: {},
    props: {},
    data() {
        return {
            query: '',
            count: 10,
            news: [],
            needMore: true,
            inProgress: false,
        }
    },
    created() {
        this.subscribe();
    },
    beforeMount() {
        this.getNextNews();
    },
    mounted() {
        window.addEventListener("scroll", this.handleScroll);

    },
    unmounted() {
        window.removeEventListener("scroll", this.handleScroll);
    },
    methods: {
        subscribe() {
            let pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                authEndpoint: '/broadcasting/auth'
            })
            pusher.subscribe('private-base')
            pusher.bind('user.registered', data => {
                alert('New user with phone ' + data.phone + ' registered');
            });
            pusher.bind('user.login', data => {
                alert('New user with phone ' + data.phone + ' logged in');
            });
            pusher.bind('news.updated', data => {
                alert('New news');
                this.getNextNews(true);
            });
        },
        handleScroll() {
            if (this.$refs.feed.getBoundingClientRect().bottom - 200 < window.innerHeight) {
                if (this.needMore && this.news.length > 0 && this.news.length % this.count === 0) {
                    this.getNextNews();
                }
            }
        },
        search() {
            this.getNextNews(null, true);
        },
        getNextNews(before, refresh) {
            if (this.inProgress)
                return

            let after = '';

            if (before === true && refresh !== true) {
                before = this.news.length > 0 ? this.news[0].published_at : '2000-00-00T00:00:00Z';
            } else {
                before = '';
                if (this.news.length > 0 && refresh !== true)
                    after = this.news[this.news.length - 1].published_at;
            }

            this.inProgress = true;

            axios.get('/news?after=' + after + '&count=' + this.count + '&query=' + this.query + '&before=' + before)
                .then(response => {
                        if (refresh === true) {
                            this.needMore = true;
                            this.news = response.data;
                        } else if (before) {
                            this.news.unshift(...response.data);
                        } else {
                            this.news.push(...response.data);
                        }

                        if (response.data.length < this.count) {
                            this.needMore = false;
                        }

                        this.inProgress = false;
                    }
                )
                .catch((e) => {
                    alert(e);
                })
        }
    }
}
</script>

<style scoped>
.wrapper {
    width: 100%;
    max-width: 700px;
    padding: 0 20px;

    h1 {
        font-size: 30px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 30px;
    }

    form {
        margin-bottom: 30px;

        input {
            border-radius: 5px;
            width: 100%;
            height: 30px;
            appearance: none;
            border: 1px solid rgba(0, 0, 0, .5);
            outline: none;

            &:focus {
                outline: 0;
                box-shadow: none;
                border: 1px solid rgba(0, 0, 0, .5);
            }
        }
    }

    .news {
        display: flex;
        flex-direction: column;
        gap: 30px;
        margin-bottom: 100px;

        .news-item {
            .title {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 15px;
            }

            .description {
                font-size: 16px;
                margin-bottom: 10px;
            }

            .info {
                display: flex;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                width: 100%;
                font-size: 14px;

                a {
                    color: grey;
                }

                p {
                    font-weight: 700;
                }
            }
        }
    }
}
</style>
