<template>
  <div>
    <b-nav-item
      v-if="!item.children"
      :key="item.name"
      :to="item.path"
    >
      {{ item.name }}
    </b-nav-item>

    <b-nav-item-dropdown
      v-if="item.children"
      :key="item.name"
      :text="item.name"
      active-class="active"
    >
      <b-dropdown-item
        v-for="child in item.children"
        :key="child.name"
        :to="child.path"
        active-class="active"
      >
        {{ child.name }}
      </b-dropdown-item>
    </b-nav-item-dropdown>
  </div>
</template>

<script>

export default {
  name: "NavItemView",
  props: {
    item: {
      type: Object,
      required: true
    }
  }
}

</script>
