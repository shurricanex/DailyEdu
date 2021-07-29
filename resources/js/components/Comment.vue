<template>

     <v-container>
            <!-- comment thread section -->
            <!-- comment post section -->
            <v-row>
              <v-col cols="1" :sm="1" :offset-sm="1">
                <v-list-item-avatar>
                  <img :src="`storage/images/avatar_images/${getUserAvatar}`" />
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
                  label="comment here..."
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
                  <img :src="`storage/images/avatar_images/${comment.avatar_image}`" />
                </v-list-item-avatar>
              </v-col>
              <v-col cols="9">
                <v-card class="d-inline-flex pa-2" depressed outlined>
                  <div>
                    <span class="title">{{comment.username}}</span>
                    <span>{{comment.created_at| formatDate}}</span>
                    <!-- edit comment section  -->
                    <v-edit-dialog
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
                      <v-card>
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
                    <img :src="`storage/images/avatar_images/${reply.avatar_image}`" />
                  </v-list-item-avatar>
                </v-col>
                <v-col cols="9">
                  <v-card class="d-inline-flex pa-2" shaped depressed outlined>
                    <div>
                      <span class="title">{{reply.username}}</span>
                      <span>{{reply.created_at|formatDate}}</span>
                      <!-- edit comment section  -->
                      <v-edit-dialog
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
                        <v-card>
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
                    <img :src="`storage/images/avatar_images/${getUserAvatar}`" />
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
</template>

<script>
    export default {
        props:["post"],
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
    deletionDialogReply: false

    // commentItems:[
    //     {title:'Reply',icon:'mdi-message-reply',menuAction:action=>this.setReplyingTo(action)}
    // ]
  }),
  methods: {
    postComment() {
      axios
        .post(`post/${this.post.entity_id}/comments`, {
          parent_id: this.replyingTo.id ? this.replyingTo.id : null,
          message: this.message
        })
        .then(response => {
          this.message = "";
          if (!this.replyingTo.id) {
            this.comments.push(response.data.data);
            console.log(this.comments);
          } else {
            this.replyingTo.replies.push(response.data.data);
          }
        })
        .finally(this.fetchComments());
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
    }
  },
  created() {
    this.fetchComments();
  },
  computed: {
    getUserName() {
      return this.$store.getters.getUserName;
    },
    getUserType() {
      return this.$store.getters.getUserType;
    },
    getUserAvatar() {
      return this.$store.getters.getUserAvatar;
    }
  }
    }
</script>

<style lang="scss" scoped>

</style>
