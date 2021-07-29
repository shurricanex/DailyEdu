<template>
  <div>
    <SideBar />
    <v-app-bar elevation="0" color="white">
      <div class="my-breadcrumb flex-center">
        <!-- <v-breadcrumbs :items="items" large></v-breadcrumbs> -->
      </div>

      <Search :search="search" class="mt-6" />

      <v-spacer></v-spacer>
      
      <v-btn class="mr-2 my-btn text-decoration-none" elevation="0" color="#EFEFEF" fab to="/messages" >
        <v-icon>mdi-email-outline</v-icon>
      </v-btn>

      <v-btn elevation="0" color="#EFEFEF" class="mr-2 my-btn" fab>
        <v-icon>mdi-bell-outline</v-icon>
      </v-btn >
      <v-menu open-on-hover bottom offset-y >
        <template v-slot:activator="{ on }">
          <v-avatar>
            <img :src="`storage/images/avatar_images/${getUserAvatar}`" v-on="on" />
          </v-avatar>
        </template>

        <v-list >
          <v-list-item :to="{name:'Logout'}" class="text-decoration-none">
            <v-list-item-title>Log Out</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
  </div>
</template>

<script>
import SideBar from "../../components/admin/SideBar";
import Search from "../../components/Search";
export default {
  components: {
    SideBar,
    Search
  },
  data: () => ({
    search: "",
    users: [],
    items: [
      {
        text: "Dashboard",
        disabled: false,
        href: "breadcrumbs_dashboard"
      },
      {
        text: "Link 1",
        disabled: false,
        href: "breadcrumbs_link_1"
      },
      {
        text: "Link 2",
        disabled: true,
        href: "breadcrumbs_link_2"
      }
    ]
  }),
  methods: {},
  computed: {
    getUserName() {
      return this.$store.getters.getUserName;
    },
    getUserType() {
      return this.$store.getters.getUserType;
    },
    getUserAvatar() {
      return this.$store.getters.getUserAvatar;
    }
  },
  created() {}
};
</script>

<style lang="css" scoped>
.my-breadcrumb {
  /* background-color: rgb(240, 240, 240); */
  border-radius: 0.5em;
  height: 70%;
  width: 30%;
  margin-left: 6em;
  margin-right: 2em;
}
.my-btn {
  height: 3.4em !important;
  width: 3.4em !important;
}
</style>
