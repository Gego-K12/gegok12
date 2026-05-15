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
                            <label>Task Type</label>
                            <p>{{ formatTaskType(task.task_type) }}</p>
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
                            <p>
                                <span :class="['priority-badge', priorityClass(task.priority)]">
                                    {{ formatPriority(task.priority) }}
                                </span>
                            </p>
                        </div>

                        <div class="detail-box">
                            <label>Status</label>
                            <p>
                                <span :class="['status-badge', taskStatusClass(task.task_status)]">
                                    {{ formatTaskStatus(task.task_status) }}
                                </span>
                            </p>
                        </div>

                        <div class="detail-box">
                            <label>Total Assignees</label>
                            <p>{{ task.total_count || assignees.length || 0 }}</p>
                        </div>

                        <div class="detail-box">
                            <label>Completed</label>
                            <p>{{ task.completion_count || completedCount }}</p>
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

                    <div class="table-responsive" v-if="assignees.length > 0">
                        <table class="assignee-table">
                            <thead>
                                <tr>
                                    <th>#</th>

                                    <th v-if="showUserColumn">Name</th>
                                    <th v-if="showUserColumn">User Name</th>

                                    <th>Assigned Type</th>

                                    <th v-if="showClassColumn">Class</th>
                                    <th v-if="showGroupColumn">Group</th>
                                    <th v-if="showClaimedColumn">Claimed By</th>

                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(assignee, index) in assignees" :key="assignee.id || index">
                                    <td>{{ index + 1 }}</td>

                                    <td v-if="showUserColumn">
                                        {{ assignee.name || '-' }}
                                    </td>

                                    <td v-if="showUserColumn">
                                        {{ assignee.username || '-' }}
                                    </td>

                                    <td>
                                        {{ formatAssignedType(assignee.assigned_type || task.assignee) }}
                                    </td>

                                    <td v-if="showClassColumn">
                                        {{ assignee.class || task.class || '-' }}
                                    </td>

                                    <td v-if="showGroupColumn">
                                        {{ assignee.group_name || assignee.group_id || '-' }}
                                    </td>

                                    <td v-if="showClaimedColumn">
                                        {{ assignee.claimed_by || '-' }}
                                    </td>

                                    <td>
                                        <span :class="['status-badge', assigneeStatusClass(assignee.status)]">
                                            {{ formatAssigneeStatus(assignee.status) }}
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
                assignees: [],
                teachers: [],
                selectedUsers: []
            },
            loading: true,
            backUrl: ''
        }
    },

    computed: {
        assignees() {
            return this.task.assignees || [];
        },

        completedCount() {
            return this.assignees.filter(assignee => assignee.status === 'completed').length;
        },

        showUserColumn() {
            return ['self', 'student', 'teacher', 'non_teaching'].includes(this.task.assignee);
        },

        showClassColumn() {
            return ['class', 'student'].includes(this.task.assignee);
        },

        showGroupColumn() {
            return this.task.assignee === 'group';
        },

        showClaimedColumn() {
            return this.assignees.some(assignee => assignee.claimed_by);
        }
    },

    methods: {
        getTask() {
            axios.get('/' + this.mode + '/task/show/' + this.taskId)
                .then(response => {
                    this.task = response.data.data || response.data;

                    if (!this.task.assignees) {
                        this.task.assignees = [];
                    }

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

        priorityClass(priority) {
            if (priority === 'high') {
                return 'priority-high';
            }

            if (priority === 'low') {
                return 'priority-low';
            }

            return 'priority-normal';
        },

        formatTaskStatus(status) {
            if (status === true || status === 1 || status === '1') {
                return 'Completed';
            }

            return 'Pending';
        },

        taskStatusClass(status) {
            if (status === true || status === 1 || status === '1') {
                return 'status-completed';
            }

            return 'status-pending';
        },

        formatAssigneeStatus(status) {
            if (!status) {
                return 'Pending';
            }

            return status
                .toString()
                .replace(/_/g, ' ')
                .replace(/\b\w/g, char => char.toUpperCase());
        },

        assigneeStatusClass(status) {
            if (status === 'completed') {
                return 'status-completed';
            }

            if (status === 'claimed') {
                return 'status-claimed';
            }

            return 'status-pending';
        },

        formatAssignedType(type) {
            if (!type) {
                return '-';
            }

            return type
                .toString()
                .replace(/_/g, ' ')
                .replace(/\b\w/g, char => char.toUpperCase());
        },

        formatTaskType(type) {
            if (!type) {
                return '-';
            }

            return type
                .toString()
                .replace(/_/g, ' ')
                .replace(/\b\w/g, char => char.toUpperCase());
        }
    },

    created() {
        this.backUrl = this.url + '/' + this.mode + '/tasks';
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

.status-badge,
.priority-badge {
    display: inline-block;
    padding: 4px 9px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
}

.status-pending {
    background: #fef3c7;
    color: #92400e;
}

.status-completed {
    background: #dcfce7;
    color: #166534;
}

.status-claimed {
    background: #dbeafe;
    color: #1d4ed8;
}

.priority-low {
    background: #e0f2fe;
    color: #0369a1;
}

.priority-normal {
    background: #f3f4f6;
    color: #374151;
}

.priority-high {
    background: #fee2e2;
    color: #991b1b;
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