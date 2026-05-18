<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div v-if="this.error!=null" class="alert alert-danger" id="error-alert">{{this.error}}</div>

            <!-- STATUS BAR -->
            <div class="bg-gray-50 border rounded p-4 mb-4">
                <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                    <div class="lg:w-1/5">
                        <label class="tw-form-label font-bold">Application #</label>
                        <p class="text-sm py-2 font-semibold">{{ application_no }}</p>
                    </div>
                    <div class="lg:w-1/5">
                        <label class="tw-form-label font-bold">Applied On</label>
                        <p class="text-sm py-2">{{ created_at }}</p>
                    </div>
                    <div class="lg:w-1/5">
                        <label class="tw-form-label font-bold">Status</label>
                        <select class="tw-form-control w-full" v-model="application_status">
                            <option value="" disabled>Select Status</option>
                            <option v-for="status in statuslist" :value="status.id">{{ status.name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/5" v-if="application_status=='Approved'">
                        <label class="tw-form-label font-bold">Section</label>
                        <select class="tw-form-control w-full" v-model="section_id">
                            <option value="" disabled>Select Section</option>
                            <option v-for="section in sectionlist" :value="section.id">{{ section.name }}</option>
                        </select>
                        <span v-if="errors.section_id" class="text-red-500 text-xs font-semibold">{{ errors.section_id[0] }}</span>
                    </div>
                    <div class="lg:w-1/5" v-if="gfeeEnabled && application_status=='Approved'">
                        <label class="tw-form-label font-bold">Fee Type</label>
                        <select class="tw-form-control w-full" v-model="fee_group_id">
                            <option value="" disabled>Select</option>
                            <option v-for="fees in feelist" :value="fees.id">{{ fees.name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/5" v-if="gfeeEnabled && application_status=='Approved'">
                        <label class="tw-form-label font-bold">Payment</label>
                        <select class="tw-form-control w-full" v-model="payment_status">
                            <option value="" disabled>Status</option>
                            <option v-for="list in paymentlist" :value="list.id">{{ list.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row gap-4 mt-3">
                    <div class="lg:w-1/2">
                        <label class="tw-form-label font-bold">Principal Notes <span class="text-xs text-gray-400">(internal)</span></label>
                        <textarea class="tw-form-control w-full" v-model="principal_notes" rows="2" placeholder="Interview observations, notes for internal use"></textarea>
                    </div>
                    <div class="lg:w-1/2" v-if="application_status=='Rejected' || application_status=='Approved'">
                        <label class="tw-form-label font-bold">Message to Applicant <span class="text-xs text-gray-400">(will be sent via email)</span></label>
                        <textarea class="tw-form-control w-full" v-model="remarks" rows="2" :placeholder="application_status=='Rejected' ? 'Reason for rejection' : 'Congratulations message'"></textarea>
                    </div>
                </div>
            </div>

            <!-- STUDENT DETAILS -->
            <div class="border rounded p-4 mb-4">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Student Details</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-2/3">
                        <div class="flex flex-col lg:flex-row">
                            <div class="lg:w-2/5 lg:mr-2 my-1">
                                <label class="tw-form-label">Name<span class="text-red-500">*</span></label>
                                <input type="text" v-model="name" class="tw-form-control w-full py-2">
                            </div>
                            <div class="lg:w-1/5 lg:mr-2 my-1">
                                <label class="tw-form-label">Date of Birth<span class="text-red-500">*</span></label>
                                <input type="date" v-model="date_of_birth" class="tw-form-control w-full py-2">
                            </div>
                            <div class="lg:w-1/5 lg:mr-2 my-1">
                                <label class="tw-form-label">Gender<span class="text-red-500">*</span></label>
                                <select v-model="gender" class="tw-form-control w-full py-2">
                                    <option value="male">Boy</option>
                                    <option value="female">Girl</option>
                                </select>
                            </div>
                            <div class="lg:w-1/5 lg:mr-2 my-1">
                                <label class="tw-form-label">Class Applied</label>
                                <select v-model="standard_id" class="tw-form-control w-full py-2">
                                    <option v-for="std in standardlist" :value="std.id">{{ std.name }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-col lg:flex-row">
                            <div class="lg:w-1/6 lg:mr-2 my-1">
                                <label class="tw-form-label">Height (cm)</label>
                                <input type="number" v-model="height" min="30" max="183" class="tw-form-control w-full py-2">
                            </div>
                            <div class="lg:w-1/6 lg:mr-2 my-1">
                                <label class="tw-form-label">Weight (kgs)</label>
                                <input type="number" v-model="weight" min="6" max="100" class="tw-form-control w-full py-2">
                            </div>
                            <div class="lg:w-1/6 lg:mr-2 my-1">
                                <label class="tw-form-label">Blood Group</label>
                                <select v-model="blood_group" class="tw-form-control w-full py-2">
                                    <option value="">Select</option>
                                    <option v-for="bg in bloodGroupList" :value="bg.num">{{ bg.name }}</option>
                                </select>
                            </div>
                            <div class="lg:w-1/6 lg:mr-2 my-1">
                                <label class="tw-form-label">Siblings</label>
                                <select v-model="siblings" class="tw-form-control w-full py-2">
                                    <option value="">Select</option>
                                    <option value="yes">Yes</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <div class="lg:w-1/3 lg:mr-2 my-1">
                                <label class="tw-form-label">Aadhaar Number</label>
                                <input type="text" v-model="aadhar_number" maxlength="12" class="tw-form-control w-full py-2">
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/3 flex justify-center items-start">
                        <div v-if="avatar" class="my-2">
                            <img :src="url+'/'+avatar" style="width: 120px; height: 120px;" class="border rounded">
                        </div>
                        <div v-else class="my-2">
                            <img :src="url+'/uploads/user/avatar/default-user.jpg'" style="width: 120px; height: 120px;" class="border rounded">
                        </div>
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Birth Place</label>
                        <input type="text" v-model="birth_place" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Nationality</label>
                        <input type="text" v-model="nationality" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Religion</label>
                        <input type="text" v-model="religion" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Mother Tongue</label>
                        <input type="text" v-model="mother_tongue" class="tw-form-control w-full py-2">
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Community</label>
                        <select v-model="community" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option v-for="c in communityList" :value="c">{{ c }}</option>
                        </select>
                    </div>
                    <div class="lg:w-5/12 lg:mr-2 my-1">
                        <label class="tw-form-label">Identification Marks</label>
                        <input type="text" v-model="identification_marks" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-5/12 lg:mr-2 my-1">
                        <label class="tw-form-label">School Last Studied</label>
                        <input type="text" v-model="school_last_studied" class="tw-form-control w-full py-2">
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/3 lg:mr-2 my-1">
                        <label class="tw-form-label">Reason for Leaving</label>
                        <input type="text" v-model="reason_for_leaving" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/3 lg:mr-2 my-1">
                        <label class="tw-form-label">Permanent Address</label>
                        <textarea v-model="permanent_address" class="tw-form-control w-full" rows="2"></textarea>
                    </div>
                    <div class="lg:w-1/3 lg:mr-2 my-1">
                        <label class="tw-form-label">Communication Address</label>
                        <textarea v-model="address_for_communication" class="tw-form-control w-full" rows="2"></textarea>
                    </div>
                </div>
            </div>

            <!-- ACADEMIC DETAILS -->
            <div class="border rounded p-4 mb-4">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Academic Details</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Board of Education</label>
                        <select v-model="board_of_education" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option v-for="b in boardList" :value="b.id">{{ b.name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Choice of Language</label>
                        <select v-model="choice_of_language" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option v-for="l in languageList" :value="l.id">{{ l.name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Group Selection</label>
                        <select v-model="group_selection" class="tw-form-control w-full py-2">
                            <option value="">N/A</option>
                            <option v-for="g in groupList" :value="g.id">{{ g.name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Board Reg. Number</label>
                        <input type="text" v-model="board_registration_number" class="tw-form-control w-full py-2">
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/2 lg:mr-2 my-1">
                        <label class="tw-form-label">Half Yearly Marks</label>
                        <input type="text" v-model="half_yearly_mark_details" class="tw-form-control w-full py-2" placeholder="e.g. English:85, Tamil:90, Maths:92">
                    </div>
                </div>
            </div>

            <!-- FATHER DETAILS -->
            <div class="border rounded p-4 mb-4">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Father's Details</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Name<span class="text-red-500">*</span></label>
                        <input type="text" v-model="father_name" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Qualification</label>
                        <select v-model="father_qualification_id" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option v-for="q in qualificationlist" :value="q.id">{{ q.display_name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Occupation</label>
                        <input type="text" v-model="father_occupation" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Designation</label>
                        <input type="text" v-model="father_designation" class="tw-form-control w-full py-2">
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Organisation</label>
                        <input type="text" v-model="father_organisation" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Annual Income</label>
                        <input type="number" v-model="father_income" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Mobile<span class="text-red-500">*</span></label>
                        <input type="tel" v-model="father_mobile_no" maxlength="15" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Email</label>
                        <input type="email" v-model="father_email" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Aadhaar</label>
                        <input type="text" v-model="father_aadhar_number" maxlength="12" class="tw-form-control w-full py-2">
                    </div>
                </div>
            </div>

            <!-- MOTHER DETAILS -->
            <div class="border rounded p-4 mb-4">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Mother's Details</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Name<span class="text-red-500">*</span></label>
                        <input type="text" v-model="mother_name" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Qualification</label>
                        <select v-model="mother_qualification_id" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option v-for="q in qualificationlist" :value="q.id">{{ q.display_name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Occupation</label>
                        <input type="text" v-model="mother_occupation" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Designation</label>
                        <input type="text" v-model="mother_designation" class="tw-form-control w-full py-2">
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Organisation</label>
                        <input type="text" v-model="mother_organisation" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Annual Income</label>
                        <input type="number" v-model="mother_income" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Mobile<span class="text-red-500">*</span></label>
                        <input type="tel" v-model="mother_mobile_no" maxlength="15" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Email</label>
                        <input type="email" v-model="mother_email" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Aadhaar</label>
                        <input type="text" v-model="mother_aadhar_number" maxlength="12" class="tw-form-control w-full py-2">
                    </div>
                </div>
            </div>

            <!-- EMERGENCY CONTACTS -->
            <div class="border rounded p-4 mb-4">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Emergency Contacts</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Contact 1</label>
                        <input type="tel" v-model="emergency_contact_1" maxlength="15" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Relation</label>
                        <input type="text" v-model="relation_with_student_1" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Contact 2</label>
                        <input type="tel" v-model="emergency_contact_2" maxlength="15" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Relation</label>
                        <input type="text" v-model="relation_with_student_2" class="tw-form-control w-full py-2">
                    </div>
                </div>
            </div>

            <!-- PERSONAL DETAILS -->
            <div class="border rounded p-4 mb-4">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Personal Details</h2>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Medical History</label>
                        <select v-model="medical_history" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="lg:w-1/3 lg:mr-2 my-1" v-if="medical_history=='yes'">
                        <label class="tw-form-label">Medical Details</label>
                        <input type="text" v-model="medical_details" class="tw-form-control w-full py-2">
                    </div>
                    <div class="lg:w-1/6 lg:mr-2 my-1">
                        <label class="tw-form-label">Extra Curricular</label>
                        <select v-model="extra_curricular_activities" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                    </div>
                    <div class="lg:w-1/3 lg:mr-2 my-1" v-if="extra_curricular_activities=='yes'">
                        <label class="tw-form-label">Activities</label>
                        <input type="text" v-model="activities" class="tw-form-control w-full py-2">
                    </div>
                </div>
                <div class="flex flex-col lg:flex-row">
                    <div class="lg:w-1/4 lg:mr-2 my-1">
                        <label class="tw-form-label">Mode of Transport</label>
                        <select v-model="mode_of_transport" class="tw-form-control w-full py-2">
                            <option value="">Select</option>
                            <option v-for="t in transportList" :value="t.id">{{ t.name }}</option>
                        </select>
                    </div>
                    <div class="lg:w-1/3 lg:mr-2 my-1" v-if="mode_of_transport=='auto' || mode_of_transport=='rickshaw' || mode_of_transport=='taxi'">
                        <label class="tw-form-label">Transport Details</label>
                        <input type="text" v-model="transport_details" class="tw-form-control w-full py-2" placeholder="Driver name, phone">
                    </div>
                </div>
            </div>

            <!-- CHANGE LOG -->
            <div class="border rounded p-4 mb-4" v-if="change_logs.length > 0">
                <h2 class="text-lg font-bold mb-3 text-blue-700">Change History</h2>
                <table class="w-full text-xs">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="text-left px-2 py-2">Date</th>
                            <th class="text-left px-2 py-2">Field</th>
                            <th class="text-left px-2 py-2">Old Value</th>
                            <th class="text-left px-2 py-2">New Value</th>
                            <th class="text-left px-2 py-2">Changed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="log in change_logs" class="border-b">
                            <td class="px-2 py-1">{{ log.changed_at }}</td>
                            <td class="px-2 py-1">{{ formatFieldName(log.field_name) }}</td>
                            <td class="px-2 py-1 text-red-600">{{ log.old_value || '-' }}</td>
                            <td class="px-2 py-1 text-green-600">{{ log.new_value || '-' }}</td>
                            <td class="px-2 py-1">{{ log.changed_by_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- SUBMIT -->
            <div class="my-6">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Save Changes</a>
                <a :href="url+'/admin/admissions'" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium">Back to List</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['id', 'url'],
        data() {
            return {
                application_no: '', application_status: '', created_at: '', section_id: '', fee_group_id: '', payment_status: '', remarks: '', principal_notes: '',
                name: '', date_of_birth: '', gender: '', standard_id: '', height: '', weight: '', blood_group: '',
                birth_place: '', nationality: '', religion: '', community: '', mother_tongue: '',
                identification_marks: '', aadhar_number: '', school_last_studied: '', reason_for_leaving: '',
                permanent_address: '', address_for_communication: '', siblings: '', avatar: '',
                half_yearly_mark_details: '', board_of_education: '', choice_of_language: '', group_selection: '', board_registration_number: '',
                father_name: '', father_qualification_id: '', father_designation: '', father_occupation: '',
                father_organisation: '', father_income: '', father_mobile_no: '', father_email: '', father_aadhar_number: '', father_avatar: '',
                mother_name: '', mother_qualification_id: '', mother_designation: '', mother_occupation: '',
                mother_organisation: '', mother_income: '', mother_mobile_no: '', mother_email: '', mother_aadhar_number: '', mother_avatar: '',
                emergency_contact_1: '', relation_with_student_1: '', emergency_contact_2: '', relation_with_student_2: '',
                medical_history: '', medical_details: '', extra_curricular_activities: '', activities: '',
                mode_of_transport: '', transport_details: '',
                sectionlist: [], standardlist: [], qualificationlist: [], feelist: [], change_logs: [],
                statuslist: [{id:'Draft',name:'Draft'},{id:'Pending',name:'Pending'},{id:'Rejected',name:'Rejected'},{id:'Approved',name:'Approved'}],
                paymentlist: [{id:'paid',name:'Paid'},{id:'not_paid',name:'Not Paid'}],
                bloodGroupList: [{num:'a+',name:'A+'},{num:'a1+',name:'A1+'},{num:'b+',name:'B+'},{num:'b1+',name:'B1+'},{num:'o+',name:'O+'},{num:'ab+',name:'AB+'},{num:'a1b+',name:'A1B+'},{num:'a-',name:'A-'},{num:'a1-',name:'A1-'},{num:'b-',name:'B-'},{num:'b1-',name:'B1-'},{num:'o-',name:'O-'},{num:'ab-',name:'AB-'},{num:'a1b-',name:'A1B-'}],
                communityList: ['BC','BCM','FC','MBC','OBC','SC','SCA','ST','Others'],
                boardList: [{id:'CBSE',name:'CBSE'},{id:'Matric',name:'Matric'},{id:'ICSE',name:'ICSE'},{id:'State Board',name:'State Board'},{id:'Anglo Indian',name:'Anglo Indian'},{id:'Others',name:'Others'}],
                languageList: [{id:'Tamil',name:'Tamil'},{id:'Hindi',name:'Hindi'},{id:'Sanskrit',name:'Sanskrit'},{id:'French',name:'French'},{id:'English',name:'English'}],
                groupList: [{id:'group1',name:'Group 1: Maths, Physics, Chemistry & CS'},{id:'group2',name:'Group 2: Maths, Physics, Chemistry & Bio'},{id:'group3',name:'Group 3: Physics, Chemistry, Bio & CS'},{id:'group4',name:'Group 4: Commerce, Accountancy, Econ & BM'},{id:'group5',name:'Group 5: Commerce, Accountancy, Econ & CS'}],
                transportList: [{id:'auto',name:'Auto'},{id:'car',name:'Car'},{id:'city_bus',name:'City Bus'},{id:'cycle',name:'Cycle'},{id:'rickshaw',name:'Rickshaw'},{id:'school_bus',name:'School Bus'},{id:'taxi',name:'Taxi'},{id:'walking',name:'Walking'}],
                errors: [], success: null, error: null, gfeeEnabled: false,
            }
        },
        mounted() { this.gfeeEnabled = window.AppConfig?.gfee_enabled ?? false; },
        methods: {
            getData() {
                axios.get(this.url + '/admin/admission/show/' + this.id).then(response => {
                    let d = response.data;
                    this.application_no = d.application_no; this.application_status = d.application_status;
                    this.created_at = d.created_at; this.section_id = d.section_id || '';
                    this.fee_group_id = d.fee_group_id || ''; this.payment_status = d.payment_status || '';
                    this.remarks = d.remarks || ''; this.principal_notes = d.principal_notes || '';
                    this.name = d.name || ''; this.date_of_birth = d.date_of_birth || '';
                    this.gender = d.gender || ''; this.standard_id = d.standard_id || '';
                    this.height = d.height || ''; this.weight = d.weight || '';
                    this.blood_group = d.blood_group || ''; this.birth_place = d.birth_place || '';
                    this.nationality = d.nationality || ''; this.religion = d.religion || '';
                    this.community = d.community || ''; this.mother_tongue = d.mother_tongue || '';
                    this.identification_marks = d.identification_marks || '';
                    this.aadhar_number = d.aadhar_number || '';
                    this.school_last_studied = d.school_last_studied || '';
                    this.reason_for_leaving = d.reason_for_leaving || '';
                    this.permanent_address = d.permanent_address || '';
                    this.address_for_communication = d.address_for_communication || '';
                    this.siblings = d.siblings || ''; this.avatar = d.avatar || '';
                    this.half_yearly_mark_details = d.half_yearly_mark_details || '';
                    this.board_of_education = d.board_of_education || '';
                    this.choice_of_language = d.choice_of_language || '';
                    this.group_selection = d.group_selection || '';
                    this.board_registration_number = d.board_registration_number || '';
                    this.father_name = d.father_name || '';
                    this.father_qualification_id = d.father_qualification_id || '';
                    this.father_designation = d.father_designation || '';
                    this.father_occupation = d.father_occupation || '';
                    this.father_organisation = d.father_organisation || '';
                    this.father_income = d.father_income || '';
                    this.father_mobile_no = d.father_mobile_no || '';
                    this.father_email = d.father_email || '';
                    this.father_aadhar_number = d.father_aadhar_number || '';
                    this.mother_name = d.mother_name || '';
                    this.mother_qualification_id = d.mother_qualification_id || '';
                    this.mother_designation = d.mother_designation || '';
                    this.mother_occupation = d.mother_occupation || '';
                    this.mother_organisation = d.mother_organisation || '';
                    this.mother_income = d.mother_income || '';
                    this.mother_mobile_no = d.mother_mobile_no || '';
                    this.mother_email = d.mother_email || '';
                    this.mother_aadhar_number = d.mother_aadhar_number || '';
                    this.emergency_contact_1 = d.emergency_contact_1 || '';
                    this.relation_with_student_1 = d.relation_with_student_1 || '';
                    this.emergency_contact_2 = d.emergency_contact_2 || '';
                    this.relation_with_student_2 = d.relation_with_student_2 || '';
                    this.medical_history = d.medical_history || '';
                    this.medical_details = d.medical_details || '';
                    this.extra_curricular_activities = d.extra_curricular_activities || '';
                    this.activities = d.activities || '';
                    this.mode_of_transport = d.mode_of_transport || '';
                    this.transport_details = d.transport_details || '';
                    this.sectionlist = d.sectionlist || []; this.standardlist = d.standardlist || [];
                    this.qualificationlist = d.qualificationlist || []; this.feelist = d.feelist || [];
                    this.change_logs = d.change_logs || [];
                });
            },
            submitForm() {
                this.errors = []; this.success = null; this.error = null;
                let formData = new FormData();
                formData.append('application_status', this.application_status);
                formData.append('section_id', this.section_id);
                formData.append('fee_group_id', this.fee_group_id);
                formData.append('payment_status', this.payment_status);
                formData.append('remarks', this.remarks);
                formData.append('principal_notes', this.principal_notes);
                formData.append('name', this.name);
                formData.append('date_of_birth', this.date_of_birth);
                formData.append('gender', this.gender);
                formData.append('standard_id', this.standard_id);
                formData.append('height', this.height);
                formData.append('weight', this.weight);
                formData.append('blood_group', this.blood_group);
                formData.append('birth_place', this.birth_place);
                formData.append('nationality', this.nationality);
                formData.append('religion', this.religion);
                formData.append('community', this.community);
                formData.append('mother_tongue', this.mother_tongue);
                formData.append('identification_marks', this.identification_marks);
                formData.append('aadhar_number', this.aadhar_number);
                formData.append('school_last_studied', this.school_last_studied);
                formData.append('reason_for_leaving', this.reason_for_leaving);
                formData.append('permanent_address', this.permanent_address);
                formData.append('address_for_communication', this.address_for_communication);
                formData.append('siblings', this.siblings);
                formData.append('half_yearly_mark_details', this.half_yearly_mark_details);
                formData.append('board_of_education', this.board_of_education);
                formData.append('choice_of_language', this.choice_of_language);
                formData.append('group_selection', this.group_selection);
                formData.append('board_registration_number', this.board_registration_number);
                formData.append('father_name', this.father_name);
                formData.append('father_qualification_id', this.father_qualification_id);
                formData.append('father_designation', this.father_designation);
                formData.append('father_occupation', this.father_occupation);
                formData.append('father_organisation', this.father_organisation);
                formData.append('father_income', this.father_income);
                formData.append('father_mobile_no', this.father_mobile_no);
                formData.append('father_email', this.father_email);
                formData.append('father_aadhar_number', this.father_aadhar_number);
                formData.append('mother_name', this.mother_name);
                formData.append('mother_qualification_id', this.mother_qualification_id);
                formData.append('mother_designation', this.mother_designation);
                formData.append('mother_occupation', this.mother_occupation);
                formData.append('mother_organisation', this.mother_organisation);
                formData.append('mother_income', this.mother_income);
                formData.append('mother_mobile_no', this.mother_mobile_no);
                formData.append('mother_email', this.mother_email);
                formData.append('mother_aadhar_number', this.mother_aadhar_number);
                formData.append('emergency_contact_1', this.emergency_contact_1);
                formData.append('relation_with_student_1', this.relation_with_student_1);
                formData.append('emergency_contact_2', this.emergency_contact_2);
                formData.append('relation_with_student_2', this.relation_with_student_2);
                formData.append('medical_history', this.medical_history);
                formData.append('medical_details', this.medical_details);
                formData.append('extra_curricular_activities', this.extra_curricular_activities);
                formData.append('activities', this.activities);
                formData.append('mode_of_transport', this.mode_of_transport);
                formData.append('transport_details', this.transport_details);
                axios.post(this.url + '/admin/admission/update/' + this.id, formData).then(response => {
                    this.success = response.data.success;
                    this.getData();
                    window.scrollTo(0, 0);
                }).catch(error => {
                    if (error.response && error.response.data) {
                        this.errors = error.response.data.errors || [];
                        this.error = error.response.data.error || 'An error occurred';
                    }
                    window.scrollTo(0, 0);
                });
            },
            formatFieldName(name) {
                return name.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
            }
        },
        created() { this.getData(); }
    }
</script>
