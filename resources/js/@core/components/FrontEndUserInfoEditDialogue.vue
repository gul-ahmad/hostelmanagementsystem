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
    type: Number,
    required:false,
    default:0,
  },
})


const emit = defineEmits([
  'update:modelValue',
  'updateUser',
  'update:isDialogVisible',
])


const roomListStore = useRoomListStore()
const isFormValid = ref(false)
const refForm = ref()

// const roomId =ref(props.roomId)

// console.log(roomId)



const tags = ref([])

console.log(props.userData)

const userData = ref(structuredClone(toRaw(props.userData)))

//const selectedTags = ref(userData.value.room.tags.map(tag => tag.id))
const isUseAsBillingAddress = ref(false)



watch(props, () => {
  userData.value = structuredClone(toRaw(props.userData))
})



const onFormSubmit = () => {

  // refForm.value?.validate().then(({ valid }) => {
  //   if (valid) {

  //const branchId = parseInt(selectedBranchId.value)
        
  const userDataUpdated = {
    id:userData.value.id,

    // branch_id: parseInt(branchText.value),
    // branch_id: branchId,

    // room_number: userData.value.room.room_number,
    name:userData.value.name,
    address:userData.value.address,
    cnic: userData.value.cnic,
    contact_no: userData.value.contact_no,

    //emergency_contact_number: userData.value.emergency_contact_number,
    gender: userData.value.gender,
    nationality: userData.value.nationality,

    //university: userData.value.university/college,
  }

  emit('update:isDialogVisible', false)

  emit('update:modelValue', false)
  emit('updateUser', userDataUpdated)

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
// const formateStartDate =ref(moment(userData.value.room.prices.start_date).format('DD-MM-YYYY'))
// const formateEndDate =ref(moment(userData.value.room.prices.end_date).format('DD-MM-YYYY'))
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
          Update Your Details
        </VCardTitle>
        <p class="mb-0">
          Updating Your Details will receive a privacy audit.
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
            <!-- 👉 Room Capacity -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.name"
                :rules="[requiredValidator]"
                label="Name"
              />
            </VCol>

            <!-- 👉 Room Number -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.address"
                :rules="[requiredValidator]"
                label="Address"
                disabled
              />
            </VCol>

        

            <!-- 👉 CNIC -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.cnic"
                :rules="[requiredValidator,integerValidator]"
                label="Cnic"
              />
            </VCol>

            <!-- 👉 contact_no -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.contact_no"
                :rules="[requiredValidator]"
                label="Contact No"
              />
            </VCol>

            <!-- 👉 nationality -->
            <VCol
              cols="12"
              md="6"
            >
              <VTextField
                v-model="userData.nationality"
                :rules="[requiredValidator]"
                label="Nationality"
              />
            </VCol>

            <!-- 👉 Submit and Cancel -->
            <VCol
              cols="12"
              class="d-flex flex-wrap justify-center gap-4"
            >
              <VBtn type="submit">
                Update
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
