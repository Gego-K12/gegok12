<template>
    <div class="">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="my-8">
                <p></p>
                <div class="w-full flex items-center justify-between mb-4">
                    <div class="flex items-center text-sm">
                        <div class="pr-3 border-r">
                            {{ parseInt(this.selectedVendorCount) }} Vendors selected
                        </div>
                        <div class="px-3 border-r relative">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected"><span>Select All</span>
                        </div>
                        <div class="px-3 relative" v-if="this.selectedVendorCount > 0">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNone($event)" v-model="noneSelected"><span>Select None</span>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="vendor in vendorlist">
                        <div class="flex items-center member-list">
                            <div class="flex items-center student_select">
                                <input class="w-5 h-5" type="checkbox" v-model="selected" :value="vendor['id']" @click="selectedCount(vendor['id'],$event)">
                                <label></label>
                            </div>
                            <div class="flex p-2 w-full active">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{vendor['name'] }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url', 'subcategoryid'],
        data(){
            return{
                vendorlist:[],
                active: false,
                selected:[],
                selectedVendorCount:0,
                allSelected: false,
                noneSelected:false,
                errors:[],
                success:null,
            }
        },
        methods:
        {
            submitForm()
            {
                if(this.selectedVendorCount > 0)
                {
                    this.errors=[];
                    axios.post("/admin/categoryvendor/add",{
                        category_id :   this.subcategoryid,
                        vendor_id   :   this.selected,
                    }).then(response => {
                        this.success = response.data.message;
                        //location.reload();
                    }).catch(error => {
                       this.errors = error.response.data.errors;
                    });
                }
                else
                {
                    alert("Select Atleast One vendor");
                }
            },

            selectAll(e)
            {
                var selected = [];
                if (e.target.checked)
                {
                    $('.standard-list').addClass('standard_selected');
                    if(this.allSelected == false)
                    {
                        this.vendorlist.forEach(function (vendor)
                        {
                            selected.push(vendor.id);
                        });
                        this.selected = selected;
                        this.selectedVendorCount = selected.length;
                        this.allSelected = true;
                    }
                }
                else
                {
                    this.vendorlist.forEach(function (vendor)
                    {
                        selected.splice(vendor.id);
                    });
                    this.selected = selected;
                    this.selectedVendorCount = selected.length;
                    this.noneSelected = false;
                    $('.standard-list').removeClass('standard_selected');
                }
            },

            selectNone(e)
            {
                var selected = [];
                if (e.target.checked)
                {
                    $('.standard-list').removeClass('standard_selected');
                    this.vendorlist.forEach(function (vendor)
                    {
                        selected.splice(vendor.id);
                    });
                    this.selected = selected;
                    this.selectedVendorCount = selected.length;
                    this.noneSelected = false;
                }
            },

            selectedCount(id,e)
            {
                if (e.target.checked)
                {
                  this.selectedVendorCount++;
                  $('#'+id).addClass('standard_selected');
                }
                else
                {
                  this.selectedVendorCount--;
                  $('#'+id).removeClass('standard_selected');
                }
            },

            getData()
            {
                axios.get('/admin/vendor/'+this.subcategoryid+'/showlist').then(response=>{
                    this.vendorlist = response.data;
                });
            },

            reset()
            {
                this.subcategoryid='';
                this.selected=[];
            },
        },
        created()
        {
            this.getData();
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
      height: 550px;
      overflow:auto;
    }
    .modal-header h3 {
      margin-top: 0;
      color: #42B983;
    }
    .modal-body {
      margin: 20px 0;
    }
    .modal-default-button {
      float: right;
    }
    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
     */
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
