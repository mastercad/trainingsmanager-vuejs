import axios from 'axios';

const BASE_URL = 'http://localhost:58000';

function upload(formData) {
  const url = `${BASE_URL}/api/devices/images`;
  return axios.post(url, formData);
  // get data
//    .then(x => x.data)
  // add url field
//    .then(x => x.map(img => Object.assign({},
//      img, { url: `${BASE_URL}/images/${img.id}` })));
}

export { upload };
