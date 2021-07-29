<template>
  <div>
    <v-text-field
    ></v-text-field>
  </div>
</template>
<script>
export default {
  name: "Search",

  data() {
    return {
      search: ""
    };
  },

  methods: {
    searchit() {
      this.$store.dispatch('searchUser',{q:this.search})
    }
  }
};
</script>
