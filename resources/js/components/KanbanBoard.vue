<template>
  <div class="relative p-2 flex overflow-x-auto h-full">
    <modal v-if="showModal" @close="showModal = false">
      <h3 slot="header">Update Card</h3>
      <div slot="body">
        <EditTaskFormVue :task="task" v-on:task-updated="handleTaskedit" />
      </div>
    </modal>

    <div class="ml-5 mr-6 flex-shrink-0">
      <div v-show="!showAddStatus" class="flex-1  flex flex-col items-center justify-center">
        <span class="text-gray-600">Add a New Column</span>
        <button
          class="mt-1 text-sm text-sm bg-white hover:bg-gray-100 text-green-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
          @click="showAddStatus = true">
          Click Here
        </button>
      </div>
      <modal v-if="showAddStatus" @close="showAddStatus = false">
        <h3 slot="header">Add Column</h3>
        <div slot="body">
          <AddStatusForm v-on:status-canceled="showAddStatus = false" v-show="showAddStatus"
            :showAddStatus="showAddStatus" v-on:status-added="handleStatusAdded" />
        </div>
      </modal>

    </div>
    <!-- Columns (Statuses) -->
    <div v-for="status in statuses" :key="status.slug" :id="'status-' + status.id"
      class="mr-6 w-4/5 max-w-xs flex-shrink-0">

      <div class="rounded-md shadow-md overflow-hidden">
        <div class="p-3 flex justify-between items-baseline bg-gray-900 ">
          <h4 class="font-medium text-white">
            {{ status.title }}
          </h4>
          <div>
            <button @click="openAddTaskForm(status.id)"
              class="text-sm bg-white hover:bg-gray-100 text-green-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
              Add
            </button>
            <button @click="dropColumn(status.id)"
              class="text-sm bg-white hover:bg-gray-100 text-red-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
              X
            </button>
          </div>
        </div>
        <div class="p-2 bg-white-100">
          <!-- AddTaskForm -->
          <!-- <AddTaskForm v-if="newTaskForStatus === status.id" :status-id="status.id" v-on:task-added="handleTaskAdded"
            v-on:task-canceled="closeAddTaskForm" /> -->
          <!-- ./AddTaskForm -->

          <modal v-if="newTaskForStatus === status.id" @close="showCardModal = false">
            <h3 slot="header">Add Card</h3>
            <div slot="body">
              <AddTaskForm v-if="newTaskForStatus === status.id" :status-id="status.id" v-on:task-added="handleTaskAdded"
                v-on:task-canceled="closeAddTaskForm" />
            </div>
          </modal>

          <!-- Tasks -->
          <draggable class="flex-1 overflow-hidden" v-model="status.tasks" v-bind="taskDragOptions"
            @end="handleTaskMoved">
            <transition-group class="flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto rounded shadow-xs"
              tag="div">
              <div v-for="task in status.tasks" :key="task.id" @click="editTask(task)"
                class="mb-3 p-4 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer">
                <span class="block mb-2 text-xl text-gray-900">
                  {{ task.title }}
                </span>
                <!-- <p class="text-gray-700">
                  {{ task.description }}
                </p> -->
              </div>
              <!-- ./Tasks -->
            </transition-group>
          </draggable>
          <!-- No Tasks -->
          <div v-show="!status.tasks.length && newTaskForStatus !== status.id"
            class="flex-1 p-4 flex flex-col items-center justify-center">
            <span class="text-gray-600 mb-1">No tasks yet</span>
            <button
              class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
              @click="openAddTaskForm(status.id)">
              Add one
            </button>
          </div>
          <!-- ./No Tasks -->
        </div>
      </div>
    </div>
    <!-- ./Columns -->
  </div>
</template>

<script>
import draggable from "vuedraggable";
import AddTaskForm from "./AddTaskForm.vue";
import AddStatusForm from "./AddStatusForm.vue";
import EditTaskFormVue from "./EditTaskForm.vue";
import vmodal from 'vue-js-modal';
export default {
  components: { draggable, AddTaskForm, AddStatusForm, EditTaskFormVue },
  props: {
    initialData: Array
  },
  data() {
    return {
      statuses: [],
      showAddStatus: false,
      showModal: false,
      showCardModal: false,

      newTaskForStatus: 0,
      task: [],
    };
  },
  computed: {
    taskDragOptions() {
      return {
        animation: 200,
        group: "task-list",
        dragClass: "status-drag"
      };
    }
  },
  mounted() {
    // 'clone' the statuses so we don't alter the prop when making changes
    this.statuses = this.initialData;
  },
  methods: {
    openAddTaskForm(statusId) {
      this.showCardModal = true;
      this.newTaskForStatus = statusId;
    },
    dropColumn(statusId) {
      // Display a confirmation dialog
      const confirmed = window.confirm("Are you sure you want to delete this column?");

      if (!confirmed) {
        return; // If user cancels, do nothing
      }

      // If confirmed, proceed with the deletion
      axios.delete(`/statuses/${statusId}`)
        .then(() => {
          const statusIndex = this.statuses.findIndex(
            status => status.id === statusId
          );
          if (statusIndex > -1)
            this.statuses.splice(statusIndex, 1);
        }).catch(err => {
          console.log(err.response);
        });
    },
    closeAddTaskForm() {
      this.newTaskForStatus = 0;
    },
    handleTaskAdded(newTask) {
      // Find the index of the status where we should add the task
      const statusIndex = this.statuses.findIndex(
        status => status.id === newTask.status_id
      );

      // Add newly created task to our column
      this.statuses[statusIndex].tasks.push(newTask);

      // Reset and close the AddTaskForm
      this.closeAddTaskForm();
      this.showCardModal = false;
    },
    handleStatusAdded(newStatus) {
      this.statuses.push(newStatus[0]);
    },
    editTask(task) {
      this.task = task;
      this.showModal = true;
    },
    handleTaskedit(newTask) {
      // Find the index of the status where we should add the task
      const statusIndex = this.statuses.findIndex(
        (status) => {
          return status.id == newTask.status_id;
        }
      );
      const taskIndex = this.statuses[statusIndex].tasks.findIndex(
        task => {
          return task.id == newTask.id;
        }
      )
      // Add newly created task to our column
      this.statuses[statusIndex].tasks[taskIndex] = newTask;
      this.showModal = false;
    },
    handleTaskMoved(evt) {
      axios.put("/tasks/sync", { columns: this.statuses }).catch(err => {
        console.log(err.response);
      });
    }
  }
};
</script>

<style scoped>
.status-drag {
  transition: transform 0.5s;
  transition-property: all;
}
</style>