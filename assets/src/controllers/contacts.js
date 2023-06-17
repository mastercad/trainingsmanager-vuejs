import axios from "axios";

export default {
  create(firstName, lastName, emailAddress) {
    return axios.post("/api/contacts", {
      firstName: firstName,
      lastName: lastName,
      emailAddress: emailAddress
    });
  },
  update(id, firstName, lastName, emailAddress) {
    return axios.put("/api/contacts/"+id, {
      firstName: firstName,
      lastName: lastName,
      emailAddress: emailAddress
    });
  },
  delete(id) {
    return axios.delete("/api/contacts/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/contacts");
  }
};
