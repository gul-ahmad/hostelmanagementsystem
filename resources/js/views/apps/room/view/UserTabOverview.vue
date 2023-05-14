<!-- eslint-disable vue/no-parsing-error -->
<script setup>
import UserInvoiceTable from './UserInvoiceTable.vue'

// Import FilePond
import vueFilePond, { setOptions } from 'vue-filepond'

// Import plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'

import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'


// Import styles
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'

const emit = defineEmits([
  'roomImage',
])

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview,FilePondPluginFileValidateSize)

const isFormValid = ref(false)
const refForm = ref()
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
const myFiles = ref([])

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      alert('dfdfd')

      const selectedFiles = Array.from(myFiles)

      console.log(selectedFiles)

      const roomImage = {
        files: selectedFiles,
      }

   
      // Adding a new room
      emit('roomImage', roomImage)
      
      // emit('update:isDrawerOpen', false)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}


const handleFilePondInit = () => {
  
  console.log('FilePond has initialized')


  
  //const pond = this.$refs.pond.filepond

  //Add a hook to send the CSRF token with the request
  setOptions({
    server: {
      process: '/api/auth/filepond-upload',
      timeout: 17000,

      headers: {
        'X-CSRF-TOKEN': csrfToken,

      },

    },

  })
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <PerfectScrollbar :options="{ wheelPropagation: false }">
        <VCard
          title="Room Images"
          flat
        >
          <VCardText>
            <!-- 👉 Form -->
            <VForm
              ref="refForm"
              v-model="isFormValid"
              enctype="multipart/form-data"
              @submit.prevent="onSubmit"
            >
              <VRow>
                <!-- 👉  Branch -->
                <VCol cols="12">
                  <FilePond
                    ref="pond"
                    name="test"
                    class-name="my-pond"
                    label-idle="Drop files here..."
                    allow-multiple="true"
                    accepted-file-types="image/jpeg, image/png"
                    :files="myFiles"
                    @init="handleFilePondInit"
                  />
                </VCol>

                <!-- 👉 Submit and Cancel -->
                <VCol cols="12">
                  <VBtn
                    type="submit"
                    class="me-3"
                  >
                    Submit
                  </VBtn>
                  <VBtn
                    type="reset"
                    variant="tonal"
                    color="secondary"
                    @click="closeNavigationDrawer"
                  >
                    Cancel
                  </VBtn>
                </VCol>
              </VRow>
            </VForm>
          </VCardText>
        </VCard>
      </PerfectScrollbar>
    </VCol>

    

    <VCol cols="12">
      <UserInvoiceTable />
    </VCol>
  </VRow>
</template>
