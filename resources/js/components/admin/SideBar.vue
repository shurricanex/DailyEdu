<template>
  <div>
    <v-card width="5%">
      <v-navigation-drawer
        permanent
        class="my-gradient"
        dark
        mini-variant
        mini-variant-width="70"
        app
      >
        <v-list rounded>
          <v-list-item>
            <v-list-item :class="'px-0'">
              <img src="/img/logo.png" width="50em" />
            </v-list-item>
          </v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list  rounded height="256" >
          <v-list-item  v-for="item in items" :key="item.id" :to="item.href" class="text-decoration-none">
            <v-list-item-icon>
              <v-icon>{{item.icon}}</v-icon>
            </v-list-item-icon>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <!-- <v-app-bar ></v-app-bar> -->
    </v-card>
  </div>
</template>

<script>
export default {
  data: () => ({
      items:[
          {icon:'mdi-account-multiple',href:'/user'},
          {icon:'mdi-clipboard-text-multiple-outline',href:''},
          {icon:'mdi-account-key-outline',href:'/role'},
          {icon:'mdi-account-lock-outline',href:'/permission'},
          {icon:'mdi-cog-outline',href:''},
      ]
  })
};
</script>

<style lang="css" scoped>
.my-gradient {
  background-image: linear-gradient(to bottom, #3f6393, #0697f2);
}
</style>
