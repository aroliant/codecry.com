<template>
  <div>
    <main>
      <nuxt />
    </main>
  </div>
</template>

<script>
export default {
  head () {
    return {
      title: '',
      titleTemplate: (chunk) => {
        if (chunk) {
          return `${chunk} - CodeCry.com`
        }

        return 'CodeCry.com'
      },
      bodyAttrs: {
        class: [' ']
      }
    }
  }
}
</script>
