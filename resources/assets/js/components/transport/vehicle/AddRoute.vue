<template>
  <div v-if="show == 1" class="modal modal-mask">
    <div class="modal-wrapper px-4">
      <div class="modal-container w-full  max-w-md px-8 mx-auto">
        <div class="modal-header flex justify-between items-center">
          <h2>Add Route</h2>
          <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
            &times;
          </button>
        </div>
        <div class="modal-body" >
          <div class="flex items-center">
            <div class="w-full">
              <label for="obtained_marks" class="tw-form-label">Route Name</label>

              <div class="my-1 w-full">
                <select name="route_id"  v-model="route_id" class="tw-form-control w-full">
                <option value="" disabled>Select Route</option>
                <option v-for="user in users" v-bind:value="user.id">{{user.name}}</option>
              </select><br>
                <span v-if="errors.route_id" class="text-red-500 text-xs font-semibold">{{errors.route_id[0]}}</span>

              </div>
            </div>
          </div>
          <div class="flex items-center">

            <div class="my-2 ">
              <input type="button" name=""  value="submit" @click="addlocationproduct()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import { bus } from "../../../app";

export default {
  props:['url'],
  data () {
    return {
      users:[],
      route_id:'',
      vehicleid:'',
      show:0,
      errors:[],
    }
  },

  methods:{
    getlist()
    {
       axios.get('/admin/transport/route/list').then(response=>{
                  this.users=response.data.data;
                 });
    },
    addlocationproduct()
    {

      this.errors=[];
                 axios.post("/admin/transport/vehicle/route/create",{
                    route_id:this.route_id,
                    vehicle_id:this.vehicleid,
                  }).then(response => {
                    this.success = response.data.message;
                    this.closeModal();
                    this.resetForm();
                     bus.$emit("add_route", response.data.message);
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
    },
    resetForm()
               {
                this.route_id='';
                this.errors=[];
               },
    showModal(vehicle_id)
    {
      this.vehicleid=vehicle_id;
      this.show = 1;
    },

    closeModal()
    {
      this.show = 0;
      this.resetForm();
    },
    closeMsg()
    {
      this.success=null;
    }

  },

  created()
  {
    this.getlist();
    bus.$on("open_add_route", vehicle_id => this.showModal(vehicle_id));
  }
}
</script>

<style scoped>

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
    overflow:auto;
}

.modal-container {
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
 /* height: 550px;*/
  overflow:auto;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.text-danger
{
  color:red;
}
</style>
