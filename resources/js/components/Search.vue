<template>
  <div>
    <v-text-field
      @keyup.enter="searchit"
      v-model="search"
      placeholder="Search"
      prepend-inner-icon="mdi-magnify"
      filled
      
      dense

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
    searchit(){
      console.log("prop search" + this.search);
      this.$store.dispatch("search", { search: this.search });
    }
  },
};
</script>
