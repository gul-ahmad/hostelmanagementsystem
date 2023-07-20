<script setup>
import { useRoomListStore } from '@/views/apps/room/useRoomListStore'
import UserBioPanel from '@/views/apps/room/view/UserBioPanel.vue'
import UserTabBillingsPlans from '@/views/apps/room/view/UserTabBillingsPlans.vue'
import UserTabConnections from '@/views/apps/room/view/UserTabConnections.vue'
import UserTabNotifications from '@/views/apps/room/view/UserTabNotifications.vue'
import UserTabOverview from '@/views/apps/room/view/UserTabOverview.vue'
import UserTabSecurity from '@/views/apps/room/view/UserTabSecurity.vue'

const roomListStore = useRoomListStore()
const route = useRoute()
const userData = ref()
const userTab = ref(null)

const tabs = [
  {
    icon: 'tabler-user-check',
    title: 'Images',
  },
  {
    icon: 'tabler-lock',
    title: 'Security',
  },
  {
    icon: 'tabler-currency-dollar',
    title: 'Billing & Plan',
  },
  {
    icon: 'tabler-bell',
    title: 'Notifications',
  },
  {
    icon: 'tabler-link',
    title: 'Connections',
  },
]

// Fetch user data
const fetchUserData = () => {
  console.log('Im being called')
  roomListStore.fetchUser(Number(route.params.id)).then(response => {
    userData.value = response.data
  })
}


// Fetch user data on component mount
onMounted(fetchUserData)

const roomImage = roomImage => {

  //alert('asdfasdfasdfasdfasdf')
  //console.log('I am in add Image area>>>')

  console.log('I am in add Image area>>>')

  console.log(roomImage)
  console.log(roomImage.roomId)

  //console.log(roomImages)
  roomListStore.updateRoomImages(roomImage)
    .then(success => {
      console.log(success.text)
      successMessage.value = success.text
    
      setTimeout(()=>{
        successMessage.value =''

      },5000)
    }).catch(error=>{

      console.log(error)
    })

  // refetch User
  fetchUserData()
}


//Deleting room images
const roomImageDelete = roomImageDelete => {

  //alert('asdfasdfasdfasdfasdf')
  //console.log('I am in add Image area>>>')

  console.log('I am in room image delete area>>')

  console.log(roomImageDelete)
  console.log(roomImageDelete.roomId)

  //console.log(roomImages)
  roomListStore.deleteRoomImage(roomImageDelete)
    .then(success => {
      console.log(success.text)
      successMessage.value = success.text
    
      setTimeout(()=>{
        successMessage.value ='Images Deleted Successfully'

      },5000)
    }).catch(error=>{

      console.log(error)
    })

  // refetch User
  fetchUserData()
}



//Selecting The Featured Image
const featuredImageSelection = payLoad => {

  //alert('asdfasdfasdfasdfasdf')
  //console.log('I am in add Image area>>>')

  console.log('I am in feature image selection area for room>>')

  console.log(payLoad)
  console.log(payLoad.roomId)

  //console.log(roomImages)
  roomListStore.updateFeaturedImage(payLoad)
    .then(success => {
      console.log(success.text)
      successMessage.value = success.text
    
      setTimeout(()=>{
        successMessage.value ='Featured Image Selected Successfully'

      },5000)
    }).catch(error=>{

      console.log(error)
    })

  // refetch User
  fetchUserData()
}
</script>

<template>
  <VRow v-if="userData">
    <VCol
      cols="12"
      md="5"
      lg="4"
    >
      <UserBioPanel
        :user-data="userData"
        @update-room-data="fetchUserData"
      />
    </VCol>

    <VCol
      cols="12"
      md="7"
      lg="8"
    >
      <VTabs
        v-model="userTab"
        class="v-tabs-pill"
      >
        <VTab
          v-for="tab in tabs"
          :key="tab.icon"
        >
          <VIcon
            :size="18"
            :icon="tab.icon"
            class="me-1"
          />
          <span>{{ tab.title }}</span>
        </VTab>
      </VTabs>

      <VWindow
        v-model="userTab"
        class="mt-6 disable-tab-transition"
        :touch="false"
      >
        <VWindowItem>
          <UserTabOverview
            :user-data="userData"
            :room-id="userData.room.id"
            @room-image="roomImage"
            @room-image-delete="roomImageDelete"
            @featured-image-selection="featuredImageSelection"
          />
        </VWindowItem>

        <VWindowItem>
          <UserTabSecurity />
        </VWindowItem>

        <VWindowItem>
          <UserTabBillingsPlans />
        </VWindowItem>

        <VWindowItem>
          <UserTabNotifications />
        </VWindowItem>

        <VWindowItem>
          <UserTabConnections />
        </VWindowItem>
      </VWindow>
    </VCol>
  </VRow>
</template>
