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

setOptions({
  // server: 'http://127.0.0.1:8000',
  // headers: {
  //   'X-CSRF-TOKEN': csrfToken,

  // },

  //   server: {
  //     url: 'http://127.0.0.1:8000',

  //     headers: {
  //       'X-CSRF-TOKEN': csrfToken,

  //     },

//   },
})

const handleFilePondInit = () => {
  
  console.log('FilePond has initialized')


  
  //const pond = this.$refs.pond.filepond

  //Add a hook to send the CSRF token with the request
  // setOptions({
  //   server: {
  //     process: '/api/auth/filepond-upload',

  //     headers: {
  //       'X-CSRF-TOKEN': csrfToken,

  //     },

  //   },

  // })
}
</script>

<template>
  <FilePond
    ref="pond"
    name="test"
    label-idle="Drop files or clicks here..."
    allow-multiple="true"
    accepted-file-types="image/jpeg, image/png, image/gif"
    :server="{
      process: '/api/auth/filepond-upload',
      revert:'/api/auth/filepond-delete',
      headers: {
        'X-CSRF-TOKEN': csrfToken
      }
      
    }"
    :files="myFiles"
    class="mt-1"
    @init="handleFilePondInit"
  />
</template>
