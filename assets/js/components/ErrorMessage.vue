<template>
  <div
    class="alert alert-danger"
    role="alert"
  >
    {{ error.response.data.error }}
  </div>
</template>

<script>
export default {
  name: "ErrorMessageView",
  props: {
    error: {
      type: Error,
      required: true
    }
  },
}
</script>