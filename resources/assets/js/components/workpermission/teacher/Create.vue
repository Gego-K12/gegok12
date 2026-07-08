<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="date" class="tw-form-label">Permission Date<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="date" name="date" v-model="date" class="tw-form-control w-full" id="date">
            </div>
            <span v-if="errors.date" class="text-red-500 text-xs font-semibold">{{errors.date[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="type" class="tw-form-label">Permission Type<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select name="type" id="type" v-model="type" class="tw-form-control w-full">
                <option value="" disabled>Select Type</option>
                <option v-for="option in typeOptions" :key="option.value" :value="option.value">{{ option.label }}</option>
              </select>
            </div>
            <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="from_time" class="tw-form-label">From Time<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="time" name="from_time" v-model="from_time" class="tw-form-control w-full" id="from_time">
            </div>
            <span v-if="errors.from_time" class="text-red-500 text-xs font-semibold">{{errors.from_time[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="to_time" class="tw-form-label">To Time<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="time" name="to_time" v-model="to_time" class="tw-form-control w-full" id="to_time">
            </div>
            <span v-if="errors.to_time" class="text-red-500 text-xs font-semibold">{{errors.to_time[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="reason" class="tw-form-label">Reason<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <textarea name="reason" id="reason" v-model="reason" class="tw-form-control w-full" rows="3" placeholder="Enter Reason"></textarea>
            </div>
            <span v-if="errors.reason" class="text-red-500 text-xs font-semibold">{{errors.reason[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="contact_number" class="tw-form-label">Contact Number During Permission</label>
            </div>
            <div class="mb-2">
              <input type="text" name="contact_number" v-model="contact_number" class="tw-form-control w-full" id="contact_number">
            </div>
            <span v-if="errors.contact_number" class="text-red-500 text-xs font-semibold">{{errors.contact_number[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8 flex items-center">
            <input type="checkbox" id="is_emergency" v-model="is_emergency" class="mr-2">
            <label for="is_emergency" class="tw-form-label mb-0">Emergency Permission</label>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="attachment" class="tw-form-label">Attachment</label>
            </div>
            <div class="mb-2">
              <input type="file" name="attachment" id="attachment" @change="onFileChange" class="tw-form-control w-full">
            </div>
            <span v-if="errors.attachment" class="text-red-500 text-xs font-semibold">{{errors.attachment[0]}}</span>
          </div>
        </div>
      </div>

      <div class="my-6">
        <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
        <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['url'],
    data() {
      return {
        typeOptions: [
          { value: 'personal_work', label: 'Personal Work' },
          { value: 'medical', label: 'Medical' },
          { value: 'bank_work', label: 'Bank Work' },
          { value: 'family_emergency', label: 'Family Emergency' },
          { value: 'official_school_work', label: 'Official School Work' },
          { value: 'government_office_work', label: 'Government Office Work' },
          { value: 'late_arrival', label: 'Late Arrival Permission' },
          { value: 'early_leaving', label: 'Early Leaving Permission' },
          { value: 'temporary_out_pass', label: 'Temporary Out-pass' },
          { value: 'other', label: 'Other' },
        ],
        date: '',
        from_time: '',
        to_time: '',
        type: '',
        reason: '',
        contact_number: '',
        is_emergency: false,
        attachment: null,
        errors: [],
        success: null,
      }
    },

    methods: {
      resetForm() {
        window.location.reload();
      },

      onFileChange(e) {
        this.attachment = e.target.files[0] || null;
      },

      submitForm() {
        this.errors = [];
        this.success = null;

        let formData = new FormData();
        formData.append('date', this.date);
        formData.append('from_time', this.from_time);
        formData.append('to_time', this.to_time);
        formData.append('type', this.type);
        formData.append('reason', this.reason);
        formData.append('contact_number', this.contact_number);
        formData.append('is_emergency', this.is_emergency ? 1 : 0);
        if (this.attachment) {
          formData.append('attachment', this.attachment);
        }

        axios.post('/teacher/workpermission/add', formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          this.success = response.data.success;
          window.location.href = '/teacher/workpermissions';
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },
    },
  }
</script>
