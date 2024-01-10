<template>
  <div>
    <form @submit.prevent="handleEditNewTask">
      <div class="p-3 flex-1">
          <input class="block w-full px-2 py-1 text-lg border-b border-blue-800 rounded" type="text"
            placeholder="Enter a title" v-model.trim="newTask.title" />
          <textarea class="mt-3 p-2 block w-full p-1 border text-sm rounded" rows="2"
            placeholder="Add a description (optional)" v-model.trim="newTask.description"></textarea>
        <div v-show="errorMessage">
          <span class="text-xs text-red-500">
            {{ errorMessage }}
          </span>
        </div>
      </div>
      <div class="p-3 flex justify-between items-end ">
        <button type="submit" class="text-sm bg-white hover:bg-gray-100 text-black-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
          Update Card
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    task: Object
  },
  data() {
    return {
      newTask: {
        title: "",
        description: ""
      },
      task_id: null,
      errorMessage: ""
    };
  },
  mounted() {
    this.newTask.title = this.task.title;
    this.newTask.description = this.task.description;
    this.task_id = this.task.id;
  },
  methods: {
    handleEditNewTask() {
      // Basic validation so we don't send an empty task to the server
      if (!this.newTask.title) {
        this.errorMessage = "The title field is required";
        return;
      }

      // Send new task to server
      axios
        .put(`/tasks/${this.task_id}`, this.newTask)
        .then(res => {
          // Tell the parent component we've added a new task and include it
          this.$emit("task-updated", res.data);
        })
        .catch(err => {
          // Handle the error returned from our request
          this.handleErrors(err);
        });
    },
    handleErrors(err) {
      if (err.response && err.response.status === 422) {
        // We have a validation error
        const errorBag = err.response.data.errors;
        if (errorBag.title) {
          this.errorMessage = errorBag.title[0];
        } else if (errorBag.description) {
          this.errorMessage = errorBag.description[0];
        } else {
          this.errorMessage = err.response.message;
        }
      } else {
        // We have bigger problems
        console.log(err.response);
      }
    }
  }
};
</script>