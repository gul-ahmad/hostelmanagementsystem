<!-- eslint-disable vue/no-parsing-error -->
<script setup>
import UserInvoiceTable from './UserInvoiceTable.vue'

// Import FilePond
import vueFilePond, { setOptions } from 'vue-filepond'

// Import plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'

// Import styles
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'

const emit = defineEmits([
  'roomImage',
])

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview)

const isFormValid = ref(false)
const refForm = ref()
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
const myFiles = ref([])

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      // alert('dfdfd')

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
  // setOptions({
  //   credits: false,
  //   server: {
  //     process: '/api/auth/filepond-upload',
  //     acceptedFileTypes: ['image/*'],
  //     labelFileTypeNotAllowed: 'Please select a valid Image type PNG/JPG.',
  //     instantUpload:true,


  //     headers: {
  //       'X-CSRF-TOKEN': csrfToken,

  //     },

  //   },

  // })
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
                    name="images[]"
                    class-name="my-pond"
                    label-idle="Drop files here..."
                    allow-multiple="true"
                    max-parallel-uploads="5"
                    data-allow-reorder="true"
                    data-max-file-size="6MB"
                    data-max-files="5"
                    credits="false"
                    accepted-file-types="image/jpeg, image/png"
                    :files="myFiles"
                    :server="{
                      process: {
                        url: '/api/auth/filepond-upload',
                        method: 'POST',
                        headers: {
                          'X-CSRF-TOKEN': csrfToken

                        },
                        onload:(response) =>
                        {
                          //console.log('hello')
                          // console.log(response);
                          // Add the temporary file ID to myFiles array
                          //myFiles.push(response);
                        },
                        onerror: (response) => {
                          console.error(response);
                        }
                      },
                      revert: {
                        url: '',
                        method: 'DELETE',
                        // headers: {
                        //   'Authorization': 'Bearer ' + token
                        // }
                      }
                    }"
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
