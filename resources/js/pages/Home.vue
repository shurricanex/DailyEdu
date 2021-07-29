<template>
<div style="width:48%; margin:auto;">
  <Scrollable @at-the-bottom="fetchPost(posts.length)">
    <v-card class="my-border-radius" >
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
          <v-col v-for="(item, i) in imageUrl" :key="i" :cols="myGrid[i]"  >
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
        accept="image/*,video/*"
        @change="onFilePicked"
        multiple
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
</template>

<script>
import Post from "../components/Post.vue";
import Scrollable from "../components/Scrollable.vue";

export default {
  component: {
    Post,
    Scrollable,
    Comment
  },

  data() {
    return {
      name: "",
      posts: [],
      title: "Image Upload",
      dialog: false,
      imageName: [],
      imageUrl: [],
      imageFile: [],
      caption: "",
      myGrid: []
    };
  },

  methods: {
    fetchPost(offset = 0) {
      this.loading = true;

      axios
        .get("/posts", {
          params: {
            offset: offset
          }
        })
        .then(response => {
          this.posts = this.posts.concat(response.data.data);
          console.log(response.data.data);
        })

        .finally(response => (this.loading = false));
    },

    postPost() {
      var fd = new FormData();

      this.imageFile.forEach(file => fd.append("image[]", file));
      fd.append("caption", this.caption);
      axios
        .post("/post", fd)
        .then(response => {
            this.myGrid = [];
            this.posts =[];
           this.fetchPost(0)
        })
        .catch(error => {
          console.log(error);
        })
        .finally(
          (this.imageName = []),
          (this.imageFile = []),
          (this.caption = []),
          (this.imageUrl = []),
          this.myGrid = []
        );
    },

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

    resetPickedImage() {
      this.imageUrl = [];
      this.imageFile = [];
      this.imageName = [];
      this.myGrid = [];

    }
  },
  created() {
    //    console.log('token:'+this.$store.getters.getToken);
    this.$store.dispatch("retrieveName").then(response => {
      this.name = response.data.name;
    });
    this.fetchPost();
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
};
</script>

<style lang="css" scoped>
.my-border-radius {
  border-radius: 4vh !important;
}

</style>
