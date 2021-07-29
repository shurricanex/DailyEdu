<template>
  <v-flex>
    <v-container style="width:50%">
      <v-row dense>
        <v-col v-for="card in cards" :key="card.title" :cols="card.flex">
          <v-card class="my-border-radius">
            <v-img
              :src="getCoverImage"
              class="white--text align-end"
              height="200px"
            ></v-img>

            <v-card-actions>
              <v-list-item>
                <v-avatar min-height="100" min-width="100" size="100">
                  <img
                    :src="`../../storage/images/avatar_images/${user.avatar_image}`"
                    alt="avatar"
                    style="height:100px;width:100px"
                  />
                </v-avatar>
                <v-list-item-content class="ml-3">
                  <v-list-item-title v-text="user.name" class="headline"></v-list-item-title>
                  <v-list-item-subtitle class="title">
                    <v-icon>mdi-home</v-icon>
                    {{user.department}}
                  </v-list-item-subtitle>
                  <v-list-item-subtitle>
                    <v-btn
                      @click.prevent="showFollowing(user.id,true)"
                      v-if="user.following"
                      text
                    >{{user.following}} following</v-btn>
                    <v-btn
                      @click.prevent="showFollowing(user.id,false) "
                      v-if="user.followers"
                      text
                    >{{user.followers}} followers</v-btn>
                  </v-list-item-subtitle>
                  <v-dialog v-model="followDialog" scrollable max-width="40%">
                    <v-card class="rounded-xl">
                      <v-card-title>{{followTitle}}</v-card-title>
                      <v-divider></v-divider>
                      <v-list-item-group v-model="followingUsers">
                        <v-list-item v-for="(item,i) in followingUsers" :key="i">
                          <v-list-item-avatar>
                            <v-img :src="`../../storage/images/avatar_images/${item.avatar_image}`"></v-img>
                          </v-list-item-avatar>
                          <v-list-item-content>
                            <v-list-item-title v-html="item.name"></v-list-item-title>
                          </v-list-item-content>
                        </v-list-item>
                      </v-list-item-group>
                    </v-card>
                  </v-dialog>
                </v-list-item-content>
              </v-list-item>
              <!-- <v-card-text></v-card-text> -->
              <v-spacer></v-spacer>
              <template v-if="getUserId==user.id">
                <v-btn depressed @click="editItem">Edit Profile</v-btn>

                <v-dialog v-model="dialog" scrollable max-width="40%">
                  <v-card>
                    <v-card-title>
                      Edit Profile
                      <v-spacer></v-spacer>
                      <v-btn icon color="pink">
                        <v-icon @click="dialog=false">mdi-close-circle</v-icon>
                      </v-btn>
                      <v-btn color="light-blue" rounded elevation="0" @click="save">Save</v-btn>
                    </v-card-title>
                    <v-divider></v-divider>
                    <v-card-text>
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="6" md="12">
                            <v-card class="my-border-radius">
                              <v-hover>
                                <template v-slot:default="{hover}">
                                  <v-card>
                                    <v-img
                                      :src="editedItem.cover_image?  `../../storage/images/cover_images/${editedItem.cover_image}`:'../../storage/images/cover_images/default_cover.jpg'"
                                      class="white--text align-end"
                                      height="200px"
                                    ></v-img>
                                    <v-fade-transition>
                                      <v-overlay v-if="hover" absolute color="#036358">
                                        <v-btn text @click="pickCover">edit photo</v-btn>
                                      </v-overlay>
                                    </v-fade-transition>
                                  </v-card>
                                </template>
                              </v-hover>

                              <v-card-actions>
                                <v-hover>
                                  <template v-slot:default="{hover}">
                                    <v-avatar min-height="100" min-width="100" size="100">
                                      <img
                                        v-if="editedItem"
                                        :src="`../../storage/images/avatar_images/${editedItem.avatar_image}`"
                                        alt="avatar"
                                        style="height:100px;width:100px"
                                      />
                                      <v-icon v-else :color="red" v-text="people"></v-icon>

                                      <v-fade-transition>
                                        <v-overlay v-if="hover" absolute color="#036358">
                                          <v-btn text @click="pickAvatar">edit photo</v-btn>
                                        </v-overlay>
                                      </v-fade-transition>
                                    </v-avatar>
                                  </template>
                                </v-hover>
                              </v-card-actions>
                            </v-card>
                          </v-col>
                          <v-col cols="12" sm="6" md="4">
                            <v-text-field v-model="editedItem.name" label="username"></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="4">
                            <v-text-field v-model="editedItem.department" label="department"></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field v-model="editedItem.bio" label="Bio"></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                  </v-card>
                </v-dialog>
              </template>
              <template v-else>
                <v-btn depressed @click="toggleFollow(user.id)">{{followed&&user.follow? 'Followed': 'Follow'}}</v-btn>
              </template>
            </v-card-actions>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
    <div style="width:48%; margin:auto;">
      <Scrollable @at-the-bottom="fetchPost(posts.length)">
        <v-card class="my-border-radius">
          <v-row justify="center">
            <v-col cols="1">
              <v-avatar size="2.5rem" style="margin-left:-1em">
                <img :src="`../../storage/images/avatar_images/${getUserAvatar}`" />
              </v-avatar>
            </v-col>
            <v-col cols="10">
              <v-textarea
                v-model="caption"
                auto-grow
                filled
                dense
                :row-height="1"
                rounded
                :placeholder="`What is going on, ${getUserName} ?`"
              ></v-textarea>
            </v-col>
          </v-row>

          <v-flex xs12 class="text-xs-center text-sm-center text-md-center text-lg-center mb-3">
            <v-row no-gutters>
              <v-col v-for="(item, i) in imageUrl" :key="i" :cols="myGrid[i]">
                <v-img :src="item" alt="input image" height="100%" width="100%" v-if="imageUrl">
                  <!-- <video  height="100%" width="100%"  :src="item" controls v-if="imageUrl" > -->

                  <v-row :justify="'end'" class="mr-4" v-show="i==0">
                    <v-btn icon dark @click="pickFile">
                      <v-icon>mdi-plus-circle</v-icon>
                    </v-btn>
                    <v-btn icon dark @click="resetPickedImage">
                      <v-icon>mdi-close-circle</v-icon>
                    </v-btn>
                  </v-row>
                  <!-- </video> -->
                </v-img>
              </v-col>
            </v-row>
          </v-flex>
          <v-row :justify="'end'" class="mb-3 mr-7">
            <v-btn text color="primary">
              <v-icon>mdi-gif</v-icon>
            </v-btn>
            <v-btn text color="primary" @click="pickFile">
              <v-icon>mdi-image-outline</v-icon>
            </v-btn>
            <input
              id="ImageInput"
              type="file"
              style="display: none"
              ref="uploader"
              accept="image/*, video/*"
              @change="onFilePicked"
              multiple
            />
            <input
              id="CoverImage"
              type="file"
              style="display: none"
              ref="coverUploader"
              accept="image/*"
              @change="onCoverPicked"
            />
            <input
              id="AvatarImage"
              type="file"
              style="display: none"
              ref="avatarUploader"
              accept="image/*"
              @change="onAvatarPicked"
            />
            <v-btn
              rounded
              style="background-image:linear-gradient(to left,#3F6393,#0697F2);color:white"
              @click="postPost"
            >Post</v-btn>
          </v-row>
          <div style="height:1rem"></div>
        </v-card>
        <Post v-for="post in posts" :key="post.id" :post="post" class="mt-3"></Post>
      </Scrollable>
    </div>
  </v-flex>
</template>

<script>
import Post from "../components/Post";
import Scrollable from "../components/Scrollable.vue";
export default {
  components: {
    Post,
    Scrollable
  },
  data() {
    return {
        imageFileCover:{},
        imageFileAvatar:{},
      followingUsers: [],
      followTitle: "",
      dialog: false,
      editedItem: {
        avatar_image: "",
        cover_image: "",
        name: "",
        department: "",
        bio: ""
      },
      followed: true,
      followDialog: false,
      posts: [],
      title: "Image Upload",
      dialog: false,
      imageName: [],
      imageUrl: [],
      imageFile: [],
      caption: "",
      myGrid: [],
      user: {},
      cards: [
        {
          title: "Pre-fab homes",
          src: "https://cdn.vuetifyjs.com/images/cards/house.jpg",
          flex: 12
        }
      ]
    };
  },

  created() {
    this.fetchPost();
    var id = this.$route.params.id;
    axios.defaults.headers.common["Authorization"] =
      "Bearer " + this.$store.getters.getToken;
    axios
      .get("users/" + id)
      .then(response => {
        this.user = response.data.data[0];
        console.log("this user" + response.data);
        this.follow = response.data.follow;
      })
      .catch(error => {
        alert(error);
      });
  },
  methods: {
    //custom image uploader
    pickFile() {
      this.$refs.uploader.click();
    },

    onFilePicked(e) {
      const files = e.target.files;
      console.log("target file" + files[0]);
      if (files[0] !== undefined) {
        this.imageName.push(files[0].name);

        if (this.imageName[0].lastIndexOf(".") <= 0) {
          console.log("condition is true");
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        var input = document.getElementsByTagName("input")[0];
        input.onclick = function() {
          this.value = null;
        };
        fr.addEventListener("load", () => {
          this.imageUrl.push(fr.result);
          this.imageFile.push(files[0]); // this is an image file that can be sent to server...
        });
      } else {
        this.imageName = [];
        this.imageFile = [];
        this.imageUrl = [];
      }
      const length = this.myGrid.length;
      if (length == 2) {
        this.myGrid.splice(1, 1, 6, 6);
      }
      if (length < 2) {
        this.myGrid.push(12);
      }
    },
    pickCover() {
      let self = this;
      console.log(self.$refs);
      self.$refs.coverUploader.click();
    },
    pickAvatar() {
      let self = this;
      console.log(self.$refs);
      self.$refs.avatarUploader.click();
    },
    onAvatarPicked(e) {
      const files = e.target.files;
      console.log("target file" + files[0]);
      if (files[0] !== undefined) {
        this.imageName.push(files[0].name);

        if (this.imageName[0].lastIndexOf(".") <= 0) {
          console.log("condition is true");
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        var input = document.getElementsByTagName("input")[0];
        input.onclick = function() {
          this.value = null;
        };
        fr.addEventListener("load", () => {
          this.editedItem.cover_image=fr.result;
          this.imageFileAvatar = files[0]; // this is an image file that can be sent to server...
          console.log("cover"+this.imageFileCover);
        });
      } else {
        this.imageName = [];
        this.imageFileCover = [];
        this.imageFileAvatar = [];
        this.imageUrl = [];
      }
    },
    onCoverPicked(e) {
      const files = e.target.files;
      console.log("target file" + files[0]);
      if (files[0] !== undefined) {
        this.imageName.push(files[0].name);

        if (this.imageName[0].lastIndexOf(".") <= 0) {
          console.log("condition is true");
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        var input = document.getElementsByTagName("input")[0];
        input.onclick = function() {
          this.value = null;
        };
        fr.addEventListener("load", () => {
          this.editedItem.cover_image=fr.result;
          this.imageFileCover = files[0]; // this is an image file that can be sent to server...
          console.log("cover"+this.imageFileCover);
        });
      } else {
        this.imageName = [];
        this.imageFileCover = [];
        this.imageUrl = [];
      }
    },

    resetPickedImage() {
      this.ImageFileCover =[]
      this.imageFileAvatar =[]
      this.imageUrl = [];
      this.imageFile = [];
      this.imageName = [];
      this.myGrid = [];
    },
    //custom image uploader
    postPost() {
      var fd = new FormData();

      this.imageFile.forEach(file => fd.append("image[]", file));
      fd.append("caption", this.caption);
      axios
        .post("/post", fd)
        .then(response => {
          this.myGrid = [];
          this.posts = [];
          this.fetchPost(0);
        })
        .catch(error => {
          console.log(error);
        })
        .finally(
          (this.imageName = []),
          (this.imageFile = []),
          (this.caption = []),
          (this.imageUrl = []),
          (this.myGrid = [])
        );
    },

    onFilePicked(e) {
      const files = e.target.files;
      console.log("target file" + files[0]);
      if (files[0] !== undefined) {
        this.imageName.push(files[0].name);

        if (this.imageName[0].lastIndexOf(".") <= 0) {
          console.log("condition is true");
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        var input = document.getElementsByTagName("input")[0];
        input.onclick = function() {
          this.value = null;
        };
        fr.addEventListener("load", () => {
          this.imageUrl.push(fr.result);
          this.imageFile.push(files[0]); // this is an image file that can be sent to server...
        });
      } else {
        this.imageName = [];
        this.imageFile = [];
        this.imageUrl = [];
      }
      const length = this.myGrid.length;
      if (length == 2) {
        this.myGrid.splice(1, 1, 6, 6);
      }
      if (length < 2) {
        this.myGrid.push(12);
      }
    },

    resetPickedImage() {
      this.imageUrl = [];
      this.imageFile = [];
      this.imageName = [];
      this.myGrid = [];
    },
    showFollowing(userId, followingOrFollower) {
      this.followTitle = "Followers";
      if (followingOrFollower) {
        this.followTitle = "Following";
      }

      this.followDialog = true;
      axios
        .get("user/following", {
          params: {
            user_id: userId,
            followingOrFollower: followingOrFollower
          }
        })
        .then(response => {
          this.followingUsers = response.data;
          console.log("followingUserr" + response.data);
        })
        .catch(error => {
          alert(error);
        });
    },
    editItem() {
      this.dialog = true;
      this.editedItem = Object.assign({}, this.user);
    },
    save() {
      this.$Progress.start();
      var fd = new FormData();
      fd.append('cover_image',this.imageFileCover)
      fd.append('avatar_image',this.imageFileAvatar)
      fd.append('bio',this.editedItem.bio)
      fd.append('department',this.editedItem.department)
      fd.set('_method','patch')
      axios
        .post("users/" + this.editedItem.id, fd)
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
    toggleFollow(id) {
      axios.post("follow/" + id).then(response => {
        var data = response.data;
        console.log(data)
        if (data === "Followed") {
          this.followed = true;
          this.user.followers++;
        } else {
          this.followed = false;
           this.user.followers--;
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
    getCoverImage(){
        return this.user.cover_image? `../../storage/images/cover_images/${this.user.cover_image}` : '../../storage/images/cover_images/default_cover.jpg'
    },
    getUserAvatar() {
      return this.$store.getters.getUserAvatar;
    },
    getUserName() {
      return this.$store.getters.getUserName;
    },
    getUserId() {
      return this.$store.getters.getUserId;
    }
  }
};
</script>

<style lang="css" scoped>
.my-border-radius {
  border-radius: 3vh !important;
}
.v-btn--depressed {
  border-radius: 1em !important;
}
</style>
