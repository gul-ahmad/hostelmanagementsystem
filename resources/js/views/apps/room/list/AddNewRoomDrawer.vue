<script setup>
import {
  integerValidator,
  requiredValidator,
} from '@validators'
import { ref } from 'vue'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

// Import FilePond
import vueFilePond, { setOptions } from 'vue-filepond'

// Import plugins
import FilePondPluginImagePreview from 'filepond-plugin-image-preview'

import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type'

import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size'


// Import styles
import 'filepond/dist/filepond.min.css'
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css'



const props = defineProps({
  isDrawerOpen: {
    type: Boolean,

    required: true,
  },
  selectedUser :{
    type:Object,
    default:null,
  },
  tags:{

    type: Array,
    required:true,

  },

  // isEditMode:{
  //   type:Boolean,

  //   //required:true,
  // },
 

})


const emit = defineEmits([
  'update:isDrawerOpen',
  'roomData',
])


// Create FilePond component
const FilePond = vueFilePond(FilePondPluginFileValidateType, FilePondPluginImagePreview,FilePondPluginFileValidateSize)


const isFormValid = ref(false)
const refForm = ref()
const roomBranch = ref('')

const branchList = [
  {
    text: 'Islamabad',
    value: '1',
  },
  {
    text: 'Rawalpindi',
    value: '2',
  },
]

const roomNumber = ref('')
const roomAvailability =ref('')
const roomFloorNumber = ref('')
const roomStatus = ref('')

const roomStatusList = [
  {
    text: 'Available For Booking',
    value: '1',
  },
  {
    text: 'Not Available',
    value: '2',
  },
]

const tags = ref('')

//console.log(props.tags)

//const roomTagsList = ref(props.tags)
const roomTagsList = ref([props.tags])


//console.log(roomTagsList.value)

// const  roomTagsList = [
//   {
//     text: 'Has Ac',
//     value: '1',
//   },
//   {
//     text: 'Has Private BathRoom',
//     value: '2',
//   },
//   {
//     text: 'Has Tv',
//     value: '3',
//   },
// ]

//const roomHidden = ref('')
const roomCapacity = ref('')

//Prices

const prices = ref([])

const myFiles = ref([])
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content')





const roomPriceStartDate= ref('')
const roomPriceEndDate= ref('')
const roomPriceForOnePersonBooking= ref('')
const roomPriceForTwoPersonBooking= ref('')
const roomPriceForThreePersonBooking= ref('')
const roomDiscountOnFullAllocation= ref('')


//prices.value['roomPriceStartDate'] = ref('')





// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {

      

      const roomData = {
        branch_id:                 roomBranch.value,
        room_number:               roomNumber.value,
        capacity:                  roomCapacity.value,
        room_floor_number:         roomFloorNumber.value,
        room_status:               roomStatus.value,
        prices: [{
          start_date:                     roomPriceStartDate.value,
          end_date:                       roomPriceEndDate.value,
          price_for_one_person_booking:   roomPriceForOnePersonBooking.value,
          price_for_two_person_booking:   roomPriceForTwoPersonBooking.value,
          price_for_three_person_booking: roomPriceForThreePersonBooking.value,
          discount_on_full_allocation:    roomDiscountOnFullAllocation.value,
        }],
        tags:tags.value.filter(tag => tag !==''),                    
      
      }

   
      // Adding a new room
      emit('roomData', roomData)
      
      emit('update:isDrawerOpen', false)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}

watch(roomNumber, async (newRoomNumber, oldRoomNumber) => {
  if (newRoomNumber) {
    roomAvailability.value = 'Checking Room Number Availability...'
    try {
      const res = await fetch(`/api/rooms/check-availability/${newRoomNumber}`)
      const data = await res.json()

      console.log(data)

      //roomAvailability.value = data.roomAvailability
      roomAvailability.value = data.available ? 'Room Number Available' : 'Room Number Not Available'

    } catch (error) {
    //  console.log('dfadfadsf')
      roomAvailability.value = 'Error! Could not reach the API. ' + error
    }
  }
})

///console.log(props.selectedUser)
watch(() => props.selectedUser, selectedUser => {
  //console.log(props.selectedUser)
  if (selectedUser) {
    branch.value = selectedUser.branch

    // email.value = selectedUser.email
    // password.value = selectedUser.password
    // confirm_password.value = selectedUser.confirm_password
  } else {
    branch.value = ''
    email.value = ''
    password.value = ''
    confirm_password.value = ''
  }
})

const handleFilePondInit = () => {
  
  console.log('FilePond has initialized')


  
  //const pond = this.$refs.pond.filepond

  //Add a hook to send the CSRF token with the request
  setOptions({
    server: {
      process: '/api/auth/filepond-upload',

      headers: {
        'X-CSRF-TOKEN': csrfToken,

      },

    },

  })
}
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <div class="d-flex align-center pa-6 pb-1">
      <h6 class="text-h6">
        <!-- Add Room -->
        Add New Room
      </h6>

      <VSpacer />

      <!-- 👉 Close btn -->
      <VBtn
        variant="tonal"
        color="default"
        icon
        size="32"
        class="rounded"
        @click="closeNavigationDrawer"
      >
        <VIcon
          size="18"
          icon="tabler-x"
        />
      </VBTn>
    </div>

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- 👉  Branch -->
              <VCol cols="12">
                <VSelect
                  v-model="roomBranch"
                  label="Choose Branch"
                  :items="branchList"
                  :rules="[requiredValidator]"
                  item-title="text"
                  item-value="value"
                  density="compact"
                  class="me-3"
                />
                <input
                  type="hidden"
                  name="_method"
                  :value="POST"
                >
              </VCol>

              <!-- 👉 Room Number -->
              <VCol cols="12">
                <VTextField
                  v-model="roomNumber"
                  :rules="[requiredValidator,integerValidator]"
                  label="Room Number"
                />

                <p>{{ roomAvailability }}</p>
              </VCol>

              <!-- 👉 Floor Number -->
              <VCol cols="12">
                <VTextField
                  v-model="roomFloorNumber"
                  :rules="[requiredValidator]"
                  label="Floor Number"
                />
              </VCol>

              <!-- 👉 Room Status -->
              <VCol cols="12">
                <VSelect
                  v-model="roomStatus"
                  label="Room Status"
                  :items="roomStatusList"
                  :rules="[requiredValidator]"
                  item-title="text"
                  item-value="value"
                  density="compact"
                  class="me-3"
                />
              </VCol>

             

              <!-- 👉 Room Capacity -->
              <VCol cols="12">
                <VTextField
                  v-model="roomCapacity"
                  :rules="[requiredValidator,integerValidator]"
                  label="Room Capacity"
                />
              </VCol>

              <!-- 👉 Price Start Date -->
              <VCol cols="12">
                <VTextField
                  v-model="roomPriceStartDate"
                  :rules="[requiredValidator]"
                  label="Price Start Date"
                />
              </VCol>

              <!-- 👉 Price End Date -->
              <VCol cols="12">
                <VTextField
                  v-model="roomPriceEndDate"
                  :rules="[requiredValidator]"
                  label="Price End Date"
                />
              </VCol>

              <!-- 👉 Price for One Persone booking -->
              <VCol cols="12">
                <VTextField
                  v-model="roomPriceForOnePersonBooking"
                  :rules="[requiredValidator,integerValidator]"
                  label="Price For One Person"
                />
              </VCol>

              <!-- 👉 Price for Two Persone booking -->
              <VCol cols="12">
                <VTextField
                  v-model="roomPriceForTwoPersonBooking"
                  :rules="[requiredValidator,integerValidator]"
                  label="Price For Two Person"
                />
              </VCol>
              <!-- 👉 Price for Three Persone booking -->
              <VCol cols="12">
                <VTextField
                  v-model="roomPriceForThreePersonBooking"
                  :rules="[requiredValidator,integerValidator]"
                  label="Price For Three Person"
                />
              </VCol>

              <!-- 👉 Discount On Full Booking -->
              <VCol cols="12">
                <VTextField
                  v-model="roomDiscountOnFullAllocation"
                  :rules="[requiredValidator,integerValidator]"
                  label="Discount On Full Booking"
                />
              </VCol>
              <VCol cols="12">
                <VSelect
                  v-model="tags"
                  label="Tags"
                  :items="props.tags"
                  :rules="[requiredValidator]"
                  item-title="name"
                  item-value="id"
                  multiple
                  persistent-hint
                />
              </VCol>
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
  </VNavigationDrawer>
</template>
