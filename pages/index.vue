<template>
  <div>
    <div>
      <article-list v-for="article in articles" :key="article.slug" :article="article" />
    </div>
  </div>
</template>

<script>
import ArticleList from './components/-article'

export default {
  components: {
    ArticleList
  },
  watchQuery: true,
  async asyncData ({ $content, route }) {
    const q = route.query.q

    let query = $content('cpp')
      .sortBy('date', 'desc')

    if (q) {
      query = query.search(q)
      // OR query = query.search('title', q)
    }

    const articles = await query.fetch()

    return {
      q,
      articles
    }
  },
  watch: {
    q () {
      this.$router.replace({ query: this.q ? { q: this.q } : undefined }).catch(() => { })
    }
  },
  head () {
    return {
      title: 'CodeCry.com'
    }
  }
}
</script>
