<template>
  <div >
    
   
    <div v-show="show"  class="flex flex-wrap" id="printMe" ref="contents">
      <div class="w-1/4 ml-2 my-2"  v-for="code in codes" >
        <p class="mb-0 w-1/6 tw-form-label">{{code.code}}</p><br>
        <qrcode-vue  :value="code.detail" :size="100" level="H" renderAs="svg"></qrcode-vue>
      </div>
    </div>
  <button v-show="buttonshow" class="text-white bg-blue-500 text-xs rounded px-2 py-1 my-2" @click="print()"><span>Print QR Codes</span></button>
    </div>
 
</template>

<script>
import QrcodeVue from 'qrcode.vue'
  export default {
      props:['url','productid'],
      data () {
        return {
          product:[],
          vendor:[],
          codes:[],
          info:'',
          show:false,
          buttonshow:false,
        }
      },
      components: {
      QrcodeVue

    },
      methods:
      {
        print()
        {
          document.title='QR_codes_'+this.product.unique_code_prefix;
          this.$htmlToPaper('printMe');  
        },
        
        getData()
          {
            axios.get('/admin/product/'+this.productid+'/editshow').then(response => {
            this.product = response.data;
            this.vendor=response.data.vendor; 
            console.log(this.product) 
            });
          },
         getproducts()
         {
          axios.get('/admin/product/'+this.productid+'/detail').then(response => {
            this.codes = response.data.data;
      if(this.codes.length>0){
            for(var i=0 ; i < this.codes.length ; i++)
          { 
            
            this.info=this.url+'/admin/product/'+this.codes[i].code+'/info'
             this.codes[i]['detail']=this.info;
            
          }
          this.buttonshow=true;
      }

            });
         }

       
      },
  
      created()
      {    
        this.getData(); 
        this.getproducts();

      }
  }
</script>