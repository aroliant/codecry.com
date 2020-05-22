<template>
  <div>

    <div>
      <div>
        <h2>{{ article.title }}</h2>
        <p>{{ article.description }}</p>
        <div>
          <time datetime="2020-05-23">{{ $moment(article.date).format('LL') }}</time>
          <span>&middot;</span>
          <span>{{ readingTime }} min read</span>
        </div>
      </div>

      <div>

        <div>

          <div>
            <nuxt-content :document="article" />
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
export default {
  async asyncData ({ $content, params, error }) {
    let article
    try {
      article = await $content(`${params.lang}/${params.title}`).fetch()
      // OR const article = await $content(`articles/${params.slug}`).fetch()
    } catch (e) {
      error({ message: 'Article not found' })
    }

    return {
      article
    }
  },
  computed: {
    readingTime () {
      return Math.ceil(this.$moment.duration(this.article.readingTime).asMinutes())
    }
  },
  head () {
    return {
      title: this.article.title
    }
  }
}
</script>

<style lang="scss">

</style>
