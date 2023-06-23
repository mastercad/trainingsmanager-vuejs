import axios from "axios";

export default {
  create(optionValue, exerciseOption, trainingPlanXExercise) {
    return axios.post("/api/training_plan_x_exercise_options", {
      optionValue: optionValue,
      exerciseOption: exerciseOption,
      trainingPlanXExercise: trainingPlanXExercise
    });
  },
  update(trainingPlanXExerciseOption, optionValue, exerciseOption, trainingPlanXExercise) {
    return axios.put("/api/training_plan_x_exercise_options/"+trainingPlanXExerciseOption.id, {
      optionValue: optionValue,
      exerciseOption: exerciseOption,
      trainingPlanXExercise: trainingPlanXExercise
    });
  },
  delete(id) {
    return axios.delete("/api/training_plan_x_exercise_options/"+id, {
    });
  },
  findAll() {
    return axios.get("/api/training_plan_x_exercise_options");
  }
};
