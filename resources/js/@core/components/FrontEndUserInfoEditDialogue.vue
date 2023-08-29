<script setup>
import {
  integerValidator,
  requiredValidator,
} from '@validators'

import { ref } from 'vue'
import moment from 'moment'
import { useRoomListStore } from '@/views/apps/room/useRoomListStore'




const emit = defineEmits([
  'update:modelValue',
  'updateRoom',
  'update:isDialogVisible',
])


const roomListStore = useRoomListStore()
const isFormValid = ref(false)
const refForm = ref()





const tags = ref([])

//const userData = ref(structuredClone(toRaw(props.userData)))

const isUseAsBillingAddress = ref(false)







const onFormSubmit = () => {

  // refForm.value?.validate().then(({ valid }) => {
  //   if (valid) {

  const branchId = parseInt(selectedBranchId.value)
        
 

  emit('update:isDialogVisible', false)

  emit('update:modelValue', false)
  emit('updateRoom', roomData)

  //  }
  // })
}

const onFormReset = () => {
  // userData.value = structuredClone(toRaw(props.userData))
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



//used this function to get the brnach name from the hardcoded list
//as the branch id is coming from db so we are getting the branch name using that id here in below function


//const branchText = ref(getBranchText(userData.value.room.branch_id))
//get the branch text for the already select branch text and
//value when the form submit which will be an integer






//get the status text from the list
//as we are getting the id for status from the backend/db 
//so used this function to get the text from id integer




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
      </VCardText>
    </VCard>
  </VDialog>
</template>
