<template>
    <form
      class="relative mb-3 flex flex-col justify-between bg-white rounded-md shadow overflow-hidden"
      @submit.prevent="handleAddNewStatus"
    >
      <div class="p-3 flex-1">
        <input
          class="block w-full px-2 py-1 text-lg border-b border-blue-800 rounded"
          type="text"
          placeholder="Enter a title for Column"
          v-model.trim="newStatus.title"
        />
        <!-- <textarea
          class="mt-3 p-2 block w-full p-1 border text-sm rounded"
          rows="2"
          placeholder="Add a Slug for Column (optional)"
          v-model.trim="newStatus.slug"
        ></textarea> -->
        <div v-show="errorMessage">
          <span class="text-xs text-red-500">
            {{ errorMessage }}
          </span>
        </div>
      </div>
      <div class="mb-10 ml-3">
        <!-- <button
          @click="$emit('status-canceled')"
          type="reset"
          class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
        >
          cancel
        </button> -->
        <button
          type="submit"
          class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
        >
          Create New Column
        </button>
      </div>
    </form>
  </template>
  
  <script>
  export default {
    data() {
      return {
        newStatus: {
          title: "",
          slug: ""
        },
        errorMessage: ""
      };
    },
    methods: {
      handleAddNewStatus() {
        // Basic validation so we don't send an empty task to the server
        if (!this.newStatus.title) {
          this.errorMessage = "The title field is required";
          return;
        }
  
        // Send new task to server
        axios
          .post("/statuses", this.newStatus)
          .then(res => {
            // Tell the parent component we've added a new task and include it
            this.$emit('status-canceled',false)
            this.newStatus.title ="";
            this.newStatus.slug = "";
            this.$emit("status-added", res.data);
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
          } else if (errorBag.slug) {
            this.errorMessage = errorBag.slug[0];
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
  