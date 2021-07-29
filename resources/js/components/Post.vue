<template>
  <!-- post section -->
  <div>
    <v-card width="100%" class="my-border-radius">
      <v-list-item>
        <router-link :to="{name:'user-profile',params:{id:post.user_id}}" class="mr-2">
          <v-avatar>
            <img :src="`../../storage/images/avatar_images/${post.avatar_image}`" />
          </v-avatar>
        </router-link>
        <v-list-item-content>
          <v-list-item-title class="title" >
            <router-link
              :to="{name:'user-profile',params:{id:post.user_id}}"
              class="mr-2"
            >{{post.username}}</router-link>
          </v-list-item-title>

          <v-list-item-subtitle>{{post.created_at|formatDate}}</v-list-item-subtitle>
        </v-list-item-content>
        <v-menu v-if="getUserName==post.username" transition="slide-y-transition" bottom>
          <template v-slot:activator="{on}">
            <v-btn icon v-on="on">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
          <v-list rounded>
            <v-list-item>
              <v-btn color="error" text rounded @click="deletePost(post.entity_id)">
                <v-icon>mdi-delete-outline</v-icon>
              </v-btn>
            </v-list-item>
            <v-list-item>
              <v-btn color="primary" rounded text @click="editPost(post)">
                <v-icon>mdi-file-edit-outline</v-icon>
              </v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-list-item>
      <template v-if="post.parent!==null">
        <v-flex align-center>
          <v-card width="100%" class="my-border-radius" outlined>
            <v-list-item>
              <router-link :to="{name:'user-profile',params:{id:post.parent.user_id}}" class="mr-2">
                <v-avatar>
                  <img :src="`../../storage/images/avatar_images/${post.parent.avatar_image}`" />
                </v-avatar>
              </router-link>
              <v-list-item-content>
                <v-list-item-title class="title">
                  <router-link
                    :to="{name:'user-profile',params:{id:post.parent.user_id}}"
                  >{{post.parent.username}}</router-link>
                </v-list-item-title>
                <v-list-item-subtitle>{{post.parent.created_at|formatDate}}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-row no-gutters>
              <v-col

                style="text-align:center;"
                v-for="(item,i) in post.parent.photos"
                :key="i"
                :cols="post.parent.photos.length==3&&i==0? 12 : (post.parent.photos.length==1? 12 : 6) "
              >
                <img
                 v-show="item.photo!=null"
                  :src="`../../storage/images/post_images/${item.photo}`"
                  style="width:100%; height:100%"
                />
              </v-col>
            </v-row>
          </v-card>
        </v-flex>
      </template>

      <template v-if="post.parent===null">
        <v-card-text>{{post.photos[0].caption}}</v-card-text>
        <v-row no-gutters >

          <v-col
            style="text-align:center;background-color:white"
            v-for="(item,i) in post.photos"
            :key="i"
            :cols="post.photos.length==3&&i==0? 12 : (post.photos.length==1? 12 : 6) "
          >
            <img
              v-show="item.photo!=null"
              :src="`../../storage/images/post_images/${item.photo}`"
              style="width:100%; height:100%"
            />
          </v-col>
        </v-row>
      </template>
      <v-card-actions>
        <v-btn
          text
          :color="post.user_liked.includes(getUserId) ? 'primary':'grey darken-3'"
          small
          @click="toggleLike(post)"
        >
          <v-icon>mdi-thumb-up-outline</v-icon>
          {{post.like?post.like:''}}
        </v-btn>
        <v-btn small text color="grey darken-3" @click=" showCommentThread = !showCommentThread">
          <v-icon>mdi-comment-outline</v-icon>
          {{comments.length?comments.length:''}}
        </v-btn>
        <v-btn small text color="grey darken-3" @click="sharePost">
          <v-icon>mdi-share-variant</v-icon>
          {{post.share?post.share:''}}
        </v-btn>
      </v-card-actions>

      <!-- end post section -->

      <!-- comment and reply section -->
      <v-expand-transition>
        <div v-show="showCommentThread">
          <v-divider></v-divider>
          <v-container>
            <!-- comment thread section -->
            <!-- comment post section -->
            <v-row>
              <v-col cols="1" :sm="1" :offset-sm="1">
                <v-list-item-avatar>
                  <img :src="`../../storage/images/avatar_images/${getUserAvatar}`" />
                </v-list-item-avatar>
              </v-col>
              <v-col cols="9">
                <v-textarea
                  sm
                  :row-height="rowHeight"
                  :rounded="true"
                  v-model="message"
                  :append-icon="message ? 'mdi-send' : '' "
                  :auto-grow="true"
                  filled
                  :clearable="clearable"
                  placeholder="Comment here..."
                  type="text"
                  @click:append="postComment"
                  @keyup.enter.exact="postComment"
                ></v-textarea>
              </v-col>
            </v-row>
            <!-- end comment post section -->

            <!-- sent comment section -->
            <v-row v-for="comment in comments" :key="comment.id">
              <v-col cols="2" :sm="1" :offset-sm="1">
                <v-list-item-avatar>
                  <img :src="`../../storage/images/avatar_images/${comment.avatar_image}`" />
                </v-list-item-avatar>
              </v-col>
              <v-col cols="9">
                <v-card class="d-inline-flex pa-2 rounded-xl" depressed outlined >
                  <div>
                    <span class="title">{{comment.username}}</span>
                    <span>{{comment.created_at| formatDate}}</span>
                    <!-- edit comment section  -->
                    <v-edit-dialog
                       scrollable
                      :return-value.sync="comment.comment"
                      large
                      persistent
                      @save="updateComment(comment)"
                    >
                      <v-chip style="background-color:#EAEAEA" class="ma-3 pa-4">{{comment.comment}}</v-chip>
                      <template v-slot:input>
                        <v-text-field v-model="comment.comment" label="edit comment" autofocus></v-text-field>
                      </template>
                    </v-edit-dialog>
                    <!-- end comment edit section  -->

                    <v-btn small text @click.prevent="setReplyingTo(comment)">
                      <v-icon>mdi-message-reply</v-icon>
                    </v-btn>

                    <!-- comment delete dialog section  -->
                    <v-dialog
                      scrollable
                      v-model="deletionDialog"
                      max-width="36%"
                      persistent
                      :retain-focus="false"
                    >
                      <template v-slot:activator=" { on }">
                        <v-btn
                          small
                          text
                          color="error"
                          v-on="on"
                          v-show="comment.username==getUserName"
                        >
                          <v-icon>mdi-comment-remove-outline</v-icon>
                        </v-btn>
                      </template>
                      <v-card class="rounded-xl">
                        <v-card-title class="headline">Do you really want to delete this comment?</v-card-title>

                        <v-card-text>You will not be able to revert this back.</v-card-text>

                        <v-card-actions>
                          <v-spacer></v-spacer>

                          <v-btn color="green darken-1" text @click="deletionDialog = false">Cancel</v-btn>

                          <v-btn color="red darken-1" @click="destroyComment(comment.id)">Delete</v-btn>
                        </v-card-actions>
                      </v-card>
                    </v-dialog>
                    <!-- end comment delete dialog section  -->

                    <v-btn
                      small
                      text
                      v-show="comment.username==getUserName"
                      color="primary"
                      @click.prevent="editComment(comment)"
                    >
                      <v-icon>mdi-comment-edit-outline</v-icon>
                    </v-btn>
                  </div>
                </v-card>
              </v-col>

              <!-- end sent comment section -->

              <!-- reply section -->

              <v-row
                v-for="reply in comment.replies"
                :key="reply.id"
                style="margin-left:4em;width:100%"
              >
                <v-col cols="2" :sm="1" :offset-sm="1">
                  <v-list-item-avatar>
                    <img :src="`../../storage/images/avatar_images/${reply.avatar_image}`" />
                  </v-list-item-avatar>
                </v-col>
                <v-col cols="9">
                  <v-card class="d-inline-flex pa-2 rounded-xl" shaped depressed outlined>
                    <div>
                      <span class="title">{{reply.username}}</span>
                      <span>{{reply.created_at|formatDate}}</span>
                      <!-- edit comment section  -->
                      <v-edit-dialog
                        scrollable
                        :return-value.sync="reply.comment"
                        large
                        persistent
                        @save="updateComment(reply)"
                      >
                        <v-chip style="background-color:#EAEAEA" class="ma-3 pa-4">{{reply.comment}}</v-chip>
                        <template v-slot:input>
                          <v-text-field v-model="reply.comment" label="edit comment" autofocus></v-text-field>
                        </template>
                      </v-edit-dialog>
                      <!-- end comment edit section  -->

                      <v-btn small text @click.prevent="setReplyingTo(comment)">
                        <v-icon>mdi-message-reply</v-icon>
                      </v-btn>

                      <!-- reply delete dialog section  -->
                      <v-dialog
                        scrollable
                        v-model="deletionDialogReply"
                        max-width="36%"
                        persistent
                        :retain-focus="false"
                      >
                        <template v-slot:activator=" { on }">
                          <v-btn
                            small
                            text
                            color="error"
                            v-on="on"
                            v-show="reply.username==getUserName"
                          >
                            <v-icon>mdi-comment-remove-outline</v-icon>
                          </v-btn>
                        </template>
                        <v-card class="rounded-xl">
                          <v-card-title class="headline">Do you really want to delete this comment?</v-card-title>

                          <v-card-text>You will not be able to revert this back.</v-card-text>

                          <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn
                              color="green darken-1"
                              text
                              @click="deletionDialogReply = false"
                            >Cancel</v-btn>

                            <v-btn color="red darken-1" @click="destroyComment(reply.id)">Delete</v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-dialog>
                      <!-- end reply delete dialog section  -->

                      <v-btn
                        small
                        text
                        v-show="reply.username==getUserName"
                        color="primary"
                        @click.prevent="editComment(reply)"
                      >
                        <v-icon>mdi-comment-edit-outline</v-icon>
                      </v-btn>
                    </div>
                  </v-card>
                </v-col>
              </v-row>
              <!-- post comment reply section -->
              <v-row v-show="comment.id==replyingTo.id" style="margin-left:.5em">
                <v-col cols="1" :sm="1" :offset-sm="1">
                  <v-list-item-avatar>
                    <img :src="`../../storage/images/avatar_images/${getUserAvatar}`" />
                  </v-list-item-avatar>
                </v-col>
                <v-col cols="9">
                  <v-textarea
                    sm
                    :outlined="true"
                    :row-height="rowHeight"
                    :rounded="true"
                    v-model="message"
                    :append-icon="message ? 'mdi-send' : '' "
                    :auto-grow="true"
                    filled
                    :clearable="clearable"
                    label="your reply here"
                    type="text"
                    @click:append="postComment"
                    @keyup.enter="postComment"
                    :append-outer-icon="'mdi-close-circle'"
                    @click:append-outer="unsetReplyingTo"
                  ></v-textarea>
                </v-col>

                <!-- post comment reply section -->
              </v-row>
              <!-- end reply section -->
            </v-row>

            <!-- end comment thread section -->
          </v-container>
        </div>
      </v-expand-transition>
    </v-card>
    <!-- comment and reply section -->
    <!-- post edit dialog -->
    <v-row justify="center">
      <v-dialog v-model="editPostDialog" max-width="500px" srollable>
        <v-card  class="rounded-xl">
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
                :row-height="7"
                rounded
                :placeholder="`What is going on, ${getUserName} ?`"
              ></v-textarea>
            </v-col>
          </v-row>

          <v-flex xs12 class="text-xs-center text-sm-center text-md-center text-lg-center mb-3">
            <v-row no-gutter>
              <v-col
                v-for="(item, i) in imageUrl"
                :key="i"
                :cols="post.photos.length==3&&i==0? 12 : (post.photos.length==1? 12 : 6) "
              >
                <v-img
                  :src="`../../storage/images/post_images/${item.photo}`"
                  alt="input image"
                  height="100%"
                  width="100%"
                >
                  <v-row :justify="'end'" class="mr-4" v-show="i==0">
                    <v-btn icon dark @click="$vuetify.theme.dark = !$vuetify.theme.dark">
                      <v-icon>mdi-plus-circle</v-icon>
                    </v-btn>
                    <v-btn icon dark @click="deletePhoto(post)">
                      <v-icon>mdi-close-circle</v-icon>
                    </v-btn>
                  </v-row>
                </v-img>
              </v-col>
            </v-row>
          </v-flex>
          <v-row :justify="'end'" class="mb-3 mr-7">
            <v-btn text color="primary">
              <v-icon>mdi-gif</v-icon>
            </v-btn>
            <v-btn text color="primary" @click="dummy">
              <v-icon>mdi-image-outline</v-icon>
            </v-btn>
            <input
              id="ImageInput"
              type="file"
              style="display: none"
              ref="image"
              accept="image/*"
              @change="dummy"
              multiple
            />
            <v-btn
              rounded
              style="background-image:linear-gradient(to left,#3F6393,#0697F2);color:white"
              @click="save(post)"
            >Save</v-btn>
          </v-row>
          <div style="height:1rem"></div>
        </v-card>
      </v-dialog>
    </v-row>
    <!-- end post edit dialog -->
  </div>
</template>

<script>
export default {
  props: ["post"],
  data: () => ({
    showCommentThread: false,
    hint: " comment what is right and good.",
    label: "username",
    placeholder: "comment here",
    rowHeight: 1,
    clearable: true,
    message: "",
    replyingTo: {},
    comments: [],
    yes: false,
    deletionDialog: false,
    deletionDialogReply: false,
    editPostDialog: false,
    justify: "end",
    imageUrl: {},
    caption: "",
    liked: false,
    photoTobeDeleted:[],
    // commentItems:[
    //     {title:'Reply',icon:'mdi-message-reply',menuAction:action=>this.setReplyingTo(action)}
    // ]


  }),
  methods: {
      save(post){
          console.log("entity_id"+post.entity_id)
        axios.post('post/edit',{
            entity_id:post.entity_id,
            photo_id:this.photoTobeDeleted,
            caption:this.caption,
        })
            .then(() => {
            Fire.$emit("AfterCreate");

            toast.fire({
              icon: "success",
              title: "Contract Created in successfully"
            });
            this.$Progress.finish();
          })
            .catch(error => {
              toast.fire({
                icon: "error",
                title: "There is problem in updating, try again"
              });

            this.$Progress.fail();
          });


      },
       editPost(post) {
        this.caption = post["photos"][0]["caption"];
        this.imageUrl = post.photos;
      this.editPostDialog = true;

    },
      deletePhoto(post){
          this.photoTobeDeleted = post.photos.map(function(item){
              return item.id
          })
        this.imageUrl ={};
        //   axios.post('/post/delete/photo',{params:{
        //       user_id:item.user_id,
        //       post_id:entity_id,
        //       photo:item.photo
        //   }
        //   })
        //        .then(response=>{
        //             alert('successfully delete')
        //        })
        //        .catch(error=>{
        //            alert(error)
        //        })
      },
    toggleLike(post) {
      axios
        .post(`post/${this.post.entity_id}/like`, {
          entity_id: this.post.entity_id
        })
        .then(response => {
          var res = response.data;
          if (res == "exist") {
            this.liked = false;
            this.post.like = this.post.like -1;
          } else {
            this.liked = true;
            this.post.like = this.post.like +1;
          }
        })
        .catch(error => {
          alert(error);
        });
    },
    postComment() {
      axios
        .post(`post/${this.post.entity_id}/comments`, {
          parent_id: this.replyingTo.id ? this.replyingTo.id : null,
          message: this.message
        })
        .then(response => {
          this.message = "";
          console.log("message var cleared");
          //   if (!this.replyingTo.id) {
          //     this.comments.push(response.data.data);
          //     console.log(this.comments);
          //   } else {
          //     this.replyingTo.replies.push(response.data.data);
          //   }
        });
      // .finally(this.fetchComments());
    },
    setReplyingTo(comment) {
      this.replyingTo = {};
      this.replyingTo = comment;
      //   console.log(comment)
      console.log(this.replyingTo);
    },
    unsetReplyingTo() {
      this.replyingTo = {};
      this.fetchComments();
    },
    fetchComments() {
      axios.get(`post/${this.post.entity_id}/comments`).then(response => {
        this.comments = response.data.data;
      });
      console.log("user id: " + this.getUserId);
    },
    updateComment(comment) {
      axios
        .patch("post/comment/" + comment.id, {
          comment: comment.comment
        })
        .then(response => {
          console.log(updated);
        })
        .catch(error => {
          console.log(error);
        })
        .finally(this.fetchComments());
    },
    destroyComment(id) {
      axios
        .delete("post/comment/" + id)
        .then(response => {
          console.log(response.data);
        })
        .catch(error => {
          console.log(error);
          this.deletionDialog = false;
        })
        .finally(
          (this.yes = false),
          (this.deletionDialog = false),
          (this.deletionDialogReply = false),
          this.fetchComments()
        );
    },
    deletePost(id) {
      // console.log(this.$parent)
     swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes"
        })
        .then(result => {
          // Send request to the server
          if (result.value) {
            axios
              .delete("/post/delete/" + id)
              .then(() => {
                swal.fire("Deleted!", "Your Post has been deleted.", "success");
                (this.$parent.$options.parent.posts = []),
                this.$parent.$options.parent.fetchPost();
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wrong.", "warning");
              });
          }
        });
    },

    sharePost() {
      axios
        .post(`share/${this.post.entity_id}`)
        .then(response => {
          toast.fire({
            icon: "success",
            title: "You just shared a post"
          });
        })
        .catch(error => {
          alert(error);
        });
    },

    dummy() {
      alert("dummy clicked");
    }
  },
  created() {
    this.fetchComments();
  },
  mounted() {
    Echo.connector.socket.on("subscription_error", (channel, data) => {
      console.log(channel, data);
    });
    Echo.private("comment").listen(".NewComment", e => {
      console.log("comment sennt:" + e);
      this.comments.push(e);
    });
  },
  computed: {
    getUserId() {
      return this.$store.getters.getUserId;
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
    gridMine() {
      length = this.post.photos.length;
      if (length == 1) {
        return 12;
      } else if (length == 2) {
        return 6;
      } else {
        if (i == 1) {
          return 12;
        }
        if (i == 2 || i == 3) {
          return 6;
        }
      }
    }
  }
};
</script>

<style lang="css" scoped>
.my-border-radius {
  border-radius: 3vh !important;
}
</style>
