<script setup>
import {
  avatarText,
  kFormatter,
} from '@core/utils/formatters'
import { watchEffect } from 'vue'


import moment from 'moment'
import { useRoomListStore } from '@/views/apps/room/useRoomListStore'


const props = defineProps({
  userData: {
    type: Object,
    required: true,
  },
})

const emit = defineEmits([
  'updateRoomData',
])

const standardPlan = {
  plan: 'Standard',
  price: 99,
  benefits: [
    '10 Users',
    'Up to 10GB storage',
    'Basic Support',
  ],
}

const roomListStore = useRoomListStore()

const isUserInfoEditDialogVisible = ref(false)
const isUpgradePlanDialogVisible = ref(false)

const resolveUserStatusVariant = stat => {
  if (stat == '1')
    return 'primary'
  if (stat == '2')
    return 'warning'
  if (stat === 'inactive')
    return 'secondary'
  
  return 'secondry'
}

const resolveUserRoleVariant = role => {
  if (role === 'subscriber')
    return {
      color: 'warning',
      icon: 'tabler-user',
    }
  if (role === 'author')
    return {
      color: 'success',
      icon: 'tabler-circle-check',
    }
  if (role === 'maintainer')
    return {
      color: 'primary',
      icon: 'tabler-chart-pie-2',
    }
  if (role === 'editor')
    return {
      color: 'info',
      icon: 'tabler-pencil',
    }
  if (role === 'admin')
    return {
      color: 'secondary',
      icon: 'tabler-server-2',
    }
  
  return {
    color: 'primary',
    icon: 'tabler-user',
  }
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

//used this function to get the brnach name from the hardcoded list
//as the branch id is coming from db so we are getting the branch name using that id here in below function
const getBranchText = branchId => {

  const branch = branchList.find(branch => branch.value == branchId)

  return branch ? branch.text : ''
}

const branchText = ref(getBranchText(props.userData.room.branch_id))






//get the status text from the list
//as we are getting the id for status from the backend/db 
//so used this function to get the text from id integer
const getStatusText = room_status => {

  const status = roomStatusList.find(status => status.value == room_status)
  
  return status ? status.text : ''
}

const statusText = ref(getStatusText(props.userData.room.room_status))

watchEffect(() => {
  branchText.value = getBranchText(props.userData.room.branch_id)

  statusText.value = getStatusText(props.userData.room.room_status)

})


const formateStartDate = computed(() => {
  return moment(props.userData.room.prices.start_date).format('DD-MM-YYYY')
})

const formateEndDate = computed(() => {
  return moment(props.userData.room.prices.end_date).format('DD-MM-YYYY')

})

const updateRoom = roomData => {
  console.log('I am in updateRoom area>>>')

  console.log(roomData)
  roomListStore.updateRoom(roomData)
    .then(success => {
      console.log(success.text)

      //on success emitted this event to display the latest record of room 
      //which is updated
      emit('updateRoomData')

      successMessage.value = success.text
    
      setTimeout(()=>{
        successMessage.value =''

      },5000)
    }).catch(error=>{

      console.log(error)
    })

  // refetch User
  // fetchRooms()
}

// watchEffect(() => {
//   console.log('Props data changed:', props.userData)

// })
</script>

<template>
  <VRow>
    <!-- SECTION User Details -->
    <VCol cols="12">
      <VCard v-if="props.userData">
        <!-- 👉 Details -->
        <VCardText>
          <p class="text-sm text-uppercase text-disabled">
            Prices
          </p>

          <!-- 👉 Room Price Details  -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Price Start Date:
                  <span class="text-body-2">
                    {{ formateStartDate }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Price End Date:
                  <span class="text-body-2">
                    {{ formateEndDate }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Price For One Person:
                  <span class="text-body-2">{{ props.userData.room.prices.price_for_one_person_booking }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Price For Two Person:
                  <span class="text-body-2">{{ props.userData.room.prices.price_for_two_person_booking }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Price For Three Person:
                  <span class="text-body-2">{{ props.userData.room.prices.price_for_three_person_booking }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Discount:
                  <span class="text-body-2">{{ props.userData.room.prices.discount_on_full_allocation }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>
        <!-- 👉 Details -->
        <VCardText>
          <p class="text-sm text-uppercase text-disabled">
            Details
          </p>

          <!-- 👉 Room Details  -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Branch:
                  <span class="text-body-2">
                    {{ branchText }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Room Number:
                  <span class="text-body-2">
                    {{ props.userData.room.room_number }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Floor Number:
                  <span class="text-body-2">{{ props.userData.room.room_floor_number }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Status:

                  <VChip
                    label
                    size="small"
                    :color="resolveUserStatusVariant(props.userData.room.room_status)"
                    class="text-capitalize"
                  >
                    {{ statusText }}
                  </VChip>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Visibility:
                  <span class="text-capitalize text-body-2">{{ props.userData.room.hidden }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Capacity:
                  <span class="text-body-2">
                    {{ props.userData.room.capacity }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>

        <!-- 👉 Details -->
        <VCardText>
          <p class="text-sm text-uppercase text-disabled">
            Tags Used
          </p>

          <!-- 👉 Room Tags   -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Tags:
                  <span 
                    v-for="(tag,index) in props.userData.room.tags"
                    :key="tag.id"
                    class="text-body-2" 
                  >
                    {{ tag.name }}
                    <span v-if="index !== props.userData.room.tags.length - 1">, </span>
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>

        <!-- 👉 Edit and Suspend button -->
        <VCardText class="d-flex justify-center">
          <VBtn
            variant="elevated"
            class="me-3"
            @click="isUserInfoEditDialogVisible = true"
          >
            Edit
          </VBtn>
          <VBtn
            variant="tonal"
            color="error"
          >
            Suspend
          </VBtn>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->

    <!-- SECTION Current Plan -->
    <VCol cols="12">
      <VCard>
        <VCardText class="d-flex">
          <!-- 👉 Standard Chip -->
          <VChip
            label
            color="primary"
            size="small"
          >
            Standard
          </VChip>

          <VSpacer />

          <!-- 👉 Current Price  -->
          <div class="d-flex align-center">
            <sup class="text-primary text-sm font-weight-regular">$</sup>
            <h3 class="text-h3 text-primary font-weight-semibold">
              99
            </h3>
            <sub class="mt-3"><h6 class="text-sm font-weight-regular">/ month</h6></sub>
          </div>
        </VCardText>

        <VCardText>
          <!-- 👉 Price Benefits -->
          <VList class="card-list">
            <VListItem
              v-for="benefit in standardPlan.benefits"
              :key="benefit"
            >
              <VIcon
                size="12"
                color="#A8AAAE"
                class="me-2"
                icon="tabler-circle"
              />
              <span>{{ benefit }}</span>
            </VListItem>
          </VList>

          <!-- 👉 Days -->
          <div class="my-6">
            <div class="d-flex font-weight-semibold mt-3 mb-2">
              <h6 class="text-base font-weight-semibold">
                Days
              </h6>
              <VSpacer />
              <h6 class="text-base font-weight-semibold">
                26 of 30 Days
              </h6>
            </div>

            <!-- 👉 Progress -->
            <VProgressLinear
              rounded
              rounded-bar
              :model-value="65"
              height="8"
              color="primary"
            />

            <p class="mt-2">
              4 days remaining
            </p>
          </div>

          <!-- 👉 Upgrade Plan -->
          <VBtn
            block
            @click="isUpgradePlanDialogVisible = true"
          >
            Upgrade Plan
          </VBtn>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>

  <!-- 👉 Edit user info dialog -->
  <UserInfoEditDialog
    v-model:isDialogVisible="isUserInfoEditDialogVisible"
    :user-data="props.userData"
    @update-room="updateRoom"
  />

  <!-- 👉 Upgrade plan dialog -->
  <UserUpgradePlanDialog v-model:isDialogVisible="isUpgradePlanDialogVisible" />
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.7rem;
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
