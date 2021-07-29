<template>
  <div>
    <nav
      id="nav"
      class="navbar navbar-expand-md navbar-light bg-white shadow-sm"
      style="background-image:linear-gradient(to left,#3F6393,#0697F2); height:66px;width:100%"
      v-if="!loggedIn"
    >
      <div class="container">
        <router-link class="navbar-brand" to="/">
          <h4>Home</h4>
        </router-link>
        <button
          class="navbar-toggler"
          type="button"
          data-toggle="collapse"
          data-target="#navbarSupportedContent"
          aria-controls="navbarSupportedContent"
          aria-expanded="false"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <!-- Left Side Of Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            <template v-if="!loggedIn">
              <li>
                <router-link
                  :to="{name:'Login'}"
                  class="nav-link"
                  style="margin-top:.4em;font-size:1.5em;"
                >
                  <h4>Login</h4>
                </router-link>
              </li>
            </template>
          </ul>
        </div>
      </div>
    </nav>
    <template v-if="loggedIn">
      <v-card v-if="getUserType!=='admin'">
        <v-navigation-drawer
        :src="switchSideBarBg ? '../../storage/images/cover_images/matthew-smith-rFBA42UFpLs-unsplash.jpg' : ''"
         gradient="to bottom, rgba(0,0,0,.1), rgba(0,0,0,.5)"
          style="width:24%;z-index:1;overflow:hidden;position:fixed"
          v-model="drawer"
          class="my-box-shadow"
          permanent
        >
          <div class="horizontal-bar"></div>
          <v-list rounded>
            <v-list-item two-line :class=" 'px-0'">
              <img src="/img/dedulogo.png" width="40%" height="40%" />
            </v-list-item>

            <v-list-item
              class="my-v-list-item"
              v-for="item in items"
              :key="item.title"
              link
              :to="item.href"
              color="'primary'"
            >
              <v-list-item-icon>
                <v-icon>{{ item.icon }}</v-icon>
                <v-badge
                  overlap
                  color="primary"
                  :content=" getUnread"
                  v-if="item.title=='Messages'&&getUnread"
                ></v-badge>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title class="font-weight-medium">{{ item.title }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-list-item
              link
              class="my-v-list-item"
              :to="{name:'user-profile',params:{id:getUserId}}"
            >
              <v-list-item-avatar class="v-list-item__icon">
                <img :src="`../../storage/images/avatar_images/${getUserAvatar}`" />
              </v-list-item-avatar>

              <v-list-item-content>
                <v-list-item-title class="font-weight-medium">{{ getUserName }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link class="my-v-list-item" @click="navigToNotification">
              <v-list-item-icon>
                <v-icon>mdi-bell-outline</v-icon>

                <v-badge
                  overlap
                  color="primary"
                  :content=" unreadNotifications.length"
                  v-if="unreadNotifications.length!=0"
                ></v-badge>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title class="font-weight-medium">Notifications</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>

          <v-menu top>
            <template v-slot:activator="{ on }">
              <v-list rounded>
                <v-list-item class="my-v-list-item-1" v-on="on">
                  <v-list-item-icon>
                    <v-icon>mdi-dots-horizontal-circle-outline</v-icon>
                  </v-list-item-icon>

                  <v-list-item-content>
                    <v-list-item-title class="font-weight-medium">More</v-list-item-title>
                  </v-list-item-content>
                </v-list-item>
              </v-list>
            </template>

            <v-list :rounded="true">
              <v-list-item v-for="(item, index) in moreItems" :key="index" :to="item.href">
                <v-list-item-icon>
                  <v-icon>{{ item.icon }}</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                  <v-list-item-title>{{ item.title }}</v-list-item-title>
                </v-list-item-content>

              </v-list-item>
              <v-list-item @click="settingDialog=true">
                <v-list-item-icon>
                  <v-icon>mdi-cog-outline</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                  <v-list-item-title>Settings</v-list-item-title>
                </v-list-item-content>

              </v-list-item>
              <v-list-item @click="$vuetify.theme.dark = !$vuetify.theme.dark">

               <v-list-item-icon>
                  <v-icon>mdi-theme-light-dark</v-icon>
                </v-list-item-icon>

                <v-list-item-content>
                  <v-list-item-title>Dark and Light mode</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list>
          </v-menu>
         <v-dialog
             v-model="settingDialog"
             scrollable
             :overlay="false"
             max-width="500px"
             transition="dialog-transition"
         >
            <v-card>
                <v-card-title>
                     <v-switch v-model="switchSideBarBg" flat label="Switch SideBar  Background" color="indigo darken-3"></v-switch>
                </v-card-title>
            </v-card>
         </v-dialog>
        </v-navigation-drawer>
        <!-- </v-card> -->
      </v-card>
      <template v-if="getUserType=='admin'">
        <Admin />
      </template>
    </template>
  </div>
</template>

<script>
import Admin from "../../pages/Admin/Admin";
export default {
  name: "Navig",
  components: {
    Admin
  },

  data() {
    return {
        switchSideBarBg : true,
        settingDialog:false,
      notifications: [],
      unreadNotifications: [],
      notificationCounter: 0,
      drawer: true,
      items: [
        { title: "Home", icon: "mdi-home", href: "/home" },
        { title: "Explore", icon: "mdi-magnify", href: "/Explore" },
        { title: "Groups", icon: "mdi-account-group-outline", href: "/Groups" },
        { title: "Messages", icon: "mdi-email-outline", href: "/Messages" },

        { title: "Level", icon: "mdi-help-box", href: "/Level" }
      ],
      moreItems: [

        { title: "Log out", icon: "mdi-logout", href: "/logout" }
      ],
      background: false
    };
  },
  methods: {
    navigToNotification() {

      axios.get("mark-as-read/" + this.getUserId).then(response => {
        var authUser = response.data;
        this.notifications = authUser.notifications;
        this.$router.push({ name: "Notifications" });
      });
    }
  },

  computed: {
    loggedIn() {
      return this.$store.getters.loggedIn;
    },
    getUserName() {
      return this.$store.getters.getUserName;
    },
    getUserType() {
      return this.$store.getters.getUserType;
    },
    getUserAvatar() {
      return this.$store.getters.getUserAvatar;
    },
    getUserId() {
      return this.$store.getters.getUserId;
    },
    getUnread() {
      return this.$store.getters.getNotification;
    },

    getUnreadNotification() {
      return this.notifications.filter(item => {
        return item.read_at == null;
      });
    },
    getAuthUser() {
      return this.$store.getters.getAuthUser;
    }
  },
  watch: {
      //watch when there is any change on notifications
      // if notifications change value it will filter to get notification read at null
    notifications(value) {
      this.unreadNotifications = this.notifications.filter(notification => {
        return notification.read_at == null;
      });
    }
  },
  mounted() {
    Echo.private("App.User." + this.$store.getters.getUserId).notification(
      notification => {
        this.notifications.push(notification.notification);
      }
    );
  },
  created() {
    var authUser = this.getAuthUser;
    authUser = JSON.parse(authUser);
    this.notifications = authUser.notifications;
    this.unreadNotifications = this.notifications.filter(notification => {
      return notification.read_at == null;
    });
  }
};
</script>
<style scoped>
.my-dropdown {
  text-align: center !important;
  background-color: rgb(2, 165, 8);
}
.my-dropdown a {
  text-decoration: none;
  background-color: rgb(2, 165, 8);
  padding: 0.6em;
  color: black;
}
.my-dropdown a:hover {
  background-color: rgb(243, 245, 245);
  color: black;
}
h4 {
  color: white !important;
}
h4:hover {
  color: goldenrod !important;
}
h4:active {
  color: goldenrod;
}
i {
  font-size: 3em !important;
}
#last-li {
  margin-right: -5em;
  margin-left: 4em;
}
.v-list-item__title {
  font-size: 1.4rem !important;
  line-height: 2rem !important;
}
.v-icon {
  font-size: 2.3em !important;
  background-color: #efefef !important;
  width: 1.2em !important;
  height: 1.2em !important;
  border-radius: 50%;
}

.my-v-list-item {
  margin-left: 0.5em !important;
}
.my-v-list-item-1 {
  margin-left: 1em !important;
}
.my-box-shadow {
  box-shadow: 0 0px 12px rgba(0, 0, 0, 0.171) !important;
}
.horizontal-bar {
  height: 100rem;
  width: 20%;
  background-image: linear-gradient(to bottom, #3f6393, #0697f2);
  margin-top: -0.5em !important;
  margin-left: -1.5em !important;
  position: fixed;
}
a {
  text-decoration: none;
}
</style>
