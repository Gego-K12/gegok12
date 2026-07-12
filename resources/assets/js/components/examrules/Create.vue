<template>
<div class="bg-white shadow px-4 py-3">
<div>
	<!-- <div class="group" v-if="parseInt(this.count)<=parseInt(this.no_of_groups)"> -->
	<div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>


<!--end-->
<!--start-->
<div class="my-3">
<div class="flex">
  <div class="w-1/3 lg:w-1/5 md:w-1/5">
          <label for="grade_one" class="tw-form-label">Grade Name</label>
        </div>
          <div class="w-1/3 lg:w-1/5 md:w-1/5">
          <label for="min_mark" class="tw-form-label">Minimum Percentage Mark</label>
        </div>
         <div class="w-1/3 lg:w-1/5 md:w-1/5">
          <label for="max_mark" class="tw-form-label">Maximum Percentage Mark</label>
        </div>
</div>
      <div class="flex py-3">
        
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="grade_one" name="grade_one" id="grade_one" class="tw-form-control w-10/12">
            <span v-if="errors.grade_one"><p class="text-danger text-xs my-1">{{errors.grade_one[0]}}</p></span>
        </div>
   

  
      
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="min_mark" name="min_mark" id="min_mark" class="tw-form-control w-10/12">
            <span v-if="errors.min_mark"><p class="text-danger text-xs my-1">{{errors.min_mark[0]}}</p></span>
        </div>
  


 
       
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="max_mark" name="max_mark" id="max_mark" class="tw-form-control w-10/12">
            <span v-if="errors.max_mark"><p class="text-danger text-xs my-1">{{errors.max_mark[0]}}</p></span>
        </div>
 
</div>



<!--end-->
<!--start-->

 <div class="py-3 flex">

  
      <!--   <div class="w-1/5">
          <label for="grade_two" class="tw-form-label">Grade Name</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="grade_two" name="grade_two" id="grade_two" class="tw-form-control w-10/12">
            <span v-if="errors.grade_two"><p class="text-danger text-xs my-1">{{errors.grade_two[0]}}</p></span>
        </div>
   
   
   
  
      <!--   <div class="w-1/5">
          <label for="a_min_mark" class="tw-form-label">Minimum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="a_min_mark" name="a_min_mark" id="a_min_mark" class="tw-form-control w-10/12">
            <span v-if="errors.a_min_mark"><p class="text-danger text-xs my-1">{{errors.a_min_mark[0]}}</p></span>
        </div>
   



        <!-- <div class="w-1/5">
          <label for="a_max_mark" class="tw-form-label">Maximum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="a_max_mark" name="a_max_mark" id="a_max_mark" class="tw-form-control w-10/12">
            <span v-if="errors.a_max_mark"><p class="text-danger text-xs my-1">{{errors.a_max_mark[0]}}</p></span>
        </div>
    </div>
 


<!--end-->
<!--start-->
 <div class="flex py-3">

   
       <!--  <div class="w-1/5">
          <label for="grade_three" class="tw-form-label">Grade Name</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="grade_three" name="grade_three" id="grade_three" class="tw-form-control w-10/12">
            <span v-if="errors.grade_three"><p class="text-danger text-xs my-1">{{errors.grade_three[0]}}</p></span>
        </div>
    
  

       <!--  <div class="w-1/5">
          <label for="b_min_mark" class="tw-form-label">Minimum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="b_min_mark" name="b_min_mark" id="b_min_mark" class="tw-form-control w-10/12">
            <span v-if="errors.b_min_mark"><p class="text-danger text-xs my-1">{{errors.b_min_mark[0]}}</p></span>
        </div>




       <!--  <div class="w-1/5">
          <label for="b_max_mark" class="tw-form-label">Maximum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="b_max_mark" name="b_max_mark" id="b_b_max_mark" class="tw-form-control w-10/12">
            <span v-if="errors.b_max_mark"><p class="text-danger text-xs my-1">{{errors.b_max_mark[0]}}</p></span>
        </div>
   
</div>

<!--end-->
<!--start-->

<div class="flex py-3">

     
       <!--  <div class="w-1/5">
          <label for="grade_four" class="tw-form-label">Grade Name</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="grade_four" name="grade_four" id="grade_four" class="tw-form-control w-10/12">
            <span v-if="errors.grade_four"><p class="text-danger text-xs my-1">{{errors.grade_four[0]}}</p></span>
        </div>
   



 
     <!--    <div class="w-1/5">
          <label for="c_min_mark" class="tw-form-label">Minimum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="c_min_mark" name="c_min_mark" id="c_min_mark" class="tw-form-control w-10/12">
            <span v-if="errors.c_min_mark"><p class="text-danger text-xs my-1">{{errors.c_min_mark[0]}}</p></span>
        </div>




  
 
      <!--   <div class="w-1/5">
          <label for="c_max_mark" class="tw-form-label">Maximum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="c_max_mark" name="c_max_mark" id="c_max_mark" class="tw-form-control w-10/12">
            <span v-if="errors.c_max_mark"><p class="text-danger text-xs my-1">{{errors.c_max_mark[0]}}</p></span>
        </div>
  

</div>

<!--end-->
<!--start-->

<div class="flex py-3">

  
       <!--  <div class="w-1/5">
          <label for="grade_five" class="tw-form-label">Grade Name</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="grade_five" name="grade_five" id="grade_five" class="tw-form-control w-10/12">
            <span v-if="errors.grade_five"><p class="text-danger text-xs my-1">{{errors.grade_five[0]}}</p></span>
        </div>



  <!--  <div class="w-1/5">
          <label for="f_min_mark" class="tw-form-label">Minimum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="f_min_mark" name="f_min_mark" id="f_min_mark" class="tw-form-control w-10/12">
            <span v-if="errors.f_min_mark"><p class="text-danger text-xs my-1">{{errors.f_min_mark[0]}}</p></span>
        </div>
  




       <!--  <div class="w-1/5">
          <label for="f_max_mark" class="tw-form-label">Maximum Percentage Mark</label>
        </div> -->
        <div class="w-1/3 lg:w-1/5 md:w-1/5">
            <input type="text" v-model="f_max_mark" name="f_max_mark" id="f_max_mark" class="tw-form-control w-10/12">
            <span v-if="errors.f_max_mark"><p class="text-danger text-xs my-1">{{errors.f_max_mark[0]}}</p></span>
 
    </div>
 
</div>


<!--end-->
<!--start-->


      
    	
    <div class="my-6">

        <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Submit</a>
    		<a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
      		
    </div>
	</div>


  </div>
  </div>
</template>



<script>


  export default {

    props:[],


      data(){
        return{
            rules:[],
            grade_one:'',
            min_mark:'',
            max_mark:'',
            grade_two:'', 
            a_min_mark:'',
            a_max_mark:'', 
            grade_three:'',
            b_min_mark:'',
            b_max_mark:'',
            grade_four:'', 
            c_min_mark:'',
            c_max_mark:'', 
            grade_five:'',
            f_min_mark:'',
            f_max_mark:'',
           

            errors:[],
            success:null,
        }
      },
        
      methods:
        {
          reset()
            {
              this.title='';
              this.publish_date='';
              this.expire_date='';
              this.description='';  
              this.attachment_file='';   
            }, 

            checkForm()
            {
              this.errors=[];
              this.success=null; 

              let formData=new FormData();

              formData.append('grade_one',this.grade_one);                 
              formData.append('min_mark',this.min_mark);                 
              formData.append('max_mark',this.max_mark);
              formData.append('grade_two',this.grade_two);
              formData.append('a_min_mark',this.a_min_mark);                 
              formData.append('a_max_mark',this.a_max_mark);
              formData.append('grade_three',this.grade_three);
              formData.append('b_min_mark',this.b_min_mark);                 
              formData.append('b_max_mark',this.b_max_mark);
              formData.append('grade_four',this.grade_four);
              formData.append('c_min_mark',this.c_min_mark);                 
              formData.append('c_max_mark',this.c_max_mark);
              formData.append('grade_five',this.grade_five);
              formData.append('f_min_mark',this.f_min_mark);                 
              formData.append('f_max_mark',this.f_max_mark);                 
                       
                    
              
            axios.post('/admin/examrules',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                this.rules = response.data;     
                this.success = response.data.success;
                
                //console.log(this.work);
            }).catch(error => {
                this.errors = error.response.data.errors;
                });
            },

           




        },
      created()
      {
        
        //alert(this.standard);
      }
  }
</script>