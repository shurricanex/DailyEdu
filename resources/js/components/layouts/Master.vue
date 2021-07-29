<template>
  <v-app id="inspire" >
    <Navig />
    <transition
      name="router-animation"
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
      mode="out-in"
    >
      <router-view></router-view>
    </transition>
    <vue-progress-bar></vue-progress-bar>
  </v-app>
</template>

<script>
import Navig from "./Navig.vue";
import Admin from "../../pages/Admin/Admin.vue";
export default {
  components: { Navig, Admin },
  computed: {
    loggedIn() {
      return this.$store.getters.loggedIn;
    }
  },
  data() {
      return {
          notifications:[]
      }
  },
  methods: {

       getContact() {
            axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.getters.getToken;
      axios.get("/contacts").then(response => {
        this.contacts = response.data;
        var unreadMessages = 0;
        var allContacts = response.data;
        allContacts.forEach(function(item) {
          unreadMessages += item.unread;
        });
          this.$store.dispatch('pushNotification',unreadMessages);
      });
    },
  },
  created() {

   this.getContact();
  
  },

};
</script>

 <style lang="scss" scopred>
.btn-submit {
  width: 100%;
  padding: 14px 12px;
  font-size: 18px;
  font-weight: bold;
  background: #60bd4f;
  color: white;
  border-radius: 3px;
  cursor: pointer;

  &:hover {
    background: darken(#60bd4f, 10%);
  }

  &:disabled {
    background: lighten(#60bd4f, 25%);
    cursor: not-allowed;
  }
}

.server-error {
  margin-bottom: 12px;
  font-size: 16px;
  padding: 10px 16px;
  color: #a94442;
  background: #f3dede;
  border-radius: 4px;
}

.success-message {
  background-color: #dff0d8;
  color: #3c763d;
  margin-bottom: 12px;
  font-size: 16px;
  padding: 10px 16px;
  border-radius: 4px;
}

.form-error {
  font-size: 16px;
  color: #a94442;
}

.input-error {
  border: 1px solid red;
}

.page-wrapper {
  animation-duration: 0.2s;
}

// CSS Spinner

.lds-ring-container {
  position: absolute;
  right: 50%;
}

.lds-ring {
  display: inline-block;
  position: relative;
  width: 64px;
  height: 64px;
}
.lds-ring div {
  box-sizing: border-box;
  display: block;
  position: absolute;
  width: 25px;
  height: 25px;
  // margin: 6px;
  border: 3px solid #fff;
  border-radius: 50%;
  animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite;
  border-color: #fff transparent transparent transparent;
}
.lds-ring div:nth-child(1) {
  animation-delay: -0.45s;
}
.lds-ring div:nth-child(2) {
  animation-delay: -0.3s;
}
.lds-ring div:nth-child(3) {
  animation-delay: -0.15s;
}
@keyframes lds-ring {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>

