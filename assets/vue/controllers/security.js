import axios from "axios";

export default {
  login(login, password) {
//    return axios.post("/login", {
    return axios.post("/api/login_check", {
      email: login,
      password: password
    });
  },
  refresh(refreshToken) {
    return axios.post("/api/token/refresh", {
      refresh_token: refreshToken
    });
  },
  logout() {
    return axios.post("/logout");
  }
};
