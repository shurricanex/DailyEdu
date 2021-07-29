<template>
    <div style="width:48%; margin:auto;margin-top:0.5em" >
        <div>
    <v-text-field
      v-on:keyup.enter="search"
      v-model="searchData"
      placeholder="Search"
      prepend-inner-icon="mdi-magnify"
      filled
      rounded
      dense
      clearable

    ></v-text-field>
  </div>
        <v-card class="my-border-radius mt-2"  v-for="user in users" :key="user.id">
         <router-link :to="{name:'user-profile',params:{id:user.id}}" class="text-decoration-none">
      <v-row >
        <v-col cols="5">
          <v-card class="my-border-radius ml-3">
            <v-img

              :src="user.cover_image?`../../storage/images/cover_images/${user.cover_image}`:'../../storage/images/cover_images/default_cover.jpg'"
              class="white--text align-end"

              height="120px"
            ></v-img>
            </v-card>
        </v-col >
        <v-col cols="5">
        <v-list-item>
          <v-avatar
                size="avatarSize"
                color="grey"
              >
                <img :src="`../../storage/images/avatar_images/${user.avatar_image}`" alt="alt">
              </v-avatar>
              <v-list-item-content>
                <v-list-item-title class="ml-2 text-h5" style="margin-botton:-5em">

                  {{user.name}}

                </v-list-item-title>

              </v-list-item-content>
        </v-list-item>

              <v-card-text ><v-icon>mdi-home</v-icon>{{user.department?user.department:' no department'}}
              </v-card-text >
              <v-card-text >  {{ user.followers ?user.followers : 'No' }} followers
              </v-card-text >



        </v-col>
      </v-row>
      </router-link>




    </v-card>
    </div>
</template>

<script>
import search from '../components/Search'

    export default {
        components:{
            search,
        },
        data() {
          return {
            searchData:'',
            users:[]
          }
        },
        methods: {
          search(){
            axios.get('search/',{
              params:{
                q:this.searchData
              }
            })
            .then(response=>{
              console.log(response.data);
              this.users =response.data.users;

            })
            .catch(error=>{
              alert(error)
            })
          },
  showFollowing(userId,followingOrFollower){
        this.followTitle = 'Followers';
        if(followingOrFollower){
            this.followTitle = 'Following';
        }

        this.followDialog = true;
        axios.get('user/following',{
            params:{
                user_id:userId,
                followingOrFollower:followingOrFollower
            }
        })
             .then(response=>{
                 this.followingUsers =response.data;
                console.log('followingUserr'+response.data);

             })
             .catch(error=>{
                 alert(error);
             })
    },
    editItem() {
      this.dialog = true;
      this.editedItem = Object.assign({}, this.user);
    },
    save() {
      this.$Progress.start();

      axios
        .put("users/" + this.editedItem.id, this.editedItem)
        .then(() => {
          swal.fire("Updated!", "Information has been updated.", "success");
          this.$Progress.finish();
          Fire.$emit("AfterCreate");
        })
        .catch(() => {
          this.$Progress.fail();
        });
      this.dialog = false;
    },
    ToggleFollow(id) {
      axios.post("/follow/" + id).then(response => {
        const data = response.data.data;
        if (data == "follow") {
          this.followed = true;
        } else {
          this.followed = false;
        }
      });
    },
    fetchPost(offset = 0) {
      this.loading = true;
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.getters.getToken;
      axios
        .get("/user/posts", {
          params: {
            offset: offset,
            userId: this.$route.params.id
          }
        })
        .then(response => {
          this.posts = this.posts.concat(response.data.data);
          console.log(response.data.data);
        })

        .finally(response => (this.loading = false));
    }
        },
        computed: {
          getAuthUser(){
            return this.$store.getters.getAuthUser;
          }
        },
        created() {
            //  this.fetchPost();
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.getters.getToken;
    axios
      .get("users")
      .then(response => {
        this.users = response.data.data;
        this.follow = response.data.follow;
      })
      .catch(error => {
        alert(error);
      });
        },
    }
</script>

<style lang="css" scoped>
 h1{ margin:auto;}
 .my-border-radius {
  border-radius: 4vh !important;
}
.v-card__subtitle, .v-card__text, .v-card__title {
    padding: 8px !important;
}
.v-list-item {

    padding: 0 8px !important;

}
</style>
