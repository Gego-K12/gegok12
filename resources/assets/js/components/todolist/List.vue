<template>
    <div class="relative task-page">
        <div v-if="this.success!=null" class="alert alert-success mt-2" id="success-alert">{{this.success}}</div>

        <Teleport to="#add_todolist">
            <div class="flex flex-wrap lg:flex-row justify-between items-center">
                <div class="flex items-center gap-3">
                    <h1 class="admin-h1 task-title">Tasks</h1>
                    <select class="tw-form-control task-type-select text-xs" name="type" v-model="type" id="type" v-on:change="selectAssigned">
                        <option v-for="assigned in assignedlist" v-bind:value="assigned.id">{{ assigned.name }}</option>
                    </select>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Filter chips -->
                   <!--  <div class="filter-chip-row">
                        <a href="#" class="filter-chip" :class="{active: status === list.id}" v-for="list in statuslist" @click.prevent="showCompletedTask(list.id)">
                            {{ list.name }}
                        </a>
                    </div>
 -->
                    <!-- Search -->
                    <div class="search-wrap">
                        <svg class="search-icon" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0C21.517,9.026,21.517,14.63,18.073,18.074z"/></svg>
                        <input type="text" name="search" v-model="search" class="search-input" placeholder="Search tasks..." @keyup.enter="searchList()">
                    </div>

                    <a href="#" @click.prevent="resetForm()" class="btn-ghost text-sm">Reset</a>

                    <a href="#" class="btn-complete" @click.prevent="submitForm()" v-if="this.selectedTaskCount > 0">
                        Mark as completed
                    </a>

                    <a :href="this.url+'/'+this.mode+'/task/add'" class="btn-new">
                        <svg viewBox="0 0 409.6 409.6" xmlns="http://www.w3.org/2000/svg" style="width:12px;height:12px;fill:#fff;flex-shrink:0"><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/></svg>
                        New task
                    </a>
                </div>
            </div>
        </Teleport>

        <div class="task-list-wrap" v-if="Object.keys(tasks).length > 0">
            <div class="task-group" v-for="(tasklist, key) in tasks">

                <!-- Section header -->
                <div class="group-header" @click="showTasks(key)">
                    <div class="flex items-center gap-2">
                        <svg v-if="arrow == '1_'+key" class="chevron" viewBox="0 0 292 292" xmlns="http://www.w3.org/2000/svg"><path d="M222.979,133.331L95.073,5.424C91.456,1.807,87.178,0,82.226,0c-4.952,0-9.233,1.807-12.85,5.424c-3.617,3.617-5.424,7.898-5.424,12.847v255.813c0,4.948,1.807,9.232,5.424,12.847c3.621,3.617,7.902,5.428,12.85,5.428c4.949,0,9.23-1.811,12.847-5.428l127.906-127.907c3.614-3.613,5.428-7.897,5.428-12.847C228.407,141.229,226.594,136.948,222.979,133.331z"/></svg>
                        <svg v-if="arrow != '1_'+key" class="chevron" viewBox="0 0 292 292" xmlns="http://www.w3.org/2000/svg"><path d="M286.935,69.377c-3.614-3.617-7.898-5.424-12.848-5.424H18.274c-4.952,0-9.233,1.807-12.85,5.424C1.807,72.998,0,77.279,0,82.228c0,4.948,1.807,9.229,5.424,12.847l127.907,127.907c3.621,3.617,7.902,5.428,12.85,5.428s9.233-1.811,12.847-5.428L286.935,95.074c3.613-3.617,5.427-7.898,5.427-12.847C292.362,77.279,290.548,72.998,286.935,69.377z"/></svg>
                        <span class="group-label">
                            <span v-if="key == 0">Overdue</span>
                            <span v-else-if="key == 1">Today</span>
                            <span v-else-if="key == 2">Upcoming</span>
                        </span>
                    </div>
                    <span class="group-count">{{ Object.keys(tasklist).length }} task{{ Object.keys(tasklist).length !== 1 ? 's' : '' }}</span>
                </div>

                <!-- Task rows -->
                <div :id="key" class="task-rows">
                    <div
                        class="task-row"
                        :class="{
                            'overdue-row': key == 0,
                            'completed-row': task_completed.includes(list.task_id)
                        }"
                        v-for="list in tasklist"
                    >
                        <div class="task-row-main">
                            <input
                                class="task-checkbox"
                                type="checkbox"
                                v-model="task_completed"
                                :id="list.task_id"
                                :value="list.task_id"
                                @click="selectedCount(list.task_id,$event)"
                            >

                            <div class="task-content">
                                <div class="task-title-row">
                                    <span class="task-name" :class="{'done': task_completed.includes(list.task_id)}">{{ list.title }}</span>
                                </div>

                                <div class="task-badges">
                                    <span class="badge badge-gray" v-if="list.assignee_display">{{ list.assignee_display }}</span>
                                    <span class="badge badge-purple" v-if="list.assignee_type">{{ list.assignee_type }}</span>
                                    <span class="badge badge-red" v-if="key == 0">
                                        Overdue &bull; {{ list.task_date }}
                                    </span>
                                    <span class="badge badge-neutral" v-else-if="key == 1">Today</span>
                                    <span class="badge badge-neutral" v-else>{{ list.task_date }}</span>
                                    <span class="badge badge-high" v-if="list.priority == 'high'">High priority</span>
                                    <span class="badge badge-normal" v-else-if="list.priority == 'normal'">Normal</span>
                                    <span class="badge badge-low" v-else-if="list.priority == 'low'">Low priority</span>
                                </div>

                                <!-- Progress bar (shown when class/group assignee) -->
                                <div class="progress-wrap" v-if="list.completion_count && list.total_count">
                                    <div class="progress-label-row">
                                        <span class="progress-label">Student completion</span>
                                        <span class="progress-label">{{ list.completion_count }} / {{ list.total_count }} done</span>
                                    </div>
                                    <div class="progress-bar-bg">
                                        <div class="progress-bar-fill" :style="{width: Math.round((list.completion_count/list.total_count)*100)+'%'}"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="task-actions">
                                <a href="#" :title="list.reminder" class="action-btn">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M347.216,301.211l-71.387-53.54V138.609c0-10.966-8.864-19.83-19.83-19.83c-10.966,0-19.83,8.864-19.83,19.83v118.978c0,6.246,2.935,12.136,7.932,15.864l79.318,59.489c3.569,2.677,7.734,3.966,11.878,3.966c6.048,0,11.997-2.717,15.884-7.952C357.766,320.208,355.981,307.775,347.216,301.211z"/><path d="M256,0C114.833,0,0,114.833,0,256s114.833,256,256,256s256-114.833,256-256S397.167,0,256,0z M256,472.341c-119.275,0-216.341-97.066-216.341-216.341S136.725,39.659,256,39.659c119.295,0,216.341,97.066,216.341,216.341S375.275,472.341,256,472.341z"/></svg>
                                </a>
                                <a href="#" @click.prevent="snoozeTask(list.task_id)" title="Snooze" class="action-btn" v-if="list.snooze == 1">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M4509,5091 c-41-42-41-107 0-153l29-33 131-5 131-5-160-240c-144-216-160-244-160-282 0-33 7-50 29-75l29-33 262,0 262,0 29,33c41,46 41,111 0,153l-29,29-131,0c-72,0-131,2-131,5 0,3 72,113 160,245 148,222 160,244 160,286 0,37-6,51-29,75l-29,29-262,0-262,0-29-29z" transform="scale(0.1)"/></svg>
                                </a>
                                <a :href="url+'/'+mode+'/task/edit/'+list.task_id" title="Edit" class="action-btn" v-if="list.auth_id == list.created_by">
                                    <svg viewBox="0 0 477.873 477.873" xmlns="http://www.w3.org/2000/svg"><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"/><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z"/></svg>
                                </a>
                                <a href="#" @click.prevent="showModal(list.task_id)" title="View" class="action-btn">
                                    <svg viewBox="-27 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38z"/></svg>
                                </a>
                                <a href="#" @click.prevent="deleteTask(list.task_id)" title="Delete" class="action-btn action-btn-danger" v-if="list.auth_id == list.created_by">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"/><rect x="235.948" y="175.791" width="40.104" height="237.285"/><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"/><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474L380.665,471.896z"/><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42C354.924,14.992,339.932,0,321.504,0z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="task-empty" v-if="Object.keys(tasklist).length === 0">
                        No tasks found
                    </div>
                </div>

                <!-- Modals for this group -->
                <div v-for="list in tasklist">
                    <div v-if="show == list.task_id+'_show'" class="modal modal-mask">
                        <div class="modal-wrapper px-4">
                            <div class="modal-container w-full max-w-md px-4 mx-auto">
                                <div class="modal-header flex justify-between items-center">
                                    <h2>View Task</h2>
                                    <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="flex">
                                        <div class="w-full lg:w-1/4"><label class="tw-form-label">Title</label></div>
                                        <div class="w-full lg:w-3/4"><p class="tw-form-control w-full">{{ task.title }}</p></div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="flex">
                                        <div class="w-full lg:w-1/4"><label class="tw-form-label">Description</label></div>
                                        <div class="w-full lg:w-3/4"><p class="tw-form-control w-full" v-html="task.to_do_list"></p></div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="flex">
                                        <div class="w-full lg:w-1/4"><label class="tw-form-label">Assignee Type</label></div>
                                        <div class="w-full lg:w-3/4"><p class="tw-form-control w-full">{{ task.assignee_display }}</p></div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="flex">
                                        <div class="w-full lg:w-1/4"><label class="tw-form-label">Assigned To</label></div>
                                        <div class="w-full lg:w-3/4" v-if="task.assignee == 'teacher'">
                                            <p class="tw-form-control w-full" v-for="teacher in task.teachers">
                                                <span v-if="mode == 'admin'"><a :href="url+'/'+mode+'/teacher/show/'+teacher.name">{{ teacher.fullname }}</a></span>
                                                <span v-else>{{ teacher.fullname }}</span>
                                            </p>
                                        </div>
                                        <div class="w-full lg:w-3/4" v-else-if="task.assignee == 'class'">
                                            <p class="tw-form-control w-full">{{ task.class }}</p>
                                        </div>
                                        <div class="w-full lg:w-3/4" v-else-if="task.assignee == 'student'">
                                            <p class="tw-form-control w-full" v-for="student in task.selectedUsers">
                                                <span v-if="mode == 'admin'"><a :href="url+'/'+mode+'/student/show/'+student.name">{{ student.fullname }}</a></span>
                                                <span v-else>{{ student.fullname }}</span>
                                            </p>
                                        </div>
                                        <div class="w-full lg:w-3/4" v-else>
                                            <p class="tw-form-control w-full">{{ task.assignee_display }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="flex">
                                        <div class="w-full lg:w-1/4"><label class="tw-form-label">Due Date</label></div>
                                        <div class="w-full lg:w-3/4"><p class="tw-form-control w-full">{{ task.task_date }}</p></div>
                                    </div>
                                </div>
                                <div class="modal-body">
                                    <div class="flex">
                                        <div class="w-full lg:w-1/4"><label class="tw-form-label">Reminder On</label></div>
                                        <div class="w-full lg:w-3/4"><p class="tw-form-control w-full">{{ task.reminder_date }}</p></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="task-empty-state" v-else>
            No tasks found
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url' , 'mode' , 'hidecolumns'],
        data () {
            return {
                tasks:[],
                task:[],
                task_completed:[],
                type:'by_me',
                status:0,
                showCompleted:'',
                selectedTaskCount:0,
                params:{},
                arrow:0,
                show:'',
                search:'',
                assignedlist:[ { id : 'by_me' , name : 'Assigned By Me' } , { id : 'to_me' , name : 'Assigned To Me' } ],
                statuslist:[ { id : 0 , name : 'All' } , { id : 1 , name : 'Pending' } , { id : 2 , name : 'Done' } , { id : 3 , name : 'Overdue' } , { id : 4 , name : 'Open' } ],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getlist()
            {
                axios.get('/'+this.mode+'/task/list?status='+this.status+'&type='+this.type+'&search='+this.search).then(response => {
                    this.tasks = response.data;
                });
            },

            showModal(id)
            {
                this.show = id+'_show';
                axios.get('/'+this.mode+'/task/show/'+id).then(response => {
                    this.task = response.data;
                });
            },

            closeModal()
            {
                this.show = 0;
            },

            searchList()
            {
                this.getlist();
            },

            resetForm()
            {
                this.search = '';
                this.getlist();
            },

            showTasks(key)
            {
                if( $('#'+key).hasClass('hidden') )
                {
                    $('#'+key).removeClass('hidden').addClass('block');
                    this.arrow = '0_'+key;
                }
                else
                {
                    $('#'+key).removeClass('block').addClass('hidden');
                    this.arrow = '1_'+key;
                }
            },

            selectAssigned()
            {
                this.final = this.url+'/'+this.mode+'/task/list?status='+this.status+'&type='+this.type;
                axios.get(this.final).then(response => {
                    this.tasks = response.data;
                });
            },

            showCompletedTask(e)
            {
                this.status = e;
                this.final = this.url+'/'+this.mode+'/task/list?status='+this.status+'&type='+this.type;
                axios.get(this.final).then(response => {
                    this.tasks = response.data;
                });
            },

            selectedCount(id,e)
            {
                if (e.target.checked)
                {
                    this.selectedTaskCount++;
                    this.task_completed.push(id);
                    $('#'+id).addClass('student_selected');
                }
                else
                {
                    this.selectedTaskCount--;
                    this.task_completed.splice(id);
                    $('#'+id).removeClass('student_selected');
                }
            },

            snoozeTask(id)
            {
                this.errors=[];
                this.success=null;
                axios.post('/'+this.mode+'/task/snooze/'+id).then(response => {
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;
                axios.post('/'+this.mode+'/task/completed',{
                    task_completed:this.task_completed,
                    selectedTaskCount:this.selectedTaskCount,
                }).then(response => {
                    this.success = response.data.success;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            deleteTask(id)
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this task ?',
                    icon: "info",
                    buttons: [ 'No', 'Yes' ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm)
                    {
                        axios.get('/'+thisswal.mode+'/task/'+id+'/delete').then(response => {
                            thisswal.success = response.data.success;
                            thisswal.getlist();
                        });
                    }
                    else
                    {
                        swal("Cancelled");
                    }
                });
            },
        },

        created()
        {
            this.getlist();
        }
    }
</script>

<style scoped>
/* ── Page ───────────────────────────────────── */
.task-page { font-family: var(--font-sans, system-ui, sans-serif); }

/* ── Header controls ────────────────────────── */
.task-title { font-size: 20px; font-weight: 500; margin: 0; }

.task-type-select {
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 13px;
    background: #fff;
    color: #374151;
    cursor: pointer;
}

.filter-chip-row { display: flex; gap: 6px; align-items: center; }

.filter-chip {
    display: inline-block;
    padding: 4px 14px;
    border-radius: 20px;
    border: 0.5px solid #d1d5db;
    background: #fff;
    font-size: 13px;
    color: #374151;
    text-decoration: none;
    transition: background 0.15s, border-color 0.15s;
    white-space: nowrap;
}
.filter-chip:hover { background: #f3f4f6; border-color: #9ca3af; }
.filter-chip.active { background: #111827; color: #fff; border-color: #111827; }

.search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.search-icon {
    position: absolute;
    left: 9px;
    width: 14px;
    height: 14px;
    fill: #9ca3af;
    pointer-events: none;
}
.search-input {
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    padding: 6px 10px 6px 30px;
    font-size: 13px;
    background: #fff;
    color: #111827;
    width: 180px;
    outline: none;
}
.search-input:focus { border-color: #6b7280; box-shadow: 0 0 0 2px rgba(107,114,128,.15); }

.btn-ghost {
    padding: 5px 12px;
    border: 0.5px solid #d1d5db;
    border-radius: 8px;
    background: #f9fafb;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    white-space: nowrap;
}
.btn-ghost:hover { background: #f3f4f6; }

.btn-complete {
    padding: 6px 14px;
    border-radius: 8px;
    background: #16a34a;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
}

.btn-new {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    background: #111827;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
}
.btn-new:hover { background: #1f2937; }

/* ── Task list wrapper ──────────────────────── */
.task-list-wrap { margin-top: 20px; display: flex; flex-direction: column; gap: 24px; background: #ffffff; }

/* ── Group section ──────────────────────────── */
.task-group {}

.group-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 6px 0 10px;
    border-bottom: 1px solid #e5e7eb;
    cursor: pointer;
    user-select: none;
}

.chevron {
    width: 10px;
    height: 10px;
    fill: #6b7280;
    flex-shrink: 0;
}

.group-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #6b7280;
}

.group-count {
    font-size: 12px;
    color: #9ca3af;
}

/* ── Task rows ──────────────────────────────── */
.task-rows { display: flex; flex-direction: column; }

.task-row {
    border-bottom: 0.5px solid #f3f4f6;
    transition: background 0.1s;
}
.task-row:hover { background: #fafafa; }
.task-row:last-child { border-bottom: none; }

.overdue-row { border-left: 3px solid #ef4444; border-radius: 0; }
.overdue-row .task-row-main { padding-left: 13px; }

.task-row-main {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 14px 4px;
}

.task-checkbox {
    width: 16px;
    height: 16px;
    margin-top: 2px;
    flex-shrink: 0;
    cursor: pointer;
    accent-color: #16a34a;
}

.task-content { flex: 1; min-width: 0; }

.task-title-row { margin-bottom: 6px; }

.task-name {
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    line-height: 1.4;
}
.task-name.done {
    text-decoration: line-through;
    color: #9ca3af;
}

/* ── Badges ─────────────────────────────────── */
.task-badges { display: flex; flex-wrap: wrap; gap: 5px; margin-top: 4px; }

.badge {
    display: inline-block;
    padding: 2px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    white-space: nowrap;
}
.badge-gray    { background: #f3f4f6; color: #374151; }
.badge-purple  { background: #ede9fe; color: #5b21b6; }
.badge-red     { background: #fee2e2; color: #991b1b; }
.badge-neutral { background: #f3f4f6; color: #374151; }
.badge-high    { background: #fef3c7; color: #92400e; }
.badge-normal  { background: #e0f2fe; color: #075985; }
.badge-low     { background: #f0fdf4; color: #166534; }

/* ── Progress bar ───────────────────────────── */
.progress-wrap { margin-top: 10px; }

.progress-label-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 4px;
}
.progress-label { font-size: 11px; color: #6b7280; }

.progress-bar-bg {
    width: 100%;
    max-width: 320px;
    height: 5px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    background: #2563eb;
    border-radius: 999px;
    transition: width 0.3s;
}

/* ── Action icons ───────────────────────────── */
.task-actions {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-shrink: 0;
    opacity: 0;
    transition: opacity 0.15s;
}
.task-row:hover .task-actions { opacity: 1; }

.action-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    transition: background 0.15s;
    text-decoration: none;
}
.action-btn svg {
    width: 14px;
    height: 14px;
    fill: #6b7280;
}
.action-btn:hover { background: #f3f4f6; }
.action-btn:hover svg { fill: #111827; }
.action-btn-danger:hover { background: #fee2e2; }
.action-btn-danger:hover svg { fill: #991b1b; }

/* ── Empty states ───────────────────────────── */
.task-empty {
    padding: 16px 4px;
    font-size: 13px;
    color: #9ca3af;
    text-align: center;
}
.task-empty-state {
    background: #fff;
    border: 0.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 32px;
    text-align: center;
    font-size: 14px;
    color: #9ca3af;
    margin-top: 20px;
}

/* ── Modal (unchanged) ──────────────────────── */
.modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,.5);
    display: table;
    transition: opacity .3s ease;
}
.modal-wrapper {
    display: table-cell;
    vertical-align: middle;
    overflow: auto;
}
.modal-container {
    margin: 0px auto;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0,0,0,.33);
    transition: all .3s ease;
    overflow: auto;
}
.modal-header h3 { margin-top: 0; color: #42b983; }
.modal-body { margin: 20px 0; }
.modal-default-button { float: right; }
.modal-enter { opacity: 0; }
.modal-leave-active { opacity: 0; }
.modal-enter .modal-container,
.modal-leave-active .modal-container { -webkit-transform: scale(1.1); transform: scale(1.1); }
</style>