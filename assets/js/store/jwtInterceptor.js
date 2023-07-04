import jwtInterceptor  from '../shared/jwtInterceptor.js';

const actions = {
  async getAllTodos({commit}){
    let response = await jwtInterceptor.get('http://localhost:3000/todos');
    if(response && response.data){
      commit('setTodos', response.data);
    }
  }
};
