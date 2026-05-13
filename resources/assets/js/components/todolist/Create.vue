<template>
    <div class="task-form-shell">
        <div class="task-card">

            <div v-if="this.success!=null" class="success-banner">{{ this.success }}</div>

            <!-- Task Title -->
            <div class="field-group">
                <label class="field-label">Task title</label>
                <input
                    type="text"
                    name="title"
                    v-model="title"
                    class="field-input"
                    placeholder="e.g. Complete worksheet on osmosis"
                >
                <span v-if="errors.title" class="field-error">{{ errors.title[0] }}</span>
            </div>

            <!-- Description -->
            <div class="field-group">
                <label class="field-label">Description <span class="optional">(optional)</span></label>
                <textarea
                    name="to_do_list"
                    v-model="to_do_list"
                    class="field-input field-textarea"
                    placeholder="Add notes or instructions..."
                ></textarea>
                <span v-if="errors.to_do_list" class="field-error">{{ errors.to_do_list[0] }}</span>
            </div>

            <!-- Assign To -->
            <div class="field-group" v-if="this.hidecolumns == 'false'">
                <label class="field-label">Assign to</label>
                <div class="assignee-grid">
                    <div class="assignee-card" :class="{ active: assignee === 'self' }" @click="setAssignee('self')">
                        <span class="assignee-icon">👤</span>
                        <div>
                            <div class="assignee-title">Myself</div>
                            <div class="assignee-sub">Personal reminder</div>
                        </div>
                    </div>
                    <div class="assignee-card" :class="{ active: assignee === 'student' }" @click="setAssignee('student')">
                        <span class="assignee-icon">🎓</span>
                        <div>
                            <div class="assignee-title">Students</div>
                            <div class="assignee-sub">One or more</div>
                        </div>
                    </div>
                    <div class="assignee-card" :class="{ active: assignee === 'class' }" @click="setAssignee('class')">
                        <span class="assignee-icon">🏫</span>
                        <div>
                            <div class="assignee-title">Whole class</div>
                            <div class="assignee-sub">One or more classes</div>
                        </div>
                    </div>
                    <div class="assignee-card" :class="{ active: assignee === 'group' }" @click="setAssignee('group')">
                        <span class="assignee-icon">👥</span>
                        <div>
                            <div class="assignee-title">Groups</div>
                            <div class="assignee-sub">One or more groups</div>
                        </div>
                    </div>
                    <div class="assignee-card" :class="{ active: assignee === 'teacher' }" @click="setAssignee('teacher')">
                        <span class="assignee-icon">📋</span>
                        <div>
                            <div class="assignee-title">Teaching staff</div>
                            <div class="assignee-sub">One or more</div>
                        </div>
                    </div>
                    <div class="assignee-card" :class="{ active: assignee === 'non_teaching' }" @click="setAssignee('non_teaching')">
                        <span class="assignee-icon">🔧</span>
                        <div>
                            <div class="assignee-title">Non-teaching</div>
                            <div class="assignee-sub">Support staff</div>
                        </div>
                    </div>
                </div>
                <span v-if="errors.assignee" class="field-error">{{ errors.assignee[0] }}</span>
            </div>

            <!-- CLASS PICKLIST (shown for class, student, group assignees) -->
            <div v-if="(assignee === 'class' || assignee === 'student' || assignee === 'group') && hidecolumns == 'false'">

                <!-- Class selector for student/group assignees -->
                <div class="field-group" v-if="assignee === 'student' || assignee === 'group'">
                    <label class="field-label">Class<span class="required">*</span></label>
                    <select
                        name="standardLink_id"
                        v-model="standardLink_id"
                        class="field-input field-select"
                        @change="assignee === 'group' ? loadGroups() : enableStudent()"
                    >
                        <option value="" disabled>Select Class</option>
                        <option v-for="standardLink in standardLinklist" :value="standardLink.id">{{ standardLink.standard_section }}</option>
                    </select>
                    <span v-if="errors.standardLink_id" class="field-error">{{ errors.standardLink_id[0] }}</span>
                </div>

                <!-- Class picklist for 'class' assignee -->
                <div v-if="assignee === 'class'" class="field-group">
                    <label class="field-label">Select class(es)</label>
                    <div class="dual-box">
                        <!-- Available panel -->
                        <div class="dual-panel">
                            <div class="dual-header">
                                <span>AVAILABLE</span>
                                <span class="dual-count">{{ classPickList[0].length }}</span>
                            </div>
                            <div class="dual-search">
                                <span class="search-icon">🔍</span>
                                <input type="text" v-model="classSearch" placeholder="Search..." class="dual-search-input">
                            </div>
                            <div class="dual-list">
                                <div
                                    v-for="item in filteredAvailableClasses"
                                    :key="item.id"
                                    class="dual-item"
                                    :class="{ highlighted: selectedAvailableClass === item.id }"
                                    @click="selectedAvailableClass = item.id"
                                    @dblclick="moveClassToSelected(item)"
                                >
                                    <div class="item-avatar" :style="{ background: avatarColor(item.standard_section) }">
                                        {{ item.standard_section.substring(0,2).trim() }}
                                    </div>
                                    <span class="item-name">{{ item.standard_section }}</span>
                                </div>
                                <div v-if="filteredAvailableClasses.length === 0" class="dual-empty">No classes found</div>
                            </div>
                        </div>

                        <!-- Transfer buttons -->
                        <div class="transfer-btns">
                            <button class="transfer-btn" @click="moveAllClassesToSelected" title="Move all">»</button>
                            <button class="transfer-btn" @click="moveSingleClassToSelected" title="Move selected">›</button>
                            <button class="transfer-btn" @click="removeSingleClassFromSelected" title="Remove selected">‹</button>
                            <button class="transfer-btn" @click="removeAllClassesFromSelected" title="Remove all">«</button>
                        </div>

                        <!-- Selected panel -->
                        <div class="dual-panel">
                            <div class="dual-header">
                                <span>SELECTED</span>
                                <span class="dual-count">{{ classPickList[1].length }}</span>
                            </div>
                            <div class="dual-list dual-list-selected">
                                <div
                                    v-for="item in classPickList[1]"
                                    :key="item.id"
                                    class="dual-item"
                                    :class="{ highlighted: selectedChosenClass === item.id }"
                                    @click="selectedChosenClass = item.id"
                                    @dblclick="removeClassFromSelected(item)"
                                >
                                    <div class="item-avatar" :style="{ background: avatarColor(item.standard_section) }">
                                        {{ item.standard_section.substring(0,2).trim() }}
                                    </div>
                                    <span class="item-name">{{ item.standard_section }}</span>
                                </div>
                                <div v-if="classPickList[1].length === 0" class="dual-empty">No classes selected</div>
                            </div>
                        </div>
                    </div>

                    <!-- Selected tags -->
                    <div class="selected-tags" v-if="classPickList[1].length > 0">
                        <span class="tag-count">{{ classPickList[1].length }} selected</span>
                        <button class="clear-all-btn" @click="classPickList = [[ ...classPickList[0], ...classPickList[1] ], []]">Clear all</button>
                    </div>
                    <div class="tags-row" v-if="classPickList[1].length > 0">
                        <span class="tag-pill" v-for="item in classPickList[1]" :key="item.id">
                            {{ item.standard_section }} <span class="tag-remove" @click="removeClassFromSelected(item)">×</span>
                        </span>
                    </div>
                    <p class="info-text" v-if="classPickList[1].length > 0">Task assigned to every student in the selected class-sections.</p>
                    <span v-if="errors.selectedClassCount" class="field-error">{{ errors.selectedClassCount[0] }}</span>
                </div>

                <!-- Student picklist -->
                <div v-if="assignee === 'student' && standardLink_id !== ''" class="field-group">
                    <label class="field-label">Select students</label>
                    <div class="dual-box">
                        <div class="dual-panel">
                            <div class="dual-header">
                                <span>AVAILABLE</span>
                                <span class="dual-count">{{ studentPickList[0].length }}</span>
                            </div>
                            <div class="dual-list">
                                <div
                                    v-for="item in studentPickList[0]"
                                    :key="item.id"
                                    class="dual-item"
                                    :class="{ highlighted: selectedAvailableStudent === item.id }"
                                    @click="selectedAvailableStudent = item.id"
                                    @dblclick="moveStudentToSelected(item)"
                                >
                                    <div class="item-avatar" :style="{ background: avatarColor(item.name) }">{{ item.name.charAt(0) }}</div>
                                    <span class="item-name">{{ item.name }}</span>
                                </div>
                                <div v-if="studentPickList[0].length === 0" class="dual-empty">No students available</div>
                            </div>
                        </div>
                        <div class="transfer-btns">
                            <button class="transfer-btn" @click="moveAllStudentsToSelected">»</button>
                            <button class="transfer-btn" @click="moveSingleStudentToSelected">›</button>
                            <button class="transfer-btn" @click="removeSingleStudentFromSelected">‹</button>
                            <button class="transfer-btn" @click="removeAllStudentsFromSelected">«</button>
                        </div>
                        <div class="dual-panel">
                            <div class="dual-header">
                                <span>SELECTED</span>
                                <span class="dual-count">{{ studentPickList[1].length }}</span>
                            </div>
                            <div class="dual-list dual-list-selected">
                                <div
                                    v-for="item in studentPickList[1]"
                                    :key="item.id"
                                    class="dual-item"
                                    :class="{ highlighted: selectedChosenStudent === item.id }"
                                    @click="selectedChosenStudent = item.id"
                                    @dblclick="removeStudentFromSelected(item)"
                                >
                                    <div class="item-avatar" :style="{ background: avatarColor(item.name) }">{{ item.name.charAt(0) }}</div>
                                    <span class="item-name">{{ item.name }}</span>
                                </div>
                                <div v-if="studentPickList[1].length === 0" class="dual-empty">No students selected</div>
                            </div>
                        </div>
                    </div>
                    <div class="selected-tags" v-if="studentPickList[1].length > 0">
                        <span class="tag-count">{{ studentPickList[1].length }} selected</span>
                        <button class="clear-all-btn" @click="studentPickList = [[...studentPickList[0], ...studentPickList[1]], []]">Clear all</button>
                    </div>
                    <div class="tags-row" v-if="studentPickList[1].length > 0">
                        <span class="tag-pill" v-for="item in studentPickList[1]" :key="item.id">
                            {{ item.name }} <span class="tag-remove" @click="removeStudentFromSelected(item)">×</span>
                        </span>
                    </div>
                    <span v-if="errors.selectedUsersCount" class="field-error">{{ errors.selectedUsersCount[0] }}</span>
                </div>

                <!-- Group picklist -->
                <div v-if="assignee === 'group' && standardLink_id !== ''" class="field-group">
                    <label class="field-label">Select groups</label>
                    <div class="dual-box">
                        <div class="dual-panel">
                            <div class="dual-header">
                                <span>GROUPS</span>
                                <span class="dual-count">{{ groupPickList[0].length }}</span>
                            </div>
                            <div class="dual-list">
                                <div
                                    v-for="item in groupPickList[0]"
                                    :key="item.id"
                                    class="dual-item"
                                    :class="{ highlighted: selectedAvailableGroup === item.id }"
                                    @click="selectedAvailableGroup = item.id"
                                    @dblclick="moveGroupToSelected(item)"
                                >
                                    <div class="item-avatar avatar-icon">👥</div>
                                    <div>
                                        <div class="item-name">{{ item.group_name }}</div>
                                        <div class="item-meta">{{ item.members_count }} Members</div>
                                    </div>
                                </div>
                                <div v-if="groupPickList[0].length === 0" class="dual-empty">No groups available</div>
                            </div>
                        </div>
                        <div class="transfer-btns">
                            <button class="transfer-btn" @click="moveAllGroupsToSelected">»</button>
                            <button class="transfer-btn" @click="moveSingleGroupToSelected">›</button>
                            <button class="transfer-btn" @click="removeSingleGroupFromSelected">‹</button>
                            <button class="transfer-btn" @click="removeAllGroupsFromSelected">«</button>
                        </div>
                        <div class="dual-panel">
                            <div class="dual-header">
                                <span>SELECTED</span>
                                <span class="dual-count">{{ groupPickList[1].length }}</span>
                            </div>
                            <div class="dual-list dual-list-selected">
                                <div
                                    v-for="item in groupPickList[1]"
                                    :key="item.id"
                                    class="dual-item"
                                    :class="{ highlighted: selectedChosenGroup === item.id }"
                                    @click="selectedChosenGroup = item.id"
                                    @dblclick="removeGroupFromSelected(item)"
                                >
                                    <div class="item-avatar avatar-icon">👥</div>
                                    <div>
                                        <div class="item-name">{{ item.group_name }}</div>
                                        <div class="item-meta">{{ item.members_count }} Members</div>
                                    </div>
                                </div>
                                <div v-if="groupPickList[1].length === 0" class="dual-empty">No groups selected</div>
                            </div>
                        </div>
                    </div>
                    <span v-if="errors.groups" class="field-error">{{ errors.groups[0] }}</span>
                </div>
            </div>

            <!-- TEACHER PICKLIST -->
            <div v-if="assignee === 'teacher' && hidecolumns == 'false'" class="field-group">
                <label class="field-label">Select teaching staff</label>
                <div class="dual-box">
                    <div class="dual-panel">
                        <div class="dual-header">
                            <span>AVAILABLE</span>
                            <span class="dual-count">{{ teacherPickList[0].length }}</span>
                        </div>
                        <div class="dual-list">
                            <div
                                v-for="item in teacherPickList[0]"
                                :key="item.id"
                                class="dual-item"
                                :class="{ highlighted: selectedAvailableTeacher === item.id }"
                                @click="selectedAvailableTeacher = item.id"
                                @dblclick="moveTeacherToSelected(item)"
                            >
                                <div class="item-avatar" :style="{ background: avatarColor(item.fullname) }">{{ item.fullname.charAt(0) }}</div>
                                <div>
                                    <div class="item-name">{{ item.fullname }}</div>
                                    <div class="item-meta">{{ item.designation }}</div>
                                </div>
                            </div>
                            <div v-if="teacherPickList[0].length === 0" class="dual-empty">No staff available</div>
                        </div>
                    </div>
                    <div class="transfer-btns">
                        <button class="transfer-btn" @click="moveAllTeachersToSelected">»</button>
                        <button class="transfer-btn" @click="moveSingleTeacherToSelected">›</button>
                        <button class="transfer-btn" @click="removeSingleTeacherFromSelected">‹</button>
                        <button class="transfer-btn" @click="removeAllTeachersFromSelected">«</button>
                    </div>
                    <div class="dual-panel">
                        <div class="dual-header">
                            <span>SELECTED</span>
                            <span class="dual-count">{{ teacherPickList[1].length }}</span>
                        </div>
                        <div class="dual-list dual-list-selected">
                            <div
                                v-for="item in teacherPickList[1]"
                                :key="item.id"
                                class="dual-item"
                                :class="{ highlighted: selectedChosenTeacher === item.id }"
                                @click="selectedChosenTeacher = item.id"
                                @dblclick="removeTeacherFromSelected(item)"
                            >
                                <div class="item-avatar" :style="{ background: avatarColor(item.fullname) }">{{ item.fullname.charAt(0) }}</div>
                                <div>
                                    <div class="item-name">{{ item.fullname }}</div>
                                    <div class="item-meta">{{ item.designation }}</div>
                                </div>
                            </div>
                            <div v-if="teacherPickList[1].length === 0" class="dual-empty">No staff selected</div>
                        </div>
                    </div>
                </div>
                <div class="selected-tags" v-if="teacherPickList[1].length > 0">
                    <span class="tag-count">{{ teacherPickList[1].length }} selected</span>
                    <button class="clear-all-btn" @click="teacherPickList = [[...teacherPickList[0], ...teacherPickList[1]], []]">Clear all</button>
                </div>
                <div class="tags-row" v-if="teacherPickList[1].length > 0">
                    <span class="tag-pill" v-for="item in teacherPickList[1]" :key="item.id">
                        {{ item.fullname }} <span class="tag-remove" @click="removeTeacherFromSelected(item)">×</span>
                    </span>
                </div>
                <span v-if="errors.selectedTeachersCount" class="field-error">{{ errors.selectedTeachersCount[0] }}</span>
            </div>

            <!-- NON-TEACHING PICKLIST -->
            <div v-if="assignee === 'non_teaching' && hidecolumns == 'false'" class="field-group">
                <label class="field-label">Select non-teaching staff</label>
                <div class="dual-box">
                    <div class="dual-panel">
                        <div class="dual-header">
                            <span>NON-TEACHERS</span>
                            <span class="dual-count">{{ nonTeacherPickList[0].length }}</span>
                        </div>
                        <div class="dual-list">
                            <div
                                v-for="item in nonTeacherPickList[0]"
                                :key="item.id"
                                class="dual-item"
                                :class="{ highlighted: selectedAvailableNonTeacher === item.id }"
                                @click="selectedAvailableNonTeacher = item.id"
                                @dblclick="moveNonTeacherToSelected(item)"
                            >
                                <div class="item-avatar" :style="{ background: avatarColor(item.fullname) }">{{ item.fullname.charAt(0) }}</div>
                                <div>
                                    <div class="item-name">{{ item.fullname }}</div>
                                    <div class="item-meta">{{ item.designation }}</div>
                                </div>
                            </div>
                            <div v-if="nonTeacherPickList[0].length === 0" class="dual-empty">No staff available</div>
                        </div>
                    </div>
                    <div class="transfer-btns">
                        <button class="transfer-btn" @click="moveAllNonTeachersToSelected">»</button>
                        <button class="transfer-btn" @click="moveSingleNonTeacherToSelected">›</button>
                        <button class="transfer-btn" @click="removeSingleNonTeacherFromSelected">‹</button>
                        <button class="transfer-btn" @click="removeAllNonTeachersFromSelected">«</button>
                    </div>
                    <div class="dual-panel">
                        <div class="dual-header">
                            <span>SELECTED</span>
                            <span class="dual-count">{{ nonTeacherPickList[1].length }}</span>
                        </div>
                        <div class="dual-list dual-list-selected">
                            <div
                                v-for="item in nonTeacherPickList[1]"
                                :key="item.id"
                                class="dual-item"
                                :class="{ highlighted: selectedChosenNonTeacher === item.id }"
                                @click="selectedChosenNonTeacher = item.id"
                                @dblclick="removeNonTeacherFromSelected(item)"
                            >
                                <div class="item-avatar" :style="{ background: avatarColor(item.fullname) }">{{ item.fullname.charAt(0) }}</div>
                                <div>
                                    <div class="item-name">{{ item.fullname }}</div>
                                    <div class="item-meta">{{ item.designation }}</div>
                                </div>
                            </div>
                            <div v-if="nonTeacherPickList[1].length === 0" class="dual-empty">No staff selected</div>
                        </div>
                    </div>
                </div>
                <span v-if="errors.selectedNonTeacherCount" class="field-error">{{ errors.selectedNonTeacherCount[0] }}</span>
            </div>

            <!-- Task Type (group only) -->
            <div class="field-group" v-if="assignee === 'group'">
                <label class="field-label">Task Type</label>
                <div class="task-type-row">
                    <div class="task-type-btn" :class="{ active: task_type === 'individual' }" @click="task_type = 'individual'">Individual</div>
                    <div class="task-type-btn" :class="{ active: task_type === 'group_task' }" @click="task_type = 'group_task'">Group task</div>
                    <div class="task-type-btn" :class="{ active: task_type === 'open' }" @click="task_type = 'open'">Open task</div>
                </div>
            </div>

            <!-- Due Date -->
            <div class="field-group">
                <label class="field-label">Due date<span class="required">*</span></label>
                <div class="field-input-wrap">
                    <VueDatePicker
                        v-model="task_date"
                        format="dd-MM-yyyy HH:mm:ss"
                        model-type="format"
                        :enable-time-picker="true"
                        :is-24="true"
                        :auto-apply="true"
                        input-class-name="date-input"
                    />
                </div>
                <span v-if="errors.task_date" class="field-error">{{ errors.task_date[0] }}</span>
            </div>

            <!-- Reminder -->
            <div class="field-group">
                <label class="field-label">Reminder<span class="required">*</span></label>
                <select name="reminder" v-model="reminder" class="field-input field-select">
                    <option value="" disabled>Select Reminder</option>
                    <option v-for="list in reminderlist" :value="list.id">{{ list.name }}</option>
                </select>
                <span v-if="errors.reminder" class="field-error">{{ errors.reminder[0] }}</span>
            </div>

            <!-- Reminder Date -->
            <div class="field-group" v-if="reminder === 'others'">
                <label class="field-label">Reminder Date<span class="required">*</span></label>
                <div class="field-input-wrap">
                    <VueDatePicker
                        v-model="reminder_date"
                        format="dd-MM-yyyy HH:mm:ss"
                        model-type="format"
                        :enable-time-picker="true"
                        :is-24="true"
                        :auto-apply="true"
                        input-class-name="date-input"
                    />
                </div>
                <span v-if="errors.reminder_date" class="field-error">{{ errors.reminder_date[0] }}</span>
            </div>

            <!-- Priority -->
            <div class="field-group">
                <label class="field-label">Priority</label>
                <select v-model="priority" class="field-input field-select">
                    <option value="" disabled>Select Priority</option>
                    <option value="low">Low</option>
                    <option value="normal">Normal</option>
                    <option value="high">High</option>
                </select>
                <span v-if="errors.priority" class="field-error">{{ errors.priority[0] }}</span>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <button class="btn-submit" @click="submitForm()">Submit</button>
                <button class="btn-reset" @click="resetForm()">Reset</button>
            </div>

        </div>
    </div>
</template>

<script>
    import { VueDatePicker } from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'

    export default {
        props: ['url', 'searchquery', 'mode', 'hidecolumns'],
        components: { VueDatePicker },

        data() {
            return {
                tasks: [],
                edit: [],
                assignee: 'self',
                reminder: '',
                title: '',
                standardLink_id: '',
                reminder_date: '',
                selected: [],
                selectedUsers: [],
                allSelected: false,
                noneSelected: false,
                selectedTeachers: [],
                allSelectedTeacher: false,
                noneSelectedTeacher: false,
                teachers: [],
                to_do_list: '',
                task_date: '',
                edittask: '',
                show: 0,
                editshow: 0,
                assignlist: [
                    { id: 'class', name: 'Class' },
                    { id: 'self', name: 'Self' },
                    { id: 'student', name: 'Student' },
                    { id: 'teacher', name: 'Teachers' }
                ],
                reminderlist: [
                    { id: 'one_hour_before_the_task', name: 'One Hour Before The Task' },
                    { id: 'one_day_before_the_task', name: 'One Day Before The Task' },
                    { id: 'two_days_before_the_task', name: 'Two Days Before The Task' },
                    { id: 'others', name: 'Select Date' }
                ],
                isLoading: false,
                standardLinklist: [],
                studentlist: [],
                teacherlist: [],
                param: {},
                errors: [],
                success: null,
                studentPickList: [[], []],
                teacherPickList: [[], []],
                groupPickList: [[], []],
                grouplist: [],
                classPickList: [[], []],
                nonTeacherPickList: [[], []],
                priority: '',
                task_type: 'individual',

                // Search states
                classSearch: '',

                // Selection tracking for dual boxes
                selectedAvailableClass: null,
                selectedChosenClass: null,
                selectedAvailableStudent: null,
                selectedChosenStudent: null,
                selectedAvailableTeacher: null,
                selectedChosenTeacher: null,
                selectedAvailableGroup: null,
                selectedChosenGroup: null,
                selectedAvailableNonTeacher: null,
                selectedChosenNonTeacher: null,
            }
        },

        computed: {
            selectedUsersCount() { return this.studentPickList[1].length; },
            selectedTeachersCount() { return this.teacherPickList[1].length; },
            filteredAvailableClasses() {
                if (!this.classSearch) return this.classPickList[0];
                return this.classPickList[0].filter(c =>
                    c.standard_section.toLowerCase().includes(this.classSearch.toLowerCase())
                );
            }
        },

        methods: {
            // ── Avatar color helper ─────────────────────────────
            avatarColor(name) {
                const colors = ['#4f86c6','#e07b54','#6bbf83','#a374c6','#e0a254','#54b8e0','#e05454','#74c6a3'];
                if (!name) return colors[0];
                return colors[name.charCodeAt(0) % colors.length];
            },

            // ── Class dual-box methods ──────────────────────────
            moveClassToSelected(item) {
                this.classPickList[0] = this.classPickList[0].filter(c => c.id !== item.id);
                this.classPickList[1] = [...this.classPickList[1], item];
                this.selectedAvailableClass = null;
            },
            moveSingleClassToSelected() {
                if (!this.selectedAvailableClass) return;
                const item = this.classPickList[0].find(c => c.id === this.selectedAvailableClass);
                if (item) this.moveClassToSelected(item);
            },
            moveAllClassesToSelected() {
                this.classPickList = [[], [...this.classPickList[1], ...this.classPickList[0]]];
                this.classSearch = '';
            },
            removeClassFromSelected(item) {
                this.classPickList[1] = this.classPickList[1].filter(c => c.id !== item.id);
                this.classPickList[0] = [...this.classPickList[0], item];
                this.selectedChosenClass = null;
            },
            removeSingleClassFromSelected() {
                if (!this.selectedChosenClass) return;
                const item = this.classPickList[1].find(c => c.id === this.selectedChosenClass);
                if (item) this.removeClassFromSelected(item);
            },
            removeAllClassesFromSelected() {
                this.classPickList = [[...this.classPickList[0], ...this.classPickList[1]], []];
            },

            // ── Student dual-box methods ────────────────────────
            moveStudentToSelected(item) {
                this.studentPickList[0] = this.studentPickList[0].filter(s => s.id !== item.id);
                this.studentPickList[1] = [...this.studentPickList[1], item];
                this.selectedAvailableStudent = null;
            },
            moveSingleStudentToSelected() {
                if (!this.selectedAvailableStudent) return;
                const item = this.studentPickList[0].find(s => s.id === this.selectedAvailableStudent);
                if (item) this.moveStudentToSelected(item);
            },
            moveAllStudentsToSelected() {
                this.studentPickList = [[], [...this.studentPickList[1], ...this.studentPickList[0]]];
            },
            removeStudentFromSelected(item) {
                this.studentPickList[1] = this.studentPickList[1].filter(s => s.id !== item.id);
                this.studentPickList[0] = [...this.studentPickList[0], item];
                this.selectedChosenStudent = null;
            },
            removeSingleStudentFromSelected() {
                if (!this.selectedChosenStudent) return;
                const item = this.studentPickList[1].find(s => s.id === this.selectedChosenStudent);
                if (item) this.removeStudentFromSelected(item);
            },
            removeAllStudentsFromSelected() {
                this.studentPickList = [[...this.studentPickList[0], ...this.studentPickList[1]], []];
            },

            // ── Teacher dual-box methods ────────────────────────
            moveTeacherToSelected(item) {
                this.teacherPickList[0] = this.teacherPickList[0].filter(t => t.id !== item.id);
                this.teacherPickList[1] = [...this.teacherPickList[1], item];
                this.selectedAvailableTeacher = null;
            },
            moveSingleTeacherToSelected() {
                if (!this.selectedAvailableTeacher) return;
                const item = this.teacherPickList[0].find(t => t.id === this.selectedAvailableTeacher);
                if (item) this.moveTeacherToSelected(item);
            },
            moveAllTeachersToSelected() {
                this.teacherPickList = [[], [...this.teacherPickList[1], ...this.teacherPickList[0]]];
            },
            removeTeacherFromSelected(item) {
                this.teacherPickList[1] = this.teacherPickList[1].filter(t => t.id !== item.id);
                this.teacherPickList[0] = [...this.teacherPickList[0], item];
                this.selectedChosenTeacher = null;
            },
            removeSingleTeacherFromSelected() {
                if (!this.selectedChosenTeacher) return;
                const item = this.teacherPickList[1].find(t => t.id === this.selectedChosenTeacher);
                if (item) this.removeTeacherFromSelected(item);
            },
            removeAllTeachersFromSelected() {
                this.teacherPickList = [[...this.teacherPickList[0], ...this.teacherPickList[1]], []];
            },

            // ── Group dual-box methods ──────────────────────────
            moveGroupToSelected(item) {
                this.groupPickList[0] = this.groupPickList[0].filter(g => g.id !== item.id);
                this.groupPickList[1] = [...this.groupPickList[1], item];
                this.selectedAvailableGroup = null;
            },
            moveSingleGroupToSelected() {
                if (!this.selectedAvailableGroup) return;
                const item = this.groupPickList[0].find(g => g.id === this.selectedAvailableGroup);
                if (item) this.moveGroupToSelected(item);
            },
            moveAllGroupsToSelected() {
                this.groupPickList = [[], [...this.groupPickList[1], ...this.groupPickList[0]]];
            },
            removeGroupFromSelected(item) {
                this.groupPickList[1] = this.groupPickList[1].filter(g => g.id !== item.id);
                this.groupPickList[0] = [...this.groupPickList[0], item];
                this.selectedChosenGroup = null;
            },
            removeSingleGroupFromSelected() {
                if (!this.selectedChosenGroup) return;
                const item = this.groupPickList[1].find(g => g.id === this.selectedChosenGroup);
                if (item) this.removeGroupFromSelected(item);
            },
            removeAllGroupsFromSelected() {
                this.groupPickList = [[...this.groupPickList[0], ...this.groupPickList[1]], []];
            },

            // ── Non-teacher dual-box methods ───────────────────
            moveNonTeacherToSelected(item) {
                this.nonTeacherPickList[0] = this.nonTeacherPickList[0].filter(n => n.id !== item.id);
                this.nonTeacherPickList[1] = [...this.nonTeacherPickList[1], item];
                this.selectedAvailableNonTeacher = null;
            },
            moveSingleNonTeacherToSelected() {
                if (!this.selectedAvailableNonTeacher) return;
                const item = this.nonTeacherPickList[0].find(n => n.id === this.selectedAvailableNonTeacher);
                if (item) this.moveNonTeacherToSelected(item);
            },
            moveAllNonTeachersToSelected() {
                this.nonTeacherPickList = [[], [...this.nonTeacherPickList[1], ...this.nonTeacherPickList[0]]];
            },
            removeNonTeacherFromSelected(item) {
                this.nonTeacherPickList[1] = this.nonTeacherPickList[1].filter(n => n.id !== item.id);
                this.nonTeacherPickList[0] = [...this.nonTeacherPickList[0], item];
                this.selectedChosenNonTeacher = null;
            },
            removeSingleNonTeacherFromSelected() {
                if (!this.selectedChosenNonTeacher) return;
                const item = this.nonTeacherPickList[1].find(n => n.id === this.selectedChosenNonTeacher);
                if (item) this.removeNonTeacherFromSelected(item);
            },
            removeAllNonTeachersFromSelected() {
                this.nonTeacherPickList = [[...this.nonTeacherPickList[0], ...this.nonTeacherPickList[1]], []];
            },

            // ── Existing methods (unchanged logic) ─────────────
            loadGroups() {
                this.groupPickList = [[], []];
                axios.get('/' + this.mode + '/groups/' + this.standardLink_id)
                    .then(response => {
                        this.grouplist = response.data.data;
                        this.groupPickList = [this.grouplist, []];
                    })
                    .catch(error => { console.log(error); });
            },

            setAssignee(type) {
                this.assignee = type;
                this.selectAssignee();
            },

            getData(url, query) {
                axios.get(url + '?' + (query || '')).then(response => {
                    this.tasks = response.data;
                    this.setData();
                });
            },

            setData() {
                if (Object.keys(this.tasks).length > 0) {
                    this.standardLinklist = this.tasks.standardlinks;
                    this.studentlist = this.tasks.students;
                    this.teacherlist = this.tasks.teachers;
                    this.task_date = this.tasks.task_date;
                    this.isLoading = false;
                    this.studentPickList = [this.studentlist, []];
                    this.teacherPickList = [this.teacherlist, []];
                    this.classPickList = [this.standardLinklist, []];
                    this.nonTeacherPickList = [this.tasks.nonteachers || [], []];
                    this.groupPickList = [this.tasks.groups || [], []];
                }
            },

            selectAssignee() {
                // Logic preserved but DOM manipulation replaced with Vue reactive rendering
            },

            enableStudent() {
                this.params = { standardlink_id: this.standardLink_id };
                this.final = this.url + '/' + this.mode + '/task/add/list';
                Object.keys(this.params).forEach(key => {
                    this.final = this.addParam(this.final, key, this.params[key]);
                });
                this.getData(this.final);
            },

            addParam(url, param, value) {
                param = encodeURIComponent(param);
                var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
                var a = document.createElement('a');
                var regex = new RegExp(r);
                var str = param + (value ? "=" + encodeURIComponent(value) : "");
                a.href = url;
                var q = a.search.replace(regex, "$1" + str);
                if (q === a.search) {
                    a.search += (a.search ? "&" : "") + str;
                } else {
                    a.search = q;
                }
                return a.href;
            },

            selectAll(e, type) {
                if (type === 'student') {
                    if (e.target.checked) {
                        this.selected = this.studentlist.map(user => user.id);
                        this.selectedUsers = [...this.selected];
                    } else {
                        this.selected = [];
                        this.selectedUsers = [];
                    }
                }
                if (type === 'teacher') {
                    if (e.target.checked) {
                        this.teachers = this.teacherlist.map(user => user.id);
                        this.selectedTeachers = [...this.teachers];
                    } else {
                        this.teachers = [];
                        this.selectedTeachers = [];
                    }
                }
            },

            selectNone(e, type) {
                if (e.target.checked) {
                    if (type === 'student') { this.selected = []; this.selectedUsers = []; }
                    if (type === 'teacher') { this.teachers = []; this.selectedTeachers = []; }
                }
            },

            selectedCount(id, e, type) {
                if (type === 'student') this.selectedUsers = [...this.selected];
                if (type === 'teacher') this.selectedTeachers = [...this.teachers];
            },

            removeUser(item, arr) {
                for (var i = 0; i < arr.length; i++) {
                    if (arr[i] == item) { arr.splice(i, 1); break; }
                }
            },

            submitForm() {
                this.errors = [];
                this.success = null;
                if (this.hidecolumns != 'false') {
                    this.assignee = 'self';
                }
                console.log(this.groupPickList[1].map(item => item.id));
                axios.post('/' + this.mode + '/task/add', {
                    assignee: this.assignee,
                    standardLink_id: this.standardLink_id,
                    selected: this.studentPickList[1].map(item => item.id),
                    selectedUsers: this.studentPickList[1].map(item => item.id),
                    selectedUsersCount: this.studentPickList[1].length,
                    teachers: this.teacherPickList[1].map(item => item.id),
                    selectedTeachers: this.teacherPickList[1].map(item => item.id),
                    selectedTeachersCount: this.teacherPickList[1].length,
                    title: this.title,
                    to_do_list: this.to_do_list,
                    task_date: this.task_date,
                    reminder: this.reminder,
                    reminder_date: this.reminder_date,
                    priority: this.priority,
                    groups: this.groupPickList[1].map(item => item.id),
                    class_ids: this.classPickList[1].map(item => item.id),
                    selectedClassCount: this.classPickList[1].length,
                    non_teachers: this.nonTeacherPickList[1].map(item => item.id),
                    selectedNonTeacherCount: this.nonTeacherPickList[1].length,
                    task_type: this.task_type,
                }).then(response => {
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            resetForm() {
                window.location.reload();
            },
        },

        created() {
            this.getData('/' + this.mode + '/task/add/list');
        }
    }
</script>

<style>
/* ── Shell ─────────────────────────────────────────────── */
.task-form-shell {
    /*background: #f4f5f7;*/
    min-height: 100vh;
    padding: 24px 32px;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    box-sizing: border-box;
}

.task-card {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.08);
    padding: 32px 40px;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}

/* ── Success banner ────────────────────────────────────── */
.success-banner {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
    border-radius: 8px;
    padding: 10px 14px;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 20px;
}

/* ── Field groups ──────────────────────────────────────── */
.field-group {
    margin-bottom: 22px;
}

.field-label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 7px;
    letter-spacing: 0.01em;
}

.optional {
    font-weight: 400;
    color: #9ca3af;
    font-size: 12px;
}

.required {
    color: #ef4444;
    margin-left: 2px;
}

.field-input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    color: #111827;
    background: #fff;
    transition: border-color 0.15s, box-shadow 0.15s;
    box-sizing: border-box;
    outline: none;
}

.field-input:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.08);
}

.field-input::placeholder { color: #9ca3af; }

.field-textarea {
    min-height: 88px;
    resize: vertical;
    font-family: inherit;
}

.field-select {
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 12px center;
    padding-right: 32px;
}

.field-input-wrap {
    position: relative;
}

.date-input {
    width: 100%;
    padding: 9px 12px;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
}

.field-error {
    display: block;
    color: #ef4444;
    font-size: 11.5px;
    font-weight: 500;
    margin-top: 4px;
}

/* ── Assignee grid ─────────────────────────────────────── */
.assignee-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 10px;
    width: 100%;
}

@media (max-width: 520px) {
    .assignee-grid { grid-template-columns: repeat(2, 1fr); }
}

.assignee-card {
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    padding: 11px 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    transition: background 0.15s, border-color 0.15s, box-shadow 0.15s;
    user-select: none;
}

.assignee-card:hover {
    background: #f9fafb;
    border-color: #c7cad0;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06);
}

.assignee-card.active {
    background: #111827;
    border-color: #111827;
    color: #fff;
}

.assignee-card.active .assignee-sub {
    color: rgba(255,255,255,0.6);
}

.assignee-icon { font-size: 16px; flex-shrink: 0; }

.assignee-title {
    font-weight: 600;
    font-size: 13px;
    line-height: 1.3;
}

.assignee-sub {
    font-size: 11px;
    color: #6b7280;
    margin-top: 2px;
}

/* ── Dual box ──────────────────────────────────────────── */
.dual-box {
    display: flex;
    gap: 0;
    border: 1.5px solid #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
    background: #fff;
    width: 100%;
}

.dual-panel {
    flex: 1;
    min-width: 0;
    display: flex;
    flex-direction: column;
}

.dual-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 8px 12px;
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
    font-size: 11px;
    font-weight: 700;
    color: #6b7280;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.dual-count {
    background: #e5e7eb;
    color: #374151;
    border-radius: 20px;
    padding: 1px 8px;
    font-size: 11px;
    font-weight: 700;
}

.dual-search {
    display: flex;
    align-items: center;
    padding: 6px 10px;
    gap: 6px;
    border-bottom: 1px solid #f3f4f6;
    background: #fff;
}

.search-icon { font-size: 13px; color: #9ca3af; }

.dual-search-input {
    border: none;
    outline: none;
    font-size: 13px;
    color: #374151;
    flex: 1;
    background: transparent;
}

.dual-search-input::placeholder { color: #c4c9d4; }

.dual-list {
    flex: 1;
    overflow-y: auto;
    min-height: 220px;
    max-height: 320px;
    padding: 4px;
}

.dual-list-selected {
    background: #fafafa;
}

.dual-item {
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 8px 10px;
    border-radius: 7px;
    cursor: pointer;
    transition: background 0.12s;
    user-select: none;
}

.dual-item:hover { background: #eef2ff; }

.dual-item.highlighted {
    background: #e0e7ff;
    outline: 1.5px solid #818cf8;
}

.dual-empty {
    text-align: center;
    color: #c4c9d4;
    font-size: 12px;
    padding: 28px 0;
}

/* ── Transfer buttons ──────────────────────────────────── */
.transfer-btns {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 5px;
    padding: 10px 6px;
    background: #f9fafb;
    border-left: 1px solid #e5e7eb;
    border-right: 1px solid #e5e7eb;
}

.transfer-btn {
    width: 28px;
    height: 28px;
    background: #fff;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #374151;
    font-weight: 700;
    transition: all 0.15s;
    line-height: 1;
}

.transfer-btn:hover {
    background: #111827;
    border-color: #111827;
    color: #fff;
}

/* ── Item avatar ───────────────────────────────────────── */
.item-avatar {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: #f59e0b;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-transform: uppercase;
}

.avatar-icon {
    background: #e5e7eb !important;
    font-size: 14px;
}

.item-name {
    font-weight: 600;
    font-size: 13px;
    color: #111827;
    line-height: 1.3;
}

.item-meta {
    font-size: 11px;
    color: #9ca3af;
    margin-top: 1px;
}

/* ── Selected tags ─────────────────────────────────────── */
.selected-tags {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 10px;
    font-size: 12.5px;
    color: #6b7280;
}

.tag-count { font-weight: 500; }

.clear-all-btn {
    background: none;
    border: none;
    color: #6366f1;
    font-size: 12.5px;
    cursor: pointer;
    font-weight: 600;
    padding: 0;
}

.clear-all-btn:hover { color: #4f46e5; text-decoration: underline; }

.tags-row {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
    margin-top: 8px;
}

.tag-pill {
    background: #f0f1ff;
    color: #4338ca;
    border: 1px solid #c7d2fe;
    border-radius: 20px;
    padding: 3px 10px 3px 12px;
    font-size: 12px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 6px;
}

.tag-remove {
    cursor: pointer;
    color: #818cf8;
    font-size: 14px;
    line-height: 1;
    font-weight: 700;
}

.tag-remove:hover { color: #4338ca; }

.info-text {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 8px;
    font-style: italic;
}

/* ── Task type ─────────────────────────────────────────── */
.task-type-row {
    display: flex;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    overflow: hidden;
    width: fit-content;
    max-width: 100%;
}

.task-type-btn {
    padding: 9px 20px;
    cursor: pointer;
    background: #f9fafb;
    font-size: 13.5px;
    font-weight: 500;
    border-right: 1.5px solid #d1d5db;
    transition: all 0.15s;
    user-select: none;
    color: #374151;
}

.task-type-btn:last-child { border-right: none; }

.task-type-btn.active {
    background: #111827;
    color: #fff;
}

/* ── Actions ───────────────────────────────────────────── */
.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 8px;
}

.btn-submit {
    padding: 9px 22px;
    background: #111827;
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.15s;
}

.btn-submit:hover { background: #1f2937; }

.btn-reset {
    padding: 9px 20px;
    background: #fff;
    color: #374151;
    border: 1.5px solid #d1d5db;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.15s;
}

.btn-reset:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}
</style>