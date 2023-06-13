<script setup>
import AddNewRoomDrawer from '@/views/apps/room/list/AddnewRoomDrawer.vue'
import RoomDeleteDialogue from '@/views/apps/room/list/RoomDeleteDialogue.vue'


import { useRoomListStore } from '@/views/apps/room/useRoomListStore'
import { avatarText } from '@core/utils/formatters'
import { onMounted, ref } from 'vue'

const roomListStore = useRoomListStore()
const searchQuery = ref('')
const selectedRole = ref()
const selectedPlan = ref()
const selectedStatus = ref()
const rowPerPage = ref(5)
const currentPage = ref(1)
const totalPage = ref(1)
const totalRooms = ref(0)
const rooms = ref([])

const tags = ref([])






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

// 👉 Fetching rooms
const fetchRooms = () => {
 
  roomListStore.fetchRooms({
    q: searchQuery.value,

    status: selectedStatus.value,
    plan: selectedPlan.value,
    role: selectedRole.value,
    perPage: rowPerPage.value,
    currentPage: currentPage.value,
  }).then(response => {
 
    rooms.value = response.data.rooms

    console.log(rooms.value)
    console.log(localStorage.getItem('accessToken'))

    // console.log(currentPage.value)

    // console.log(response.data.totalPage)

    // console.log(response.data.totalRooms)
    // console.log(currentPage.value) 
    totalPage.value = response.data.totalPage
    totalRooms.value = response.data.totalRooms
  }).catch(error => {
    console.error(error)
  })
}

watchEffect(fetchRooms)



// 👉 search filters
const roles = [
  {
    title: 'Admin',
    value: 'admin',
  },
  {
    title: 'Author',
    value: 'author',
  },
  {
    title: 'Editor',
    value: 'editor',
  },
  {
    title: 'Maintainer',
    value: 'maintainer',
  },
  {
    title: 'Subscriber',
    value: 'subscriber',
  },
]

const plans = [
  {
    title: 'Basic',
    value: 'basic',
  },
  {
    title: 'Company',
    value: 'company',
  },
  {
    title: 'Enterprise',
    value: 'enterprise',
  },
  {
    title: 'Team',
    value: 'team',
  },
]

const status = [
  {
    title: 'Pending',
    value: 'pending',
  },
  {
    title: 'Active',
    value: 'active',
  },
  {
    title: 'Inactive',
    value: 'inactive',
  },
]

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
      icon: 'tabler-device-laptop',
    }
  
  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}

const resolveUserStatusVariant = stat => {
  if (stat === 'pending')
    return 'warning'
  if (stat === 'active')
    return 'success'
  if (stat === 'inactive')
    return 'secondary'
  
  return 'primary'
}

const isAddNewRoomDrawerVisible = ref(false)
const isRoomDeleteDialogueVisible =ref(false)

//const isEditUserDrawerVisible =ref(false)
const selectedUser =ref()
const selectedRoomId = ref(0)

//const isEditMode =ref(false)

const successMessage = ref('')

//const userId =ref()

function deleteRoom(roomId) {

  //console.log(roomId)
  isRoomDeleteDialogueVisible.value = true
  selectedRoomId.value = roomId
  console.log(isRoomDeleteDialogueVisible.value )

}

// function updateUser(user) {
//   //console.log(user)
//   selectedUser.value = user

//   // console.log(selectedUser.value)
//   isEditMode.value = true
// }

// 👉 watching current page
watchEffect(() => {

  if (currentPage.value > totalPage.value)
    currentPage.value = totalPage.value
})

// 👉 Computing pagination data
const paginationData = computed(() => {
  const firstIndex = rooms.value.length ? (currentPage.value - 1) * rowPerPage.value + 1 : 0
  const lastIndex = rooms.value.length + (currentPage.value - 1) * rowPerPage.value
  
  return `Showing ${ firstIndex } to ${ lastIndex } of ${ totalRooms.value } entries`
})

const addNewRoom = roomData => {
  //console.log('I am in addRoom area>>>')
  //console.log(roomData)
  roomListStore.addRoom(roomData)
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
  fetchRooms()
}

// const editUser = userData => {

//   // console.log('I am in userEdit area>>>')
//   roomListStore.editUser(userData)
//     .then(success => {
//       successMessage.value = success.text
//       setTimeout(()=>{
//         successMessage.value =''

//       },5000)
//     }).catch(error=>{

//       console.log(error)
//     })

//   // refetch User
//   fetchRooms()
// }

const deleteRoomRecord = roomData => {

  //alert('I ma here in delete Room>>>>')
  

  roomListStore.deleteRoomRecrod(roomData)
    .then(success => {
      successMessage.value = success.text
      setTimeout(()=>{
        successMessage.value =''

      },5000)
    }).catch(error=>{

      console.log(error)
    })

  fetchRooms()

  
}


// 👉 List
const userListMeta = [
  {
    icon: 'tabler-user',
    color: 'primary',
    title: 'Session',
    stats: '21,459',
    percentage: +29,
    subtitle: 'Total Users',
  },
  {
    icon: 'tabler-user-plus',
    color: 'error',
    title: 'Paid Users',
    stats: '4,567',
    percentage: +18,
    subtitle: 'Last week analytics',
  },
  {
    icon: 'tabler-user-check',
    color: 'success',
    title: 'Active Users',
    stats: '19,860',
    percentage: -14,
    subtitle: 'Last week analytics',
  },
  {
    icon: 'tabler-user-exclamation',
    color: 'warning',
    title: 'Pending Users',
    stats: '237',
    percentage: +42,
    subtitle: 'Last week analytics',
  },
]
</script>

<template>
  <section>
    <VRow>
      <VCol cols="12">
        <VCard title="Search Filter">
          <!-- 👉 Filters -->
          <VAlert
            v-if="successMessage"
            transition="fade"
            :value="true"
            type="success"
            dismissible
          >
            {{ successMessage }}
          </VAlert>
          <VCardText>
            <VRow>
              <!-- 👉 Select Role -->
              <VCol
                cols="12"
                sm="4"
              >
                <VSelect
                  v-model="selectedRole"
                  label="Select Role"
                  :items="roles"
                  clearable
                  clear-icon="tabler-x"
                />
              </VCol>
              <!-- 👉 Select Plan -->
              <VCol
                cols="12"
                sm="4"
              >
                <VSelect
                  v-model="selectedPlan"
                  label="Select Plan"
                  :items="plans"
                  clearable
                  clear-icon="tabler-x"
                />
              </VCol>
              <!-- 👉 Select Status -->
              <VCol
                cols="12"
                sm="4"
              >
                <VSelect
                  v-model="selectedStatus"
                  label="Select Status"
                  :items="status"
                  clearable
                  clear-icon="tabler-x"
                />
              </VCol>
            </VRow>
          </VCardText>

          <VDivider />

          <VCardText class="d-flex flex-wrap py-4 gap-4">
            <div
              class="me-3"
              style="width: 80px;"
            >
              <VSelect
                v-model="rowPerPage"
                density="compact"
                variant="outlined"
                :items="[10, 20, 30, 50]"
              />
            </div>

            <VSpacer />

            <div class="app-user-search-filter d-flex align-center flex-wrap gap-4">
              <!-- 👉 Search  -->
              <div style="width: 10rem;">
                <VTextField
                  v-model="searchQuery"
                  placeholder="Search"
                  density="compact"
                />
              </div>

              <!-- 👉 Export button -->
              <VBtn
                variant="tonal"
                color="secondary"
                prepend-icon="tabler-screen-share"
              >
                Export
              </VBtn>

              <!-- 👉 Add room button -->
              <VBtn
                prepend-icon="tabler-plus"
                @click="isAddNewRoomDrawerVisible = true"
              >
                New Room
              </VBtn>
            </div>
          </VCardText>

          <VDivider />

          <VTable class="text-no-wrap">
            <!-- 👉 table head -->
            <thead>
              <tr>
                <th scope="col">
                  Room Number
                </th>
                <th scope="col">
                  Room Floor Number
                </th>
                <th scope="col">
                  Room Status
                </th>
                <th scope="col">
                  Capacity
                </th>
                <th scope="col">
                  Visibility
                </th>
               
               
                <th scope="col">
                  ACTIONS
                </th>
              </tr>
            </thead>
            <!-- 👉 Table Body -->
            <tbody>
              <tr
                v-for="room in rooms"
                :key="room.id"
                style="height: 3.75rem;"
              >
                <!-- 👉 Client Avatar and Email -->
                <td>
                  <div class="d-flex align-center">
                    <div class="d-flex flex-column">
                      <h6 class="text-base font-weight-medium mb-0">
                        {{ room.room_number }}
                      </h6>
                    </div>
                  </div>
                </td>

                <!-- 👉 total -->
                <td class="text-center">
                  {{ room.room_floor_number }}
                </td>

                <!-- 👉 Date -->
                <td>{{ room.room_status }}</td>

                <td>{{ room.capacity }}</td>
                <td>{{ room.hidden }}</td>

                <!-- 👉 Actions -->
                <td style="width: 8rem;">
                  <VBtn
                    icon
                    size="x-small"
                    color="default"
                    variant="text"
                    @click="()=>deleteRoom(room.id)"
                  >
                    <VIcon
                      size="22"
                      icon="tabler-trash"
                    />
                  </VBtn>

                  <VBtn
                    icon
                    variant="text"
                    color="default"
                    size="x-small"
                  >
                    <VIcon
                      :size="22"
                      icon="tabler-eye"
                    />
                  </VBtn>

                  <VBtn
                    icon
                    variant="text"
                    color="default"
                    size="x-small"
                  >
                    <VIcon
                      :size="22"
                      icon="tabler-dots-vertical"
                    />

                    <VMenu activator="parent">
                      <VList>
                        <VListItem :to="{ name: 'room-view-id', params: { id: room.room_number } }">
                          <template #prepend>
                            <VIcon
                              size="24"
                              class="me-3"
                              icon="tabler-pencil"
                            />
                          </template>

                          <VListItemTitle>Edit</VListItemTitle>
                        </VListItem>
                      </VList>
                    </VMenu>
                  </VBtn>
                </td>
              </tr>
            </tbody>

            <!-- 👉 table footer  -->
            <tfoot v-show="!rooms.length">
              <tr>
                <td
                  colspan="7"
                  class="text-center"
                >
                  No data available
                </td>
              </tr>
            </tfoot>
          </VTable>

          <VDivider />

          <VCardText class="d-flex align-center flex-wrap justify-space-between gap-4 py-3 px-5">
            <span class="text-sm text-disabled">
              {{ paginationData }}
            </span>

            <VPagination
              v-model="currentPage"
              size="small"
              :total-visible="5"
              :length="totalPage"
            />
          </VCardText>
        </VCard>
      </VCol>
    </VRow>

    <!-- 👉 Add New Room -->
    <AddNewRoomDrawer
      v-model:isDrawerOpen="isAddNewRoomDrawerVisible"
      :tags="tags"
      @room-data="addNewRoom"
    />
    
   
    <!-- 👉 Delete Room -->
    <RoomDeleteDialogue
      :key="selectedRoomId"
      v-model:isDeleteDialogueVisible="isRoomDeleteDialogueVisible"
      :selected-room-id="selectedRoomId"
      @delete-room-record="deleteRoomRecord"
    />
  </section>
</template>

<style lang="scss">
.app-user-search-filter {
  inline-size: 31.6rem;
}

.text-capitalize {
  text-transform: capitalize;
}

.user-list-name:not(:hover) {
  color: rgba(var(--v-theme-on-background), var(--v-high-emphasis-opacity));
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
