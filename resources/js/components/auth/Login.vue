<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 text-center">
        <div>
          <div class="p-4">
            <h1 class="mt-3" style="font-size:6em;">Login</h1>
          </div>
          <hr />

          <div class="card-body">
            <form method="POST" action="#" @submit.prevent="login">
              <div class="col-md-11 offset-md-1">
                <div class="form-group">
                  <div v-if="successMessage" class="success-message">{{ successMessage }}</div>
                  <div v-if="serverError" class="server-error col-md-11">{{ serverError }}</div>
                  <label for="email" class="col-form-label text-md-left">
                    <h4>Username</h4>
                  </label>

                  <div>
                    <input
                      id="email"
                      type="text"
                      v-model="username"
                      class="col-md-11"
                      name="email"
                      required
                      autocomplete="email"
                      autofocus
                    />
                  </div>
                </div>

                <div class="form-group">
                  <label for="password" class="col-form-label text-md-left">
                    <h4>Password</h4>
                  </label>

                  <div>
                    <input
                      id="password"
                      type="password"
                      class="col-md-11"
                      name="password"
                      required
                      autocomplete="current-password"
                      v-model="password"
                    />
                  </div>
                </div>

                <div class="form-group mb-0 mt-5">
                  <div class>
                    <button
                      type="submit"
                      class="btn col-md-11"
                      :disabled="loading"
                      style="background-image:linear-gradient(to left,#3F6393,#0697F2)"
                    >
                      <div class="lds-ring-container" v-if="loading">
                        <div class="lds-ring">
                          <div></div>
                          <div></div>
                          <div></div>
                          <div></div>
                        </div>
                      </div>Login
                    </button>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-6 offset-md-3"></div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "Login",
  props: {
    dataSuccessMessage: {
      type: String
    }
  },
  data() {
    return {
      username: "",
      password: "",
      serverError: "",
      successMessage: this.dataSuccessMessage,
      loading: false
    };
  },
  methods: {
    login() {
      this.loading = true;
      this.$store
        .dispatch("retrieveToken", {
          username: this.username,
          password: this.password
        })
        .then(response => {
          this.loading = false;
          this.$store.dispatch("retrieveName").then(response1 => {
            var userType = response1.data.type;
            if (userType == "admin") {
              this.$router.push({ name: "User" });
            } else {
              this.$router.push({ name: "Home" });
            }
          });
        })
        .catch(error => {
          this.loading = false;
          this.serverError = error.response.data;
          this.password = "";
          this.successMessage = "";
        });
    }
  }
};
</script>
<style scoped>
input {
  border-radius: 0.5em !important;
  width: 17em;
  height: 4em !important ;
  outline: none;
  border: 1px solid #dddddd !important;
}
button {
  border-radius: 0.5em !important;
  width: 17em;
  height: 4em !important;
  outline: none !important;
}

input[type="text"]:focus,
input[type="password"]:focus {
  box-shadow: none;
  outline: none;
  border: 1px solid rgba(81, 203, 238, 1) !important;
}
button:hover {
  -webkit-transform: scale(1.02);
  transform: scale(1.02);
  box-shadow: 0 7px 17px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
}
</style>
