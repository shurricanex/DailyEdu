<template>
  <div style="width:75%; margin-left:25%;background-color: white;">
    <ChatApp :user="user"></ChatApp>
  </div>
</template>

<script>
import ChatApp from "../components/Message/ChatApp";
export default {
  components: {
    ChatApp
  },
  data() {
    return {
      user: {}
    };
  },
  mounted() {
    Echo.private(`messages.${this.user.id}`).listen(".SendMessage", e => {

      this.getContact();
    });

  },
  methods: {
      getContact() {
      axios.get("/contacts").then(response => {
        this.contacts = response.data;
        var unreadMessages = 0;
        var allContacts = response.data;
        allContacts.forEach(function(item) {
          unreadMessages += item.unread;
        });
        console.log("unreadMessages" + unreadMessages);
          this.$store.dispatch('pushNotification',unreadMessages);
      });
    },
  },
  created() {
    this.user = this.$store.getters.getAuthUser;
  }
};
</script>

<style lang="css" scoped>
h1 {
  margin: auto;
}
</style>
