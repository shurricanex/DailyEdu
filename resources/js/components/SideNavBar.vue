<template>
  <v-app >
    <v-container  >
        <v-navigation-drawer
          style="width:20%"
          v-model="drawer"
          :src="bg"
          absolute

        >
        <div class="horizontal-bar"></div>
          <v-list
            dense
            nav
            class="py-0"
          >
            <v-list-item two-line :class="miniVariant && 'px-0'">
                <img src="/img/dedulogo.png" width="40%" height="40%">
            </v-list-item>


            <v-list-item
              v-for="item in items"
              :key="item.title"
              link
              :to="item.href"

            >
              <v-list-item-icon>
                <v-icon>{{ item.icon }}</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title >{{ item.title }}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>



              <v-menu top :offset-y="offset">
        <template v-slot:activator="{ on }">

              <v-list-item
              v-on="on"
            >
              <v-list-item-icon>
                <v-icon>mdi-cog-outline</v-icon>
              </v-list-item-icon>

              <v-list-item-content>
                <v-list-item-title >More</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

        </template>

        <v-list>
          <v-list-item
            v-for="(item, index) in moreItems"
            :key="index"
            :to="item.href"
          >
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>


          </v-list>
        </v-navigation-drawer>
      <!-- </v-card> -->
    </v-container>
  </v-app>

</template>

<script>
    export default {
  data () {
    return {
      drawer: true,
      items: [
        { title: 'Home', icon: 'mdi-home',href:'/welcome' },
        { title: 'Explore', icon: 'mdi-magnify',href:'/Explore' },
        { title: 'Groups', icon: 'mdi-account-group-outline',href:'/Groups' },
        { title: 'Messages', icon: 'mdi-email-outline',href:'/Messages' },
        { title: 'Notifications', icon: 'mdi-bell-outline',href:'/Notifications' },
        { title: 'Level', icon: 'mdi-help-box',href:'/Level' },
        { title: 'Settings', icon: 'mdi-cog-outline' },
      ],
      moreItems: [
      { title: 'About' },
      { title: 'Log out',  href:'/logout' },
      ],
      offset: true,
      color: 'primary',
      right: false,
      miniVariant: false,
      expandOnHover: false,
      background: false,
    }
  },
  compute:{
     getUserName(){
      return this.$store.getters.getUserName;
    },
    getUserType(){

      return this.$store.getters.getUserType;

    },
    getUserAvatar(){
      return this.$store.getters.getUserAvatar;
    }
  }

    }
</script>

<style lang="css" scoped>
.v-list-item__title{
    font-size:1.5rem !important;
    line-height:3rem !important;
}
.v-icon{
    font-size:2.3em !important;
    background-color:#EFEFEF !important;
    width:1.2em !important;
    height: 1.2em !important;
    border-radius: 50%;
}
.v-list-item{
    margin-left:2em !important;
}
.my-box-shadow{
 box-shadow: 0px 0px 10px #4a82b6 !important;
}
.horizontal-bar{
    height:100rem;
    width:20%;
    background-image:linear-gradient(to bottom,#3F6393,#0697F2);
     margin-top:-.5em !important;
    /* margin-bottom:-2em !important; */
    margin-left: -1.5em !important;
    position:fixed;
}
a{
    text-decoration: none;
}
</style>
