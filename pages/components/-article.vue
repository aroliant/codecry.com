<template>
  <article>
    <div>
      <div>
        <p>
          <span v-for="tag of article.tags" :key="tag">{{ tag }}</span>
        </p>
        <nuxt-link :to="`/cpp/${article.slug}`" class="block">
          <h3>{{ article.title }}</h3>
          <p>{{ article.description }}</p>
        </nuxt-link>
      </div>
      <div>
        <div>
          <p>
            <a :href="article.authors[0].link" target="_blank">{{ article.authors[0].name }}</a>
          </p>
          <div>
            <time datetime="2020-05-23">{{ $moment(article.date).format('LL') }}</time>
            <span>&middot;</span>
            <span>{{ readingTime }} min read</span>
          </div>
        </div>
      </div>
    </div>
  </article>
</template>

<script>
export default {
  props: {
    article: {
      type: Object,
      required: true
    }
  },
  computed: {
    readingTime () {
      return Math.ceil(
        this.$moment.duration(this.article.readingTime).asMinutes()
      )
    }
  }
}
</script>

<style lang="scss" scoped>
</style>
