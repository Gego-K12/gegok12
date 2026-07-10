<template>
    <Teleport to="#batch-filter">
        <div class=" flex flex-wrap items-center mt-3">
            <select name="passing_session" v-model="passing_session" id="passing_session" class="tw-form-control text-xs">
                <option value="" disabled="disabled">Select Batch</option>
                <option v-for="i in range(start,end)" v-bind:value="i">{{ i }}</option>
            </select>
            <div class="tw-form-row my-4 flex justify-end">
                <a href="#" dusk="search-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 ml-3 text-sm font-medium" @click="searchmember()">Search</a>
                <a href="#" dusk="reset-btn" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 ml-3 text-sm font-medium" @click="reset()">Reset</a>
            </div>
        </div>
    </Teleport>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url','searchquery','batch'],
        data(){
            return {
                date:[],
                passing_session:'',
                start:'',
                end:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            reset()
            {
                this.passing_session='';
                window.location.href=this.url+"/admin/alumni";
            },

            getData()
            {
                axios.get('/admin/alumni/getdate').then(response => {
                    this.date = response.data;
                    //console.log(this.date);
                    this.setData();   
                });
            },

            setData()
            { 
                if(Object.keys(this.date).length>0)
                {
                    this.start  = parseInt(this.date.start);
                    if(this.date.end != null)
                    {
                        this.end = parseInt(this.date.end);
                    }
                    else
                    {
                        this.end = null;
                    }
                }
            },
     
            range(max,min)
            {
                var array = [],
                j = 0;
                if(min != null)
                {
                    for(var i = max; i >= min; i--)
                    {
                        array[j] = i;
                        j++;
                    }
                }
                else
                {
                    array[j] = max
                }
                return array;
            },

            searchmember()
            {
                this.params = {
                    passing_session:this.passing_session,
                };

                this.final=this.url+'/admin/alumni?'+this.searchquery;
                Object.keys(this.params).forEach(key => {
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                window.location.href=this.final;
            },

            addParam(url, param, value) 
            {
                param = encodeURIComponent(param);
                var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
                var a = document.createElement('a');
                var regex = new RegExp(r);
                var str = param + (value ? "=" + encodeURIComponent(value) : ""); 
                a.href = url;
                var q = a.search.replace(regex, "$1"+str);
                if (q === a.search) 
                {
                    a.search += (a.search ? "&" : "") + str;
                } 
                else 
                {
                    a.search = q;
                }
                return a.href ;
            },
        },
      
        created()
        {
            this.passing_session = this.batch;
            this.getData(); 
        }
    }
</script>