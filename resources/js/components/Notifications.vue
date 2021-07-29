<template>
  <div style="width:48%; margin:auto;">
    <v-card class="my-border-radius mt-3" v-for="item in notifications" :key="item.id">
      <v-row justify="center" >
        <v-col cols="1">
          <v-avatar size="2.5rem" style="margin-left:-1em">
            <img :src="`../../storage/images/avatar_images/${item.data.userLiked.avatar_image}`" />
          </v-avatar>
        </v-col>
        <v-col cols="10">
          <v-card-text>{{item.data.userLiked.name}} liked your post</v-card-text>
        </v-col>
      </v-row>
    </v-card>
  </div>
</template>

<script>
import search from "../components/Search";

export default {
  components: {
    search
  },
  methods: {},
  data() {
    return {
      users: {},
      notifications: []
    };
  },
  mounted() {
    Echo.private("App.User." + this.$store.getters.getUserId).notification(
      notification => {
        this.notifications.push(notification.notification);
        console.log(notification);
      }
    );
  },
  created() {
    var authUser = this.$store.getters.getAuthUser;
    this.users = authUser;
    this.notifications = this.users.notifications;
    

  }
};
</script>

<style lang="css" scoped>
h1 {
  margin: auto;
}
.my-border-radius {
  border-radius: 4vh !important;
}
</style>
