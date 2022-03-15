import axios from "axios";

export default {
  login(login, password) {
    return axios.post("/login", {
      email: login,
      password: password
    });
  }
}
