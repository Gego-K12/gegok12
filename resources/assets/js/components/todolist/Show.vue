<template>
    <div class="task-show-page">
        <div class="task-show-card">
            <div class="task-show-header">
                <div>
                    <h3>View Task</h3>
                    <p class="page-subtitle">Task details and assignee information</p>
                </div>

                <a :href="backUrl" class="btn-back">
                    Back
                </a>
            </div>

            <div v-if="!loading">
                <!-- Task Details -->
                <div class="details-section">
                    <h4 class="section-title">Task Details</h4>

                    <div class="task-details-grid">
                        <div class="detail-box">
                            <label>Title</label>
                            <p>{{ task.title || '-' }}</p>
                        </div>

                        <div class="detail-box">
                            <label>Assignee Type</label>
                            <p>{{ task.assignee_display || '-' }}</p>
                        </div>

                        <div class="detail-box">
                            <label>Task Date</label>
                            <p>{{ task.task_date || '-' }}</p>
                        </div>

                        <div class="detail-box">
                            <label>Reminder Date</label>
                            <p>{{ task.reminder_date || '-' }}</p>
                        </div>

                        <div class="detail-box">
                            <label>Priority</label>
                            <p>{{ formatPriority(task.priority) }}</p>
                        </div>

                        <div class="detail-box">
                            <label>Status</label>
                            <p>{{ formatStatus(task.task_status) }}</p>
                        </div>
                    </div>

                    <div class="description-box">
                        <label>Description</label>
                        <div class="description-content" v-html="task.to_do_list || '-'"></div>
                    </div>
                </div>

                <!-- Assignee Details -->
                <div class="details-section">
                    <h4 class="section-title">Assignee Details</h4>

                    <!-- Teacher Table -->
                    <div class="table-responsive" v-if="task.assignee == 'teacher' && task.teachers.length > 0">
                        <table class="assignee-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Teacher Name</th>
                                    <th>User Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(teacher, index) in task.teachers" :key="teacher.id || teacher.name || index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ teacher.fullname || '-' }}</td>
                                    <td>{{ teacher.name || '-' }}</td>
                                    <td>
                                        <span class="status-badge">
                                            {{ teacher.status || 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Student Table -->
                    <div class="table-responsive" v-else-if="task.assignee == 'student' && task.selectedUsers.length > 0">
                        <table class="assignee-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>User Name</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(student, index) in task.selectedUsers" :key="student.id || student.name || index">
                                    <td>{{ index + 1 }}</td>
                                    <td>{{ student.fullname || '-' }}</td>
                                    <td>{{ student.name || '-' }}</td>
                                    <td>{{ task.class || '-' }}</td>
                                    <td>
                                        <span class="status-badge">
                                            {{ student.status || 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Class Table -->
                    <div class="table-responsive" v-else-if="task.assignee == 'class'">
                        <table class="assignee-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Assigned Type</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Class</td>
                                    <td>{{ task.class || '-' }}</td>
                                    <td>
                                        <span class="status-badge">
                                            Pending
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="empty-assignee" v-else>
                        No assignee details found
                    </div>
                </div>
            </div>

            <div v-else class="loading-box">
                Loading task...
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['url', 'mode', 'taskId'],

    data() {
        return {
            task: {
                teachers: [],
                selectedUsers: []
            },
            loading: true,
            backUrl: ''
        }
    },

    methods: {
        getTask() {
            axios.get('/' + this.mode + '/task/show/' + this.taskId)
                .then(response => {
                    this.task = response.data;

                    if (!this.task.teachers) {
                        this.task.teachers = [];
                    }

                    if (!this.task.selectedUsers) {
                        this.task.selectedUsers = [];
                    }

                    this.loading = false;
                })
                .catch(error => {
                    console.log(error);
                    this.loading = false;
                });
        },

        formatPriority(priority) {
            if (!priority) {
                return '-';
            }

            return priority.charAt(0).toUpperCase() + priority.slice(1);
        },

        formatStatus(status) {
            if (status == 1) {
                return 'Pending';
            }

            if (status == 2) {
                return 'Completed';
            }

            return 'Open';
        }
    },

    created() {
        console.log('TASK SHOW VUE FILE CALLED');
        console.log('url:', this.url);
        console.log('mode:', this.mode);
        console.log('taskId:', this.taskId);

        this.backUrl = this.url + '/' + this.mode + '/task';
        this.getTask();
    }
}
</script>

<style scoped>
.task-show-page {
    background: #f4f5f7;
    min-height: 100vh;
    padding: 20px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
}

.task-show-card {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 20px;
    width: 100%;
    max-width: none;
    margin: 0;
}

.task-show-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #f3f4f6;
    padding-bottom: 14px;
    margin-bottom: 18px;
}

.task-show-header h3 {
    margin: 0;
    font-size: 22px;
    font-weight: 700;
    color: #111827;
}

.page-subtitle {
    margin: 4px 0 0;
    color: #6b7280;
    font-size: 13px;
}

.btn-back {
    background: #6b7280;
    color: #fff;
    padding: 8px 16px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
}

.btn-back:hover {
    background: #4b5563;
    color: #fff;
    text-decoration: none;
}

.details-section {
    margin-bottom: 24px;
}

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: #111827;
    margin: 0 0 14px;
}

.task-details-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
}

.detail-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px 14px;
    background: #fafafa;
}

.detail-box label,
.description-box label {
    display: block;
    font-size: 12px;
    font-weight: 700;
    color: #6b7280;
    margin-bottom: 6px;
}

.detail-box p {
    margin: 0;
    color: #111827;
    font-size: 14px;
}

.description-box {
    margin-top: 14px;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 12px 14px;
    background: #fff;
}

.description-content {
    color: #111827;
    font-size: 14px;
    line-height: 1.6;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.assignee-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border: 1px solid #e5e7eb;
}

.assignee-table th {
    background: #f9fafb;
    color: #374151;
    font-size: 13px;
    font-weight: 700;
    text-align: left;
    padding: 12px;
    border-bottom: 1px solid #e5e7eb;
}

.assignee-table td {
    color: #111827;
    font-size: 14px;
    padding: 12px;
    border-bottom: 1px solid #f3f4f6;
}

.assignee-table tr:last-child td {
    border-bottom: none;
}

.status-badge {
    display: inline-block;
    background: #fef3c7;
    color: #92400e;
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.empty-assignee {
    padding: 20px;
    text-align: center;
    color: #6b7280;
    background: #f9fafb;
    border: 1px dashed #d1d5db;
    border-radius: 10px;
}

.loading-box {
    padding: 30px;
    text-align: center;
    color: #6b7280;
    font-size: 14px;
}

@media (max-width: 992px) {
    .task-details-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .task-show-page {
        padding: 10px;
    }

    .task-show-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .task-details-grid {
        grid-template-columns: 1fr;
    }
}
</style>