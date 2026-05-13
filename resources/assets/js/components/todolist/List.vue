<template>
    <div class="relative task-page">
        <div v-if="this.success!=null" class="success-banner" id="success-alert">{{ this.success }}</div>

        <Teleport to="#add_todolist">
            <div class="topbar-wrap">
                <div class="topbar-left">
                    <h1 class="page-title">Tasks</h1>
                    <select class="type-select" name="type" v-model="type" id="type" v-on:change="selectAssigned">
                        <option v-for="assigned in assignedlist" v-bind:value="assigned.id">{{ assigned.name }}</option>
                    </select>
                </div>

                <div class="topbar-right">
                    <div class="search-wrap">
                        <svg class="search-icon" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0C21.517,9.026,21.517,14.63,18.073,18.074z"/>
                        </svg>
                        <input type="text" name="search" v-model="search" class="search-input" placeholder="Search tasks..." @keyup.enter="searchList()">
                    </div>

                    <a href="#" @click.prevent="resetForm()" class="btn-ghost">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;stroke:#374151;stroke-width:2;stroke-linecap:round;stroke-linejoin:round">
                            <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/>
                            <path d="M3 3v5h5"/>
                        </svg>
                        Reset
                    </a>

                    <a href="#" class="btn-complete" @click.prevent="submitForm()" v-if="this.selectedTaskCount > 0">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:13px;height:13px;stroke:#fff;stroke-width:2.5;stroke-linecap:round;stroke-linejoin:round">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Mark as completed ({{ selectedTaskCount }})
                    </a>

                    <a :href="this.url+'/'+this.mode+'/task/add'" class="btn-new">
                        <svg viewBox="0 0 409.6 409.6" xmlns="http://www.w3.org/2000/svg" style="width:11px;height:11px;fill:#fff;flex-shrink:0">
                            <path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/>
                        </svg>
                        New task
                    </a>
                </div>
            </div>
        </Teleport>

        <!-- Task list -->
        <div class="task-board" v-if="Object.keys(tasks).length > 0">
            <div class="task-section" v-for="(tasklist, key) in tasks">

                <!-- Section header -->
                <div class="section-header" @click="showTasks(key)">
                    <div class="section-header-left">
                        <span class="section-chevron" :class="{ rotated: arrow === '1_'+key }">
                            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <polyline points="6 9 12 15 18 9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <span class="section-label overdue-label" v-if="key == 0">
                            <span class="section-dot dot-red"></span>Overdue
                        </span>
                        <span class="section-label today-label" v-else-if="key == 1">
                            <span class="section-dot dot-blue"></span>Today
                        </span>
                        <span class="section-label upcoming-label" v-else>
                            <span class="section-dot dot-green"></span>Upcoming
                        </span>
                    </div>
                    <span class="section-count">
                        {{ Object.keys(tasklist).length }} task{{ Object.keys(tasklist).length !== 1 ? 's' : '' }}
                    </span>
                </div>

                <!-- Task rows -->
                <div :id="key" class="task-rows">
                    <div
                        class="task-row"
                        :class="{
                            'task-row-overdue': key == 0,
                            'task-row-done': task_completed.includes(list.task_id)
                        }"
                        v-for="list in tasklist"
                        :key="list.task_id"
                    >
                        <div class="task-row-inner">

                            <!-- Checkbox -->
                            <label class="checkbox-wrap" :for="'chk_'+list.task_id">
                                <input
                                    class="task-checkbox"
                                    type="checkbox"
                                    v-model="task_completed"
                                    :id="'chk_'+list.task_id"
                                    :value="list.task_id"
                                    @click="selectedCount(list.task_id,$event)"
                                >
                                <span class="checkbox-box">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="checkbox-check">
                                        <polyline points="20 6 9 17 4 12" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </span>
                            </label>

                            <!-- Content -->
                            <div class="task-body">
                                <div class="task-name" :class="{ 'task-name-done': task_completed.includes(list.task_id) }">
                                    {{ list.title }}
                                </div>

                                <div class="task-meta">
                                    <span class="meta-chip chip-gray" v-if="list.assignee_display">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;stroke:#6b7280;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        {{ list.assignee_display }}
                                    </span>
                                    <span class="meta-chip chip-purple" v-if="list.assignee_type">{{ list.assignee_type }}</span>
                                    <span class="meta-chip chip-red" v-if="key == 0">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;stroke:#991b1b;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0">
                                            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                                        </svg>
                                        Overdue &bull; {{ list.task_date }}
                                    </span>
                                    <span class="meta-chip chip-blue" v-else-if="key == 1">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;stroke:#075985;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        Today
                                    </span>
                                    <span class="meta-chip chip-neutral" v-else>
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;stroke:#374151;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        {{ list.task_date }}
                                    </span>
                                    <span class="meta-chip chip-amber" v-if="list.priority == 'high'">
                                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:10px;height:10px;stroke:#92400e;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;flex-shrink:0">
                                            <path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/>
                                        </svg>
                                        High priority
                                    </span>
                                    <span class="meta-chip chip-sky" v-else-if="list.priority == 'normal'">Normal</span>
                                    <span class="meta-chip chip-green" v-else-if="list.priority == 'low'">Low priority</span>
                                </div>

                                <!-- Progress bar -->
                                <div class="progress-block" v-if="list.completion_count && list.total_count">
                                    <div class="progress-info">
                                        <span>Task completion</span>
                                        <span class="progress-fraction">{{ list.completion_count }} / {{ list.total_count }}</span>
                                    </div>
                                    <div class="progress-track">
                                        <div class="progress-fill" :style="{width: Math.round((list.completion_count/list.total_count)*100)+'%'}"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="task-actions">
                                <a href="#" :title="list.reminder" class="act-btn act-btn-default">
                                    <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M347.216,301.211l-71.387-53.54V138.609c0-10.966-8.864-19.83-19.83-19.83c-10.966,0-19.83,8.864-19.83,19.83v118.978c0,6.246,2.935,12.136,7.932,15.864l79.318,59.489c3.569,2.677,7.734,3.966,11.878,3.966c6.048,0,11.997-2.717,15.884-7.952C357.766,320.208,355.981,307.775,347.216,301.211z"/>
                                        <path d="M256,0C114.833,0,0,114.833,0,256s114.833,256,256,256s256-114.833,256-256S397.167,0,256,0z M256,472.341c-119.275,0-216.341-97.066-216.341-216.341S136.725,39.659,256,39.659c119.295,0,216.341,97.066,216.341,216.341S375.275,472.341,256,472.341z"/>
                                    </svg>
                                </a>
                                <a href="#" @click.prevent="snoozeTask(list.task_id)" title="Snooze" class="act-btn act-btn-default" v-if="list.snooze == 1">
                                    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                        <text y=".9em" font-size="90">⏰</text>
                                    </svg>
                                </a>
                                <!-- <a :href="url+'/'+mode+'/task/edit/'+list.task_id" title="Edit" class="act-btn act-btn-default" v-if="list.auth_id == list.created_by">
                                    <svg viewBox="0 0 477.873 477.873" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"/>
                                        <path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z"/>
                                    </svg>
                                </a> -->
                                 <a :href="url+'/'+mode+'/task/view/'+list.task_id" title="View" class="act-btn act-btn-default">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;stroke:#6b7280;stroke-width:2;stroke-linecap:round;stroke-linejoin:round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                        <circle cx="12" cy="12" r="3"></circle>
                                    </svg>
                                </a>
                                  
                                <a href="#" @click.prevent="deleteTask(list.task_id)" title="Delete" class="act-btn act-btn-danger" v-if="list.auth_id == list.created_by">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;stroke:#6b7280;stroke-width:2;stroke-linecap:round;stroke-linejoin:round">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="task-empty" v-if="Object.keys(tasklist).length === 0">
                        No tasks found
                    </div>
                </div>

            </div>
        </div>

        <!-- Empty state -->
        <div class="empty-state" v-else>
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <rect x="9" y="3" width="6" height="4" rx="1" stroke="#d1d5db" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <p class="empty-title">No tasks yet</p>
            <p class="empty-sub">Create your first task to get started</p>
        </div>

        <!-- View Modal -->
        <div v-for="(tasklist, key) in tasks" :key="key">
            <div v-for="list in tasklist" :key="list.task_id">
                <div v-if="show == list.task_id+'_show'" class="modal-overlay" @click.self="closeModal()">
                    <div class="modal-box">
                        <div class="modal-head">
                            <h2 class="modal-title">View Task</h2>
                            <button class="modal-close" @click="closeModal()">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <line x1="18" y1="6" x2="6" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                    <line x1="6" y1="6" x2="18" y2="18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                </svg>
                            </button>
                        </div>
                        <div class="modal-body-wrap">
                            <div class="modal-field">
                                <span class="modal-field-label">Title</span>
                                <span class="modal-field-value">{{ task.title }}</span>
                            </div>
                            <div class="modal-field">
                                <span class="modal-field-label">Description</span>
                                <span class="modal-field-value" v-html="task.to_do_list"></span>
                            </div>
                            <div class="modal-field">
                                <span class="modal-field-label">Assignee Type</span>
                                <span class="modal-field-value">{{ task.assignee_display }}</span>
                            </div>
                            <div class="modal-field">
                                <span class="modal-field-label">Assigned To</span>
                                <div class="modal-field-value">
                                    <template v-if="task.assignee == 'teacher'">
                                        <div v-for="teacher in task.teachers" :key="teacher.name">
                                            <a v-if="mode == 'admin'" :href="url+'/'+mode+'/teacher/show/'+teacher.name" class="modal-link">{{ teacher.fullname }}</a>
                                            <span v-else>{{ teacher.fullname }}</span>
                                        </div>
                                    </template>
                                    <template v-else-if="task.assignee == 'class'">{{ task.class }}</template>
                                    <template v-else-if="task.assignee == 'student'">
                                        <div v-for="student in task.selectedUsers" :key="student.name">
                                            <a v-if="mode == 'admin'" :href="url+'/'+mode+'/student/show/'+student.name" class="modal-link">{{ student.fullname }}</a>
                                            <span v-else>{{ student.fullname }}</span>
                                        </div>
                                    </template>
                                    <template v-else>{{ task.assignee_display }}</template>
                                </div>
                            </div>
                            <div class="modal-field">
                                <span class="modal-field-label">Due Date</span>
                                <span class="modal-field-value">{{ task.task_date }}</span>
                            </div>
                            <div class="modal-field" style="border-bottom:none">
                                <span class="modal-field-label">Reminder On</span>
                                <span class="modal-field-value">{{ task.reminder_date }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props: ['url', 'mode', 'hidecolumns'],
        data() {
            return {
                tasks: [],
                task: [],
                task_completed: [],
                type: 'by_me',
                status: 0,
                showCompleted: '',
                selectedTaskCount: 0,
                params: {},
                arrow: 0,
                show: '',
                search: '',
                assignedlist: [
                    { id: 'by_me', name: 'Assigned By Me' },
                    { id: 'to_me', name: 'Assigned To Me' }
                ],
                statuslist: [
                    { id: 0, name: 'All' },
                    { id: 1, name: 'Pending' },
                    { id: 2, name: 'Done' },
                    { id: 3, name: 'Overdue' },
                    { id: 4, name: 'Open' }
                ],
                errors: [],
                success: null,
            }
        },

        methods: {
            getlist() {
                axios.get('/' + this.mode + '/task/list?status=' + this.status + '&type=' + this.type + '&search=' + this.search).then(response => {
                    this.tasks = response.data;
                });
            },

            showModal(id) {
                this.show = id + '_show';
                axios.get('/' + this.mode + '/task/show/' + id).then(response => {
                    this.task = response.data;
                });
            },

            closeModal() {
                this.show = 0;
            },

            searchList() {
                this.getlist();
            },

            resetForm() {
                this.search = '';
                this.getlist();
            },

            showTasks(key) {
                if ($('#' + key).hasClass('hidden')) {
                    $('#' + key).removeClass('hidden').addClass('block');
                    this.arrow = '0_' + key;
                } else {
                    $('#' + key).removeClass('block').addClass('hidden');
                    this.arrow = '1_' + key;
                }
            },

            selectAssigned() {
                this.final = this.url + '/' + this.mode + '/task/list?status=' + this.status + '&type=' + this.type;
                axios.get(this.final).then(response => {
                    this.tasks = response.data;
                });
            },

            showCompletedTask(e) {
                this.status = e;
                this.final = this.url + '/' + this.mode + '/task/list?status=' + this.status + '&type=' + this.type;
                axios.get(this.final).then(response => {
                    this.tasks = response.data;
                });
            },

            selectedCount(id, e) {
                if (e.target.checked) {
                    this.selectedTaskCount++;
                    this.task_completed.push(id);
                    $('#' + id).addClass('student_selected');
                } else {
                    this.selectedTaskCount--;
                    this.task_completed.splice(id);
                    $('#' + id).removeClass('student_selected');
                }
            },

            snoozeTask(id) {
                this.errors = [];
                this.success = null;
                axios.post('/' + this.mode + '/task/snooze/' + id).then(response => {
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            submitForm() {
                this.errors = [];
                this.success = null;
                axios.post('/' + this.mode + '/task/completed', {
                    task_completed: this.task_completed,
                    selectedTaskCount: this.selectedTaskCount,
                }).then(response => {
                    this.success = response.data.success;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            deleteTask(id) {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this task ?',
                    icon: "info",
                    buttons: ['No', 'Yes'],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) {
                        axios.get('/' + thisswal.mode + '/task/' + id + '/delete').then(response => {
                            thisswal.success = response.data.success;
                            thisswal.getlist();
                        });
                    } else {
                        swal("Cancelled");
                    }
                });
            },
        },

        created() {
            this.getlist();
        }
    }
</script>

<style scoped>
/* ── Page ──────────────────────────────────────────────────── */
.task-page {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background: #f4f5f7;
    min-height: 100vh;
    padding: 0;
}

/* ── Success Banner ────────────────────────────────────────── */
.success-banner {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    border-radius: 8px;
    padding: 10px 16px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 16px;
}

/* ── Topbar ────────────────────────────────────────────────── */
.topbar-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.topbar-left {
    display: flex;
    align-items: center;
    gap: 10px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.page-title {
    font-size: 19px;
    font-weight: 600;
    color: #111827;
    margin: 0;
    letter-spacing: -0.01em;
}

.type-select {
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 5px 28px 5px 10px;
    font-size: 13px;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E") no-repeat right 8px center;
    color: #374151;
    cursor: pointer;
    appearance: none;
    outline: none;
}
.type-select:focus { border-color: #6b7280; }

/* ── Search ────────────────────────────────────────────────── */
.search-wrap {
    position: relative;
    display: flex;
    align-items: center;
}
.search-icon {
    position: absolute;
    left: 9px;
    width: 13px;
    height: 13px;
    fill: #9ca3af;
    pointer-events: none;
}
.search-input {
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    padding: 6px 10px 6px 30px;
    font-size: 13px;
    background: #fff;
    color: #111827;
    width: 200px;
    outline: none;
    transition: border-color 0.15s;
}
.search-input:focus { border-color: #9ca3af; box-shadow: 0 0 0 3px rgba(107,114,128,0.08); }
.search-input::placeholder { color: #c4c9d4; }

/* ── Buttons ───────────────────────────────────────────────── */
.btn-ghost {
    display: flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border: 1.5px solid #e5e7eb;
    border-radius: 8px;
    background: #fff;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    transition: background 0.15s;
}
.btn-ghost:hover { background: #f3f4f6; }

.btn-complete {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 8px;
    background: #16a34a;
    color: #fff;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    border: none;
    transition: background 0.15s;
}
.btn-complete:hover { background: #15803d; }

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
    transition: background 0.15s;
}
.btn-new:hover { background: #1f2937; }

/* ── Board ─────────────────────────────────────────────────── */
.task-board {
    margin-top: 20px;
    background: #fff;
    border-radius: 12px;
    border: 1.5px solid #e5e7eb;
    overflow: hidden;
}

.task-section {
    border-bottom: 1.5px solid #f3f4f6;
}
.task-section:last-child { border-bottom: none; }

/* ── Section header ────────────────────────────────────────── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 13px 20px;
    background: #fafafa;
    border-bottom: 1px solid #f3f4f6;
    cursor: pointer;
    user-select: none;
    transition: background 0.12s;
}
.section-header:hover { background: #f5f6f8; }

.section-header-left {
    display: flex;
    align-items: center;
    gap: 8px;
}

.section-chevron {
    display: flex;
    align-items: center;
    color: #9ca3af;
    transition: transform 0.2s;
}
.section-chevron svg {
    width: 16px;
    height: 16px;
}
.section-chevron.rotated { transform: rotate(-90deg); }

.section-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    border-radius: 50%;
    margin-right: 6px;
}
.dot-red { background: #ef4444; }
.dot-blue { background: #3b82f6; }
.dot-green { background: #22c55e; }

.section-label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.04em;
    text-transform: uppercase;
}

.overdue-label { color: #dc2626; }
.today-label   { color: #2563eb; }
.upcoming-label { color: #16a34a; }

.section-count {
    font-size: 12px;
    color: #9ca3af;
    font-weight: 500;
}

/* ── Task rows ─────────────────────────────────────────────── */
.task-rows { display: flex; flex-direction: column; }

.task-row {
    border-bottom: 1px solid #f9fafb;
    transition: background 0.1s;
}
.task-row:last-child { border-bottom: none; }
.task-row:hover { background: #fafafa; }

.task-row-overdue {
    border-left: 3px solid #ef4444;
}
.task-row-overdue .task-row-inner { padding-left: 17px; }

.task-row-done { opacity: 0.65; }

.task-row-inner {
    display: flex;
    align-items: flex-start;
    gap: 14px;
    padding: 14px 20px;
}

/* ── Custom checkbox ───────────────────────────────────────── */
.checkbox-wrap {
    display: flex;
    align-items: center;
    cursor: pointer;
    flex-shrink: 0;
    margin-top: 1px;
}
.task-checkbox { display: none; }

.checkbox-box {
    width: 18px;
    height: 18px;
    border: 2px solid #d1d5db;
    border-radius: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #fff;
    transition: background 0.15s, border-color 0.15s;
    flex-shrink: 0;
}
.task-checkbox:checked + .checkbox-box {
    background: #16a34a;
    border-color: #16a34a;
}
.checkbox-check {
    width: 10px;
    height: 10px;
    opacity: 0;
    transition: opacity 0.1s;
}
.task-checkbox:checked + .checkbox-box .checkbox-check { opacity: 1; }

/* ── Task body ─────────────────────────────────────────────── */
.task-body { flex: 1; min-width: 0; }

.task-name {
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    line-height: 1.45;
    margin-bottom: 6px;
}
.task-name-done {
    text-decoration: line-through;
    color: #9ca3af;
}

/* ── Meta chips ────────────────────────────────────────────── */
.task-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 5px;
    align-items: center;
}

.meta-chip {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 2px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 500;
    white-space: nowrap;
}

.chip-gray    { background: #f3f4f6;  color: #374151; }
.chip-purple  { background: #ede9fe;  color: #5b21b6; }
.chip-red     { background: #fee2e2;  color: #991b1b; }
.chip-blue    { background: #dbeafe;  color: #1e40af; }
.chip-neutral { background: #f3f4f6;  color: #374151; }
.chip-amber   { background: #fef3c7;  color: #92400e; }
.chip-sky     { background: #e0f2fe;  color: #075985; }
.chip-green   { background: #dcfce7;  color: #166534; }

/* ── Progress ──────────────────────────────────────────────── */
.progress-block { margin-top: 10px; }

.progress-info {
    display: flex;
    justify-content: space-between;
    font-size: 11px;
    color: #9ca3af;
    margin-bottom: 5px;
}
.progress-fraction { font-weight: 600; color: #6b7280; }

.progress-track {
    width: 100%;
    max-width: 300px;
    height: 5px;
    background: #e5e7eb;
    border-radius: 999px;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #6366f1);
    border-radius: 999px;
    transition: width 0.4s ease;
}

/* ── Task actions ──────────────────────────────────────────── */
.task-actions {
    display: flex;
    align-items: center;
    gap: 2px;
    flex-shrink: 0;
    opacity: 0;
    transition: opacity 0.15s;
}
.task-row:hover .task-actions { opacity: 1; }

.act-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    transition: background 0.15s;
    text-decoration: none;
    cursor: pointer;
}
.act-btn svg {
    width: 14px;
    height: 14px;
    fill: #9ca3af;
}
.act-btn-default:hover { background: #f3f4f6; }
.act-btn-default:hover svg { fill: #374151; }
.act-btn-danger:hover { background: #fee2e2; }
.act-btn-danger:hover svg { stroke: #dc2626 !important; }

/* ── Empty states ──────────────────────────────────────────── */
.task-empty {
    padding: 20px;
    font-size: 13px;
    color: #c4c9d4;
    text-align: center;
}

.empty-state {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 56px 32px;
    text-align: center;
    margin-top: 20px;
}
.empty-icon {
    width: 56px;
    height: 56px;
    margin: 0 auto 16px;
}
.empty-icon svg { width: 56px; height: 56px; }
.empty-title {
    font-size: 15px;
    font-weight: 600;
    color: #374151;
    margin: 0 0 4px;
}
.empty-sub {
    font-size: 13px;
    color: #9ca3af;
    margin: 0;
}

/* ── Modal ─────────────────────────────────────────────────── */
.modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 9998;
    background: rgba(0,0,0,0.45);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    backdrop-filter: blur(2px);
}

.modal-box {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.18);
    width: 100%;
    max-width: 480px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalIn 0.2s ease;
}

@keyframes modalIn {
    from { opacity: 0; transform: scale(0.96) translateY(8px); }
    to   { opacity: 1; transform: scale(1) translateY(0); }
}

.modal-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 18px 22px 14px;
    border-bottom: 1px solid #f3f4f6;
}
.modal-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin: 0;
}
.modal-close {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border: none;
    background: #f3f4f6;
    border-radius: 7px;
    cursor: pointer;
    color: #6b7280;
    transition: background 0.15s;
}
.modal-close:hover { background: #e5e7eb; color: #111827; }
.modal-close svg { width: 14px; height: 14px; }

.modal-body-wrap { padding: 6px 0 16px; }

.modal-field {
    display: flex;
    gap: 12px;
    padding: 12px 22px;
    border-bottom: 1px solid #f9fafb;
    align-items: flex-start;
}
.modal-field-label {
    font-size: 12px;
    font-weight: 600;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    width: 110px;
    flex-shrink: 0;
    padding-top: 1px;
}
.modal-field-value {
    font-size: 14px;
    color: #111827;
    flex: 1;
    min-width: 0;
    line-height: 1.5;
}
.modal-link {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 500;
}
.modal-link:hover { text-decoration: underline; }
</style>