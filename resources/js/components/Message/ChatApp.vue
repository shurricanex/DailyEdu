<template>
  <v-row no-gutters>
    <v-col cols="5">
      <ContactsList :contacts="contacts" @selected="startConversationWith" />
    </v-col>
    <v-col cols="6">
      <Conversation :contact="selectedContact" :messages="messages" @new="saveNewMessage" />
    </v-col>
  </v-row>
</template>

<script>
import Conversation from "./Conversation";
import ContactsList from "./ContactsList";

export default {
  props: {
    user: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      selectedContact: null,
      messages: [],
      contacts: []
    };
  },
  mounted() {
    Echo.private(`messages.${this.user.id}`).listen(".SendMessage", e => {
      this.hanleIncoming(e.message);
      this.getContact();
    });
    this.getContact();
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
    startConversationWith(contact) {
      this.updateUnreadCount(contact, true);

      axios.get(`/conversation/${contact.id}`).then(response => {
        this.messages = response.data;
        this.selectedContact = contact;
        console.log("selectdContact" + this.selectedContact.id);
      });
    },
    saveNewMessage(message) {
      this.messages.push(message);
    },
    hanleIncoming(message) {
      var message = JSON.parse(message);
      if (this.selectedContact && message.from == this.selectedContact.id) {
        this.saveNewMessage(message);
        return;
      }

      this.updateUnreadCount(message.from_contact, false);
    },
    updateUnreadCount(contact, reset) {
      this.contacts = this.contacts.map(single => {
        if (single.id !== contact.id) {
          return single;
        }

        if (reset) single.unread = 0;
        else single.unread += 1;

        return single;
      });
    }
  },
  components: { Conversation, ContactsList }
};
</script>


<style lang="scss" scoped>
.chat-app {
  display: flex;
  margin-left: 500px;
  width: 50%;
  height: 70%;
}
</style>
