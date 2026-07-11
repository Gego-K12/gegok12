<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : product_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setProductTab('1')">Poduct Detail</a>
      </li>
      <li class="px-2 mx-3 py-2" v-if="this.ownership_status==1" v-bind:class="[{'active' : product_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setProductTab('2')">Ownership</a>
      </li>
       <li class="px-2 mx-3 py-2" v-if="this.track_status==1" v-bind:class="[{'active' : product_tab === '3'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setProductTab('3')">Location tracking</a>
      </li>
      <li class="px-2 mx-3 py-2" v-if="this.condition_status==1" v-bind:class="[{'active' : product_tab === '4'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setProductTab('4')">Condition </a>
      </li>

    </ul>
    <Teleport to="#product">
      <history :url="this.url" :productid="this.product_id"></history>
      <ownership :url="this.url" :productid="this.productid"></ownership>
      <tracking :url="this.url" :productid="this.productid"></tracking>
       <condition :url="this.url" :productid="this.productid"></condition>

    </Teleport>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import ownership from './ownershiplist';
  import tracking from './trackinglist';
  import condition from './conditionlist';
  import history from './producthistory';
  
  
  export default {
    props:['url','product_id','productid','track_status','ownership_status','condition_status'],
    data () {
      return {
        product_tab:'1',     
      }
    },
    components: {
      ownership,
      tracking,
      condition,
      history
     
    },

    methods:
    {
      setProductTab(val)
      {
        this.product_tab=val;
        bus.$emit("dataProductTab", this.product_tab);
      }
    },

    created()
    {
      bus.$emit("dataProductTab", this.product_tab);
       
      bus.$on("dataProductTab", data => {
        if(data!='')
        {
          this.product_tab=data;                   
        }
      });     
    }
  }
</script>