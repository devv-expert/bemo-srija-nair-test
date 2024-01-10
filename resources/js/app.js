require("./bootstrap");
import vmodal from 'vue-js-modal'

window.Vue = require("vue");
Vue.use(vmodal)
// Register our components
Vue.component("modal", {
  template: "#modal-template"
});
Vue.component("kanban-board", require("./components/KanbanBoard.vue").default);

const app = new Vue({
  el: "#app"
});
