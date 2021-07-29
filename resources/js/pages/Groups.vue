<template>
  <div style="width:48%; margin:auto;margin-top:0.5em">
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
      <v-btn class="mx-2" fab dark small color="light-blue
" @click="createGroupDialog=true">
        <v-icon dark>mdi-plus</v-icon>
      </v-btn>
      <v-dialog v-model="createGroupDialog" class="rounded-xl" max-width="600px">
        <v-card class="rounded-xl">
          <v-card-title>
            <span class="headline">Create Group</span>
          </v-card-title>
          <v-divider></v-divider>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="6">
                  <v-text-field outlined label="Group Name*" v-model="groupName" required></v-text-field>
                </v-col>

                <v-col cols="12" sm="12">
                  <v-autocomplete
                    hide-details
                    rounded
                    filled
                    dense
                    :search-input.sync="searching"
                    v-model="selectedUser"
                    :items="users"
                    chips
                    color="blue-grey lighten-2"
                    label="Select"
                    item-text="name"
                    item-value="name"
                    multiple
                    return-object
                  >
                    <template v-slot:selection="data">
                      <v-chip
                        v-bind="data.attrs"
                        :input-value="data.selected"
                        close
                        @click="data.select"
                        @click:close="remove(data.item)"
                      >
                        <v-avatar left>
                          <v-img
                            :src="`../../storage/images/avatar_images/${data.item.avatar_image}`"
                          ></v-img>
                        </v-avatar>
                        {{ data.item.name }}
                      </v-chip>
                    </template>
                    <template v-slot:item="data">
                      <template>
                        <v-list-item-avatar>
                          <img
                            :src="`../../storage/images/avatar_images/${data.item.avatar_image}`"
                          />
                        </v-list-item-avatar>
                        <v-list-item-content>
                          <v-list-item-title v-html="data.item.name"></v-list-item-title>
                          <v-list-item-subtitle v-html="data.item.email"></v-list-item-subtitle>
                        </v-list-item-content>
                      </template>
                    </template>
                  </v-autocomplete>
                </v-col>
              </v-row>
            </v-container>
            <small>*indicates required field</small>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="blue darken-1"
              elevation="0"
              text
              @click="createGroup"
            >Create</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
    <v-card class="my-border-radius mt-2" v-for="group in groups" :key="group.id">
      <router-link :to="{name:'group-profile',params:{id:group.id}}" class="text-decoration-none">
        <v-row>
          <v-col cols="5">
            <v-card class="my-border-radius ml-3">
              <v-img
                :src="group.cover_image?`../../storage/images/cover_images/${group.cover_image}`:'../../storage/images/cover_images/default_cover.jpg'"
                class="white--text align-end"
                height="120px"
              ></v-img>
            </v-card>
          </v-col>
          <v-col cols="5">
            <v-list-item>
              <v-avatar size="avatarSize" color="grey" class="rounded-xl">
                <img
                  :src="`../../storage/images/avatar_images/${group.avatar_image}`"
                  alt="group_avatar"
                  v-if="group.avatar_image"
                />
                <v-icon v-else>mdi-account-group-outline</v-icon>
              </v-avatar>
              <v-list-item-content>
                <v-list-item-title class="ml-2 text-h5" style="margin-botton:-5em">{{group.name}}</v-list-item-title>
              </v-list-item-content>
            </v-list-item>

            <v-card-text>
              <v-icon>mdi-account-group-outline</v-icon>
              {{group.member_numbers?group.member_numbers:'not available'}} members
            </v-card-text>

          </v-col>
        </v-row>
      </router-link>
    </v-card>
  </div>
</template>

<script>
import search from "../components/Search";

export default {
  components: {
    search
  },
  data() {
    return {
      groupName:'',
      users: [],
      searching: "",
      selectedUser: [],
      createGroupDialog: false,
      searchData: "",
      groups: []
    };
  },
  methods: {
    remove(item) {
      const index = this.selectedUser.indexOf(item.name);
      console.log("item.name" + item.name + "index" + index);
      if (index >= 0) this.selectedUser.splice(index, 1);
    },
    createGroup() {
       var users_ids  = this.selectedUser.map(function(item){
            return item.id
        })

      axios.post('groups/',{
          name:this.groupName,
          users:users_ids
      })
           .then(response=>{
               console.log(response)
           })
    },

    searchUser(query) {
      axios
        .get("findUser", {
          params: {
            q: this.searching
          }
        })
        .then(response => {
          this.users = response.data.data;
          console.log("this users" + this.users);
        });
    },
    search() {
      axios
        .get("findgroup/", {
          params: {
            q: this.searchData
          }
        })
        .then(response => {
          console.log(response.data);
          this.groups = response.data.data;
        })
        .catch(error => {
          alert(error);
        });
    },
    showFollowing(groupId, followingOrFollower) {
      this.followTitle = "Followers";
      if (followingOrFollower) {
        this.followTitle = "Following";
      }

      this.followDialog = true;
      axios
        .get("group/following", {
          params: {
            group_id: groupId,
            followingOrFollower: followingOrFollower
          }
        })
        .then(response => {
          this.followinggroups = response.data;
          console.log("followinggroupr" + response.data);
        })
        .catch(error => {
          alert(error);
        });
    },
    editItem() {
      this.dialog = true;
      this.editedItem = Object.assign({}, this.group);
    },
    save() {
      this.$Progress.start();

      axios
        .put("groups/" + this.editedItem.id, this.editedItem)
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
        .get("/group/posts", {
          params: {
            offset: offset,
            groupId: this.$route.params.id
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
    getAuthgroup() {
      return this.$store.getters.getAuthgroup;
    }
  },
  watch: {
    searching(val) {
      val && val !== this.selectedUser && this.searchUser(val);
    },
    model(val, prev) {
      if (val.length === prev.length) return;

      this.model = val.map(v => {
        if (typeof v === "string") {
          v = {
            text: v,
            color: this.colors[this.nonce - 1]
          };

          this.items.push(v);

          this.nonce++;
        }

        return v;
      });
    }
  },
  created() {
    //  this.fetchPost();
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.getters.getToken;
    axios
      .get("groups")
      .then(response => {
        this.groups = response.data.data;
        this.follow = response.data.follow;
      })
      .catch(error => {
        alert(error);
      });
  }
};
</script>

<style lang="css" >
.v-dialog {
  box-shadow: none !important;
}
h1 {
  margin: auto;
}
.my-border-radius {
  border-radius: 4vh !important;
}
.v-card__subtitle,
.v-card__text,
.v-card__title {
  padding: 8px !important;
}
.v-list-item {
  padding: 0 8px !important;
}
</style>
