import axios from "axios";

export default {
  register(email, login, firstName, lastName, firstPassword, secondPassword) {
    return axios.post("/register", {
      email: email,
      login: login,
      firstName: firstName,
      lastName: lastName,
      plainPassword: {
        first: firstPassword,
        second: secondPassword
      }
    });
  },
  login(login, password) {
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
