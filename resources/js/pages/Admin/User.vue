<template>
  <div>
    <div class="flex-center mt-10 ml-8">
      <v-data-table
        :headers="headers"
        :items="users"
        sort-by="name"
        class="elevation-1"
        style="width:80%"
        :server-items-length="pagination.total"
        hide-default-footer
        @page-count="pageCount = $event"
        :page.sync="pagination.current"
      >
        <template v-slot:top>
          <v-toolbar flat>
            <v-toolbar-title>Users</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-spacer></v-spacer>

            <v-dialog v-model="dialog" max-width="500px" class="rounded-xl">
              <template v-slot:activator="{ on }">
                <v-btn color="#00A0E9 " rounded dark class="mb-2" v-on="on">Add User</v-btn>
              </template>
              <v-stepper :value="e1" class="rounded-xl">
                <v-stepper-content :step="n" v-for="n in steps" :key="`${n}-content`">
                  <v-card>
                    <v-card-title>
                      <span class="headline">{{ formTitle }}</span>
                      <v-spacer></v-spacer>
                      <v-btn color="primary" @click="nextStep(n)">{{n==1? 'Import':'Back'}}</v-btn>
                    </v-card-title>
                    <v-card-text v-if="n==1">
                      <v-container>
                        <v-row>
                          <v-col cols="12" sm="6" md="4">
                            <v-avatar size="36px">
                              <img
                                label="avatar"
                                v-if="editedItem.avatar_image"
                                alt="Avatar"
                                :src="`../../storage/images/avatar_images/${editedItem.avatar_image}`"
                              />
                              <v-icon v-else color="primary" v-text="'mdi-account-circle-outline'"></v-icon>
                            </v-avatar>
                          </v-col>
                          <v-col cols="12" sm="6" md="4">
                            <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="4">
                            <v-text-field v-model="editedItem.level" label="Level"></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="4">
                            <v-text-field v-model="editedItem.status" label="Status"></v-text-field>
                          </v-col>
                          <v-col cols="12" sm="6" md="6">
                            <v-text-field v-model="editedItem.email" label="Email"></v-text-field>
                          </v-col>
                        </v-row>
                      </v-container>
                    </v-card-text>
                    <v-card-text v-if="n==2">
                      <v-file-input
                        id="import-file"
                        v-model="import_file"
                        type="file"
                        accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                      ></v-file-input>
                    </v-card-text>
                    <v-card-actions>
                      <v-spacer></v-spacer>
                      <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                      <v-btn color="blue darken-1" v-show="n==1" @click="save">Save</v-btn>
                      <v-btn
                        color="blue darken-1"
                        :disable="import_file"
                        v-show="n==2"
                        @click="importFile"
                      >Upload</v-btn>
                    </v-card-actions>
                  </v-card>
                </v-stepper-content>
              </v-stepper>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn icon color="green" @click="navigateTo(item)">
            <v-icon class="mr-2">mdi-file-eye-outline</v-icon>
          </v-btn>
          <v-btn icon color="primary" @click="editItem(item)">
            <v-icon class="mr-2">mdi-pencil</v-icon>
          </v-btn>
          <v-btn icon color="pink" @click="deleteItem(item)">
            <v-icon>mdi-delete</v-icon>
          </v-btn>
        </template>
        <template v-slot:no-data>
          <v-btn color="primary">Reset</v-btn>
        </template>
        <template v-slot:item.avatar_image="{ item }">
          <v-avatar dark>
            <img :src="`../../storage/images/avatar_images/${item.avatar_image}`" alt="avatar" />
          </v-avatar>
        </template>
      </v-data-table>
    </div>
    <div class="text-center pt-7">
      <v-pagination v-model="pagination.current" :length="pagination.total" @input="getResults"></v-pagination>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      import_file: {},
      steps: 2,
      e1: 1,
      pagination: {
        current: 1,
        total: 0
      },
      userLength: 0,
      page: 1,
      pageCount: 0,
      users: [],
      search: "",
      dialog: false,
      headers: [
        {
          text: "avatar",
          align: "start",
          sortable: false,
          value: "avatar_image"
        },
        { text: "name", value: "name" },
        { text: "level", value: "level" },
        { text: "email", value: "email" },
        { text: "Actions", value: "actions", sortable: false }
      ],
      editedIndex: -1,
      editedItem: {
        id: "",
        avatar_image: "",
        name: "",
        level: "",
        email: "",
        status: ""
      },
      defaultItem: {
        avatar_image: "",
        name: "",
        level: "",
        email: "",
        status: ""
      }
    };
  },
  computed: {
    getSearch() {
      this.users = this.$store.getters.getSearch;
    },
    formTitle() {
      return this.editedIndex === -1 ? "New User" : "Edit User";
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    step(val) {
      if (this.e1 > val) {
        this.e1 = val;
      }
    }
  },

  created() {
    this.fetchUser();
    // this.$store.dispatch("retrieveUsers");
    Fire.$on("AfterCreate", () => {
      this.fetchUser();
    });
  },
  methods: {
    importFile() {
      let fd = new FormData();
      fd.append("import_file", this.import_file);
      axios
        .post("import", fd)
        .then(() => {
          Fire.$emit("AfterCreate");

          toast.fire({
            icon: "success",
            title: "User Imported successfully"
          });
          this.$Progress.finish();
        })
        .catch(error => {
          toast.fire({
            icon: "error",
            title: "Import error"
          });
        });
      this.close();
    },
    nextStep(n) {
      if (n === this.steps) {
        this.e1 = 1;
      } else {
        this.e1 = n + 1;
      }
    },
    getResults(page = 1) {
      axios.get("users?page=" + page).then(response => {
        this.users = response.data.data;
      });
    },
    navigateTo(user) {
      this.$router.push({
        name: "Log",
        params: { id: user.id, avatar: user.avatar_image, name: user.name }
      });
    },
    fetchUser() {
      axios.defaults.headers.common["Authorization"] =
        "Bearer " + this.$store.getters.getToken;
      axios
        .get("users")
        .then(response => {
          this.users = response.data.data;
          this.pagination.current = response.data.meta.current_page;
          this.pagination.total = response.data.meta.last_page;
          console.log(this.users);
        })
        .catch(error => {
          alert(error);
        });
    },

    editItem(item) {
      this.editedIndex = this.users.indexOf(item);
      Object.filter = (obj, predicate) =>
        Object.fromEntries(Object.entries(obj).filter(predicate));
      item = Object.filter(
        item,
        ([key, value]) =>
          key != "followers" && key != "follow" && key != "following"
      );
      console.log(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },

    deleteItem(item) {
      var index = this.users.indexOf(item);
      var id = this.users[index].id;
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
              .delete("/users/" + id)
              .then(() => {
                swal.fire("Deleted!", "Your file has been deleted.", "success");
                Fire.$emit("AfterCreate");
              })
              .catch(() => {
                swal.fire("Failed!", "There was something wrong.", "warning");
              });
          }
        });
    },

    close() {
      this.dialog = false;
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      });
    },

    save() {
      this.$Progress.start();
      if (this.editedIndex === -1) {
        axios
          .post("/users", this.editedItem)

          .then(() => {
            Fire.$emit("AfterCreate");

            toast.fire({
              icon: "success",
              title: "User Created in successfully"
            });
            this.$Progress.finish();
          })
          .catch(error => {
            console.log(error);
            console.log(error.response.status);

            if (error.response.status == "409") {
              toast.fire({
                icon: "error",
                title: "User is already existed"
              });
            }
            this.$Progress.finish();
          });
      } else {
        axios
          .patch("users/" + this.editedItem.id, this.editedItem)
          .then(() => {
            swal.fire("Updated!", "Information has been updated.", "success");
            this.$Progress.finish();
            Fire.$emit("AfterCreate");
          })
          .catch(() => {
            this.$Progress.fail();
          });
      }
      this.close();
    }
  }
};
</script>

<style lang="css" >
.v-data-table th {
  font-size: 1em !important;
  background: #eaeaea;
  border-bottom: none !important;
}
.mytable .v-table tbody tr:not(:last-child) {
  border-bottom: none !important;
}
.theme--light.v-data-table
  tbody
  tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
  background: #ffffff;
}
.mytable .v-table tbody tr:hover {
  background: chartreuse;
}
.theme--light.v-data-table {
  background-color: #f8f8f7;
  color: rgba(0, 0, 0, 0.87);
}
.my-thead {
  background-color: #eaeaea;
}
.v-data-table tbody tr td {
  border-bottom: thin solid white !important;
}
.v-avatar {
  height: 40px !important;
  width: 40px !important;
}
</style>
