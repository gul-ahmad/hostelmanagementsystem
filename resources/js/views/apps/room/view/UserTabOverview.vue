<!-- eslint-disable vue/no-parsing-error -->
<script setup>
import {
  requiredValidator,
} from '@validators'
import { ref , defineProps,defineEmits } from 'vue'

import UserInvoiceTable from './UserInvoiceTable.vue'
import { useRoomListStore } from '@/views/apps/room/useRoomListStore'


// Import FilePond
import vueFilePond, { setOptions } from 'vue-filepond'

// Import plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'

// Import styles
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'

//define props here

const props = defineProps({
  userData: {
    type: Object,
    required: true,
  },
  roomId:{
    type: String,
    required: true,
    
  },
})

const emit = defineEmits([
  'roomImage','roomImageDelete','featuredImageSelection',
])

// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview)

const roomListStore = useRoomListStore()


const selectedImages = ref([])

// Create a local copy of the userData prop
const localUserData = ref(props.userData)

const isFormValid = ref(false)
const refForm = ref()
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
const myFiles = ref([''])
const selectedFiles = ref([])

const selectedFeaturedImageId =ref(null)


const selectFeaturedImage =imageId =>{

  

  const payLoad ={

    roomId :props.roomId,
    featuredImageId:imageId,

  }

  // console.log(payLoad)

  emit('featuredImageSelection',payLoad)

  // imageId.value=''

}


const deleteSelectedImages = () => {
  if (selectedImages.value.length === 0) {
    return
  }
    

  const roomImageDelete = {
    imagesId: selectedImages.value,
    roomId:props.roomId,
  }

   
  //  Deleting a room image/images
  emit('roomImageDelete', roomImageDelete)

  selectedImages.value = []

  // Make an API call to delete the selected images from the server
  roomListStore.deleteRoomImages(props.roomId,selectedImages.value)
    .then(() => {
      // Images deleted successfully, update the image list in the localUserData
      const updatedImages = localUserData.value.room.images.filter(image => !selectedImages.value.includes(image.id))

      localUserData.value.room.images = updatedImages

      // Clear the selectedImages array
      selectedImages.value = []
    })
    .catch(error => {
      // Error occurred while deleting the images
      console.error(error)
    })
}


const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      
      const selectedFiles = myFiles.value.images

      console.log(selectedFiles)

      const roomImage = {
        files: selectedFiles,
        roomId:props.roomId,
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

const processFiles =()=> {
  
  roomListStore.fetchImagesUploadedByFilePond()
    .then(response => {
      // Tags are fetched successfully
      // tags.value = response.map(tag => ({
      //   text: tag.name,  // assuming the tag object has a "name" property for the text
      //   value: tag.id,   // assuming the tag object has an "id" property for the value
      // }))

      myFiles.value =response

      console.log(response)
      console.log(myFiles.value.images)
    })
    .catch(error => {
      // Error occurred while fetching tags
      console.error(error)
    })
}

setOptions({
  credits: false,
  server: {
    process: '/api/auth/filepond-upload',
    revert: '/api/auth/filepond-delete',
    acceptedFileTypes: ['image/*'],
    labelFileTypeNotAllowed: 'Please select a valid Image type PNG/JPG.',
    instantUpload:true,




    headers: {
      'X-CSRF-TOKEN': csrfToken,

    },

  },

})

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
        <VCard v-if="props.userData.room.images">
          <VCardText class="text-center pt-15">
            <!-- 👉 Avatar -->
            <VContainer fluid>
              <VRow>
                <VCol
                  v-for="image in props.userData.room.images"
                  :key="image.id"
                  cols="12"
                  md="6"
                  lg="4"
                >
                  <VCard class="mb-4">
                    <VTooltip bottom>
                      <template #activator="{ on, attrs }">
                        <div class="image-container">
                          <VImg
                            v-if="image.path"
                            :src="'/storage/roomsfinal/tmp/' + image.path"
                            :alt="image.path"
                            :class="{'featured-image': selectedFeaturedImageId === image.id}"
                            v-bind="attrs"
                            v-on="on"
                          />
                          <div
                            class="image-overlay"
                            @click="selectFeaturedImage(image.id)"
                          >
                            <span class="overlay-text">Make it featured image</span>
                          </div>
                        </div>
                      </template>
                    </VTooltip>



                    <!-- Checkbox -->
                    <VCheckbox
                      v-model="selectedImages"
                      :value="image.id"
                    />
                  </VCard>
                </VCol>
              </VRow>
            </VContainer>
          </VCardText>

          <VDivider />
        </VCard>
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
                <!-- 👉  Filepond Images upload -->
                <VCol cols="12">
                  <FilePond
                    type="file"
                    name="images[]"
                    class-name="my-pond"
                    label-idle="Drop files here..."
                    allow-multiple="true"
                    max-parallel-uploads="5"
                    data-allow-reorder="true"
                    max-file-size="5MB"
                    data-max-files="5"
                    credits="false"
                    accepted-file-types="image/jpeg, image/png"
                    label-file-type-not-allowed="Only Png and Jpg allowed."
                    label-max-total-file-size-exceeded="Maximum total size exceeded,5MB allowed."
                    :rules="[requiredValidator]"
                    :my-files="myFiles"
                    required
                    @processfiles="processFiles"   
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
                  <VBtn
                    v-if="selectedImages.length > 0"
                    icon
                    small
                    fab
                    right
                    class="ma-2"
                    color="error"
                    @click="deleteSelectedImages"
                  >
                    <VIcon>mdi-delete</VIcon>
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

<style scoped>
.image-container {
  position: relative;
  cursor: pointer;
}

.featured-image {
  border: 2px solid blue;
}

.image-overlay {
  position: absolute;
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: rgba(0, 0, 0, 50%);
  block-size: 100%;
  inline-size: 100%;
  inset-block-start: 0;
  inset-inline-start: 0;
  opacity: 0;
  transition: opacity 0.3s ease-in-out;
}

.overlay-text {
  color: white;
  font-size: 16px;
  font-weight: bold;
}

.image-container:hover .image-overlay {
  opacity: 1;
}
</style>

