<template>
  <v-card min-width="25%" overflow min-height="100%" style="position:fixed; top:0; ">
    <v-toolbar>
      <v-toolbar-title class="title">Messages</v-toolbar-title>

      <v-spacer></v-spacer>

      <v-btn icon>
        <v-icon>mdi-dots-vertical</v-icon>
      </v-btn>
    </v-toolbar>
     <v-text-field
     dense
     style="width:90%;"
     class="mt-4 ml-4"
      v-on:keyup.enter="search"
      v-model="searchData"
      placeholder="Search"
      prepend-inner-icon="mdi-magnify"
      filled
      rounded
      clearable

    ></v-text-field>
    <ul>
      <li
        v-for="contact in sortedContacts"
        :key="contact.id"
        @click="selectContact(contact)"
        :class="{ 'selected': contact == selected }"
      >
        <div>
          <v-avatar>
            <img :src="`storage/images/avatar_images/${contact.avatar_image}`" :alt="contact.name" />
          </v-avatar>
          <v-badge v-if="contact.unread" :content="contact.unread" overlap></v-badge>
        </div>
        <div class="contact">
          <p class="name">{{ contact.name }}</p>
          <!-- <p class="email">{{ contact.email }}</p> -->
        </div>
      </li>
    </ul>
  </v-card>
</template>

<script>
export default {
  props: {
    contacts: {
      type: Array,
      default: []
    }
  },
  data() {
    return {
        users:[],
        searchData:"",
      selected: this.contacts.length ? this.contacts[0] : null
    };
  },
  methods: {
       search(){
            axios.get('findUser/',{
              params:{
                q:this.searchData
              }
            })
            .then(response=>{
              console.log(response.data);
              this.users =response.data.data;

            })
            .catch(error=>{
              alert(error)
            })
          },
    selectContact(contact) {
      this.selected = contact;

      this.$emit("selected", contact);
    }
  },
  computed: {
    sortedContacts() {
      return _.sortBy(this.contacts, [
        contact => {
          if (contact == this.selected) {
            return Infinity;
          }

          return contact.unread;
        }
      ]).reverse();
    }
  }
};
</script>

<style lang="scss" scoped>
.contacts-list {
  flex: 2;
  max-height: 100%;
  height: 600px;
  overflow: scroll;
  border-left: 1px solid #a6a6a6;

  ul {
    list-style-type: none;
    padding-left: 0;

    li {
      display: flex;
      padding: 2px;
      border-bottom: 1px solid #aaaaaa;
      height: 80px;
      position: relative;
      cursor: pointer;

      &.selected {
        background: #dfdfdf;
      }

      span.unread {
        background: #82e0a8;
        color: #fff;
        position: absolute;
        right: 11px;
        top: 20px;
        display: flex;
        font-weight: 700;
        min-width: 20px;
        justify-content: center;
        align-items: center;
        line-height: 20px;
        font-size: 12px;
        padding: 0 4px;
        border-radius: 3px;
      }

      .avatar {
        flex: 1;
        display: flex;
        align-items: center;

        img {
          width: 35px;
          border-radius: 50%;
          margin: 0 auto;
        }
      }

      .contact {
        flex: 3;
        font-size: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: center;

        p {
          margin: 0;

          &.name {
            font-weight: bold;
          }
        }
      }
    }
  }
}
</style>
