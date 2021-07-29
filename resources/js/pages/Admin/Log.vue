<template>
    <div class="flex-center my-container">

        <v-row >
            <v-col cols="4" style="margin-right:-1.5em">
        <v-simple-table>
            <template v-slot:top>
                <div class="my-bg flex-center pt-3 pb-3">
                    <v-avatar width="30" height="30">
                      <img :src="`../../../storage/images/avatar_images/${avatar}`" alt="avatar" >
                  </v-avatar>
                <h4>  {{username}}'s log</h4>
                </div>
            </template>
      <template v-slot:default>

        <tbody>
          <tr v-for="item in logs" :key="item.id" class="my-tr-bg" @click="getLog(item.subject_id)">
              <td>
                  <v-icon color="pink">mdi-delete-outline</v-icon>
              </td>
            <td>{{ item.description }}</td>
            <td>{{ item.created_at|formatDate}}</td>
          </tr>
        </tbody>
      </template>
    </v-simple-table>
    </v-col>
    <v-col cols="8">
        <template v-if="deleted" >
            <div class="my-bg flex-center">
            <div v-for="d_item in deleted" :key="d_item.id" style="width:50%;height:10%">
                  <v-col  >
                <v-col cols="2" :sm="1" :offset-sm="1">
                <v-list-item-avatar>
                  <img :src="`../../storage/images/avatar_images/${d_item.avatar_image}`" />
                </v-list-item-avatar>
              </v-col>
              <v-col cols="9">
                <v-card class="d-inline-flex pa-2 rounded-xl" depressed outlined >
                  <div>
                    <span class="title">{{d_item.username}}</span>
                    <span>{{d_item.created_at| formatDate}}</span>
                    <!-- edit comment section  -->
                    <v-edit-dialog
                       scrollable
                      :return-value.sync="d_item.comment"
                      large
                      persistent
                      @save="updateComment(comment)"
                    >
                      <v-chip style="background-color:#EAEAEA" class="ma-3 pa-4">{{d_item.comment}}</v-chip>
                      <template v-slot:input>
                        <v-text-field v-model="d_item.comment" label="edit comment" autofocus></v-text-field>
                      </template>
                    </v-edit-dialog>
                    </div>
                    </v-card>
            </v-col>
            </v-col>

<v-card  class="my-border-radius" >
      <v-list-item>
        <v-list-item-avatar color="grey">
          <img :src="`../../../storage/images/avatar_images/${d_item.post.avatar_image}`" />
        </v-list-item-avatar>
        <v-list-item-content>
          <v-list-item-title class="title">{{d_item.post.username}}</v-list-item-title>
          <v-list-item-subtitle>{{d_item.post.created_at|formatDate}}</v-list-item-subtitle>
        </v-list-item-content>
        <v-menu v-if="'getUserName'==d_item.post.username"
         transition="slide-y-transition"
         bottom
        >
          <template v-slot:activator="{on}">
            <v-btn icon v-on="on">
              <v-icon>mdi-dots-vertical</v-icon>
            </v-btn>
          </template>
          <v-list rounded>
            <v-list-item>
              <v-btn color="error" text rounded @click="deletePost(d_item.post.entity_id)">
                <v-icon>mdi-delete-outline</v-icon>
              </v-btn>
            </v-list-item>
            <v-list-item>
              <v-btn color="primary" rounded text @click="editPost(d_item.post)">
                <v-icon>mdi-file-edit-outline</v-icon>
              </v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-list-item>

      <template v-if="d_item.post.parent!==null">
        <v-flex align-center>
          <v-card width="100%" class="my-border-radius" outlined>
            <v-list-item>
              <v-list-item-avatar color="grey">
                <img :src="`../../../storage/images/avatar_images/${d_item.post.parent.avatar_image}`" />
              </v-list-item-avatar>
              <v-list-item-content>
                <v-list-item-title class="title">{{d_item.post.parent.username}}</v-list-item-title>
                <v-list-item-subtitle>{{d_item.post.parent.created_at|formatDate}}</v-list-item-subtitle>
              </v-list-item-content>
            </v-list-item>
            <v-row no-gutters>
              <v-col
                style="text-align:center;background-color:white"
                v-for="(item,i) in d_item.post.parent.photos"
                :key="i"
                :cols="d_item.post.parent.photos.length==3&&i==0? 12 : (d_item.post.parent.photos.length==1? 12 : 6) "
              >
                <img
                  :src="`../../../storage/images/post_images/${item.photo}`"
                  style="width:100%; height:100%"
                />
              </v-col>
            </v-row>
          </v-card>
        </v-flex>
      </template>

      <template v-if="d_item.post.parent===null">
        <v-card-text>{{d_item.post.photos[0].caption}}</v-card-text>
        <v-row no-gutters>
          <v-col
            style="text-align:center;background-color:white"
            v-for="(item,i) in d_item.post.photos"
            :key="i"
            :cols="d_item.post.photos.length==3&&i==0? 12 : (d_item.post.photos.length==1? 12 : 6) "
          >
            <img :src="`../../../storage/images/post_images/${item.photo}`" style="width:100%; height:100%" />
          </v-col>
        </v-row>
      </template>
      <v-card-actions>
        <v-btn
          text
          :color="d_item.post.user_liked.includes('getUserId') ? 'primary':'grey darken-3'"
          small
          @click="toggleLike"
        >
          <v-icon>mdi-thumb-up-outline</v-icon>
          {{d_item.post.like?d_item.post.like:''}}
        </v-btn>
        <v-btn small text color="grey darken-3" @click=" showCommentThread = !showCommentThread">
          <v-icon>mdi-comment-outline</v-icon>

        </v-btn>
        <v-btn small text color="grey darken-3" >
          <v-icon>mdi-share-variant</v-icon>
          {{d_item.post.share?d_item.post.share:''}}
        </v-btn>
      </v-card-actions>
      </v-card>
            </div>

            </div>
        </template>
    </v-col>
    </v-row>
    </div>

</template>

<script>
import router from '../../router'

    export default {
        computed: {


        },
        data() {
            return {
                username:'',
                id:0,
                logs:[],
                avatar:'',
                deleted:[]
            }
        },
        methods: {
            getLog(id){
                axios.get('admin/log/deleted/'+id)
                     .then(response=>{
                         this.deleted = response.data.data
                     })
            }
        },
          created() {
              this.username = this.$route.params.name;
              this.avatar =this.$route.params.avatar;
              console.log(this.avatar);
        this.id = this.$route.params.id;

        axios.get('admin/log/'+this.id)
             .then(response=>{
                 this.logs = response.data

             })
             .catch(error=>{
                 alert(error)
             })
    },


    }

</script>

<style lang="scss" scoped>

.font-size{
    font-size:1.2em;
}
.my-container{
    margin-left:7em;
}
.sub-container{
    background-color:#EAEAEA;
    height:100%;
    width:99%;
}
.my-bg{
    background-color:#EAEAEA;
}
.my-tr-bg{
    background-color:#F8F8F7;
}
</style>
