<script setup>
import {
  integerValidator,
  requiredValidator,
} from '@validators'

import { ref } from 'vue'
import moment from 'moment'
import { useRoomListStore } from '@/views/apps/room/useRoomListStore'

const props = defineProps({
  userData: {
    type: Object,
    required: true,
  },
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  roomId:{
    type: Integer,
    required:true,
  },
})


const emit = defineEmits([
  'update:modelValue',
  'updateRoom',
  'update:isDialogVisible',
])


const roomListStore = useRoomListStore()
const isFormValid = ref(false)
const refForm = ref()

const roomId =ref(props.roomId)

console.log(roomId)



const tags = ref([])

const userData = ref(structuredClone(toRaw(props.userData)))

const selectedTags = ref(userData.value.room.tags.map(tag => tag.id))
const isUseAsBillingAddress = ref(false)



watch(props, () => {
  userData.value = structuredClone(toRaw(props.userData))
})



const onFormSubmit = () => {

  // refForm.value?.validate().then(({ valid }) => {
  //   if (valid) {

  const branchId = parseInt(selectedBranchId.value)
        
  const roomData = {
    id:userData.value.room.id,

    // branch_id: parseInt(branchText.value),
    branch_id: branchId,

    // room_number: userData.value.room.room_number,
    room_floor_number:userData.value.room.room_floor_number,
    capacity: userData.value.room.capacity,
    room_status: userData.value.room.room_status,
    prices: [
      {
        start_date: formateStartDate.value,
        end_date: formateEndDate.value,
        price_for_one_person_booking: userData.value.room.prices.price_for_one_person_booking,
        price_for_two_person_booking: userData.value.room.prices.price_for_two_person_booking,
        price_for_three_person_booking: userData.value.room.prices.price_for_three_person_booking,
        discount_on_full_allocation: userData.value.room.prices.discount_on_full_allocation,
      },
    ],

    // tags: userData.value.room.tags.filter(tag => tag !== ''),
    tags: selectedTags.value.filter(tag => tag !== ''),


    // tags: userData.value.room.tags.map(tag => tag.id), // Extract the tag IDs

  }

  emit('update:isDialogVisible', false)

  emit('update:modelValue', false)
  emit('updateRoom', roomData)

  //  }
  // })
}

const onFormReset = () => {
  userData.value = structuredClone(toRaw(props.userData))
  emit('update:isDialogVisible', false)
}

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

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


//use the ref for momemnt to formate the date
//reason for ref becuase if the user changes the date ,updated date will be send to backend
//while using computed does not send the update value of date to db
const formateStartDate =ref(moment(userData.value.room.prices.start_date).format('DD-MM-YYYY'))
const formateEndDate =ref(moment(userData.value.room.prices.end_date).format('DD-MM-YYYY'))


//used this function to get the brnach name from the hardcoded list
//as the branch id is coming from db so we are getting the branch name using that id here in below function
const getBranchText = branchId => {
  // console.log('Branch ID:', branchId)

  const branch = branchList.find(branch => branch.value == branchId)

  //console.log('Branch:', branch)

  return branch ? branch.text : ''
}

//const branchText = ref(getBranchText(userData.value.room.branch_id))
//get the branch text for the already select branch text and
//value when the form submit which will be an integer
const branchText = ref({ value: userData.value.room.branch_id, text: getBranchText(userData.value.room.branch_id) })

const onBranchSelect = branchId => {
  //alert(branchId)
  selectedBranchId.value = branchId
  selectedBranchText.value = getBranchText(branchId)
}

const selectedBranchId = ref(userData.value.room.branch_id)
const selectedBranchText = ref(getBranchText(userData.value.room.branch_id))


//get the status text from the list
//as we are getting the id for status from the backend/db 
//so used this function to get the text from id integer
const getStatusText = room_status => {

  const status = roomStatusList.find(status => status.value == room_status)
  
  return status ? status.text : ''
}

const statusText = ref(getStatusText(userData.value.room.room_status))

// watch(selectedBranchId, newVal => {
//   branchText.value = {
//     value: newVal,
//     text: getBranchText(newVal),
//   }
// })

// Fetch tags when the component is mounted
onMounted(() => {
  roomListStore.fetchTags()
    .then(response => {
      // Tags are fetched successfully
      // tags.value = response.map(tag => ({
      //   text: tag.name,  // assuming the tag object has a "name" property for the text
      //   value: tag.id,   // assuming the tag object has an "id" property for the value
      // }))

      tags.value =response.data

      //console.log(response)
    })
    .catch(error => {
      // Error occurred while fetching tags
      console.error(error)
    })
})
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 700"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-sm-14 pa-5">
      <VCardItem class="text-center">
        <VCardTitle class="text-h5 mb-3">
          Edit Room Information  Branch Text:
        </VCardTitle>
        <p class="mb-0">
          Updating room details will receive a privacy audit.
        </p>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm
          ref="refForm"
          v-model="isFormValid"
          class="mt-6"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <!-- 👉 Branch -->
            <VCol
              cols="12"
              md="6"
            >
              <VSelect
                v-model="selectedBranchId"
                label="Choose Branch"
                :items="branchList"
                :rules="[requiredValidator]"
                item-title="text"
                item-value="value"
                density="compact"
                class="me-3"
                :on-change="onBranchSelect(selectedBranchId)"
              />
            </VCol>

            <!-- 👉 Room Capacity -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.capacity"
                :rules="[requiredValidator]"
                label="Room Capacity"
              />
            </VCol>

            <!-- 👉 Room Number -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.room_number"
                :rules="[requiredValidator]"
                label="Room Number"
                disabled
              />
            </VCol>

            <!-- 👉 Status -->
            <VCol
              cols="12"
              md="6"
            >
              <VSelect
                v-model="userData.room.room_status"
                label="Room Status"
                :items="roomStatusList"
                :rules="[requiredValidator]"
                item-title="text"
                item-value="value"
                density="compact"
                class="me-3"
              />
            </VCol>

            <!-- 👉 Floor Number -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.room_floor_number"
                :rules="[requiredValidator,integerValidator]"
                label="Floor Number"
              />
            </VCol>

            <!-- 👉 Price Start Date -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="formateStartDate"
                :rules="[requiredValidator]"
                label="Price Start Date"
              />
            </VCol>

            <!-- 👉 Price End Date -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="formateEndDate"
                :rules="[requiredValidator]"
                label="Price End Date"
              />
            </VCol>


            <!-- 👉 Price For One Person -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.prices.price_for_one_person_booking"
                :rules="[requiredValidator,integerValidator]"
                label="Price For One Person"
              />
            </VCol>

            <!-- 👉 Price For Two Persons -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.prices.price_for_two_person_booking"
                :rules="[requiredValidator,integerValidator]"
                label="Price For Two Persons"
              />
            </VCol>


            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.prices.price_for_three_person_booking"
                :rules="[requiredValidator,integerValidator]"
                label="Price For Three Persons"
              />
            </VCol>
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.room.prices.discount_on_full_allocation"
                :rules="[requiredValidator,integerValidator]"
                label="Discount On Full Booking"
              />
            </VCol>

            <VCol cols="12">
              <VSelect
                v-model="selectedTags"
                label="Tags"
                :items="tags"
                item-title="name"
                item-value="id"
                multiple
                persistent-hint
              />
            </VCol>

       

          

            <!-- 👉 Submit and Cancel -->
            <VCol
              cols="12"
              class="d-flex flex-wrap justify-center gap-4"
            >
              <VBtn type="submit">
                Submit
              </VBtn>

              <VBtn
                color="secondary"
                variant="tonal"
                @click="onFormReset"
              >
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
