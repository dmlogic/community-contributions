<script>
export default {
  data() {
    return {
      show: this.$page.props.status,
    }
  }
}
</script>

<template>
    <div v-if="show" class="items-center w-full mb-4 text-green-600">
        {{ $page.props.status }}
    </div>
</template>


