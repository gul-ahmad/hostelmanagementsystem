<script setup>
import moment from 'moment'
import AddNewRoomDrawer from '@/views/apps/room/list/AddnewRoomDrawer.vue'
import RoomDeleteDialogue from '@/views/apps/room/list/RoomDeleteDialogue.vue'


import { useReservationListStore } from '@/views/apps/reservation/useReservationListStore'
import { avatarText } from '@core/utils/formatters'
import { onMounted, ref } from 'vue'

const reservationListStore = useReservationListStore()
const searchQuery = ref('')
const selectedRole = ref()
const selectedPlan = ref()
const selectedStatus = ref()
const rowPerPage = ref(5)
const currentPage = ref(1)
const totalPage = ref(1)
const totalRooms = ref(0)
const reservations = ref([])

const tags = ref([])






// Fetch tags when the component is mounted
onMounted(() => {
 
})


//formate date using moment.js
//defining a fucntion for it composition api way

const formateDate = date => {
  return moment(date).format('DD-MM-YYYY')
}

// 👉 Fetching rooms
const fetchReservations = () => {
 
  reservationListStore.fetchReservations({
    q: searchQuery.value,

    status: selectedStatus.value,
    plan: selectedPlan.value,
    role: selectedRole.value,
    perPage: rowPerPage.value,
    currentPage: currentPage.value,
  }).then(response => {
 
    reservations.value = response.data.reservations

    console.log(reservations.value)
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

watchEffect(fetchReservations)



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

// 👉 watching current page
watchEffect(() => {

  if (currentPage.value > totalPage.value)
    currentPage.value = totalPage.value
})

// 👉 Computing pagination data
const paginationData = computed(() => {
  const firstIndex = reservations.value.length ? (currentPage.value - 1) * rowPerPage.value + 1 : 0
  const lastIndex = reservations.value.length + (currentPage.value - 1) * rowPerPage.value
  
  return `Showing ${ firstIndex } to ${ lastIndex } of ${ totalRooms.value } entries`
})


// 👉 Room Slots  variant resolver
const resolveRoomSlotsVariant = (available_slots, capacity) => {
  if (available_slots === capacity)
    return {
      status: 'No Slot Booked',
      chip: { color: 'error' },
    }
  if (available_slots === 0)
    return {
      status: 'Fully Booked',
      chip: { color: 'success' },
    }
  
  return {
    status: available_slots,
    chip: { variant: 'text' },
  }
}


// 👉 Reservation Status variant resolver
const resolveReservationStatusVariantAndIcon = status => {
  if (status == 1)

  //  alert(status)
  
    return {
      variant: 'Active',
      icon: 'tabler-circle-check',
     
    }
  if (status == 2)
    return {
      variant: 'error',
      icon: 'tabler-alert-circle',
    }

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
                  Start Date
                </th>
                <th scope="col">
                  End Date
                </th>
                <th scope="col">
                  Price
                </th>
                <th scope="col">
                  User Name
                </th>
                <th scope="col">
                  Room Number
                </th>
                <th scope="col">
                  Status
                </th>
                <th scope="col">
                  Available Slots
                </th>
               
               
                <th scope="col">
                  ACTIONS
                </th>
              </tr>
            </thead>
            <!-- 👉 Table Body -->
            <tbody>
              <tr
                v-for="reservation in reservations"
                :key="reservation.id"
                style="height: 3.75rem;"
              >
                <!-- 👉 Client Avatar and Email -->
                <td>
                  <div class="d-flex align-center">
                    <div class="d-flex flex-column">
                      <h6 class="text-base font-weight-medium mb-0">
                        {{ formateDate(reservation.start_date) }}
                      </h6>
                    </div>
                  </div>
                </td>

                <!-- 👉 total -->
                <td class="text-center">
                  {{ formateDate(reservation.end_date) }}
                </td>

                <!-- 👉 Date -->
                <td>{{ reservation.price }}</td>

                <td>{{ reservation.user.name }}</td>
                <td>{{ reservation.room.room_number }}</td>
                <td class="text-center">
                  <VTooltip>
                    <template #activator="{ props }">
                      <VAvatar
                        :size="30"
                        v-bind="props"
                        :color="resolveReservationStatusVariantAndIcon(reservation.status).variant"
                        variant="tonal"
                      >
                        <VIcon
                          :size="20"
                          :icon="resolveReservationStatusVariantAndIcon(reservation.status).icon"
                        />
                      </VAvatar>
                    </template>

                   
                    <p class="mb-0">
                      Room Capacity: {{ reservation.room.capacity }}
                    </p>
                  </VTooltip>
                </td>
            
                <td class="text-center">
                  <VChip
                    label
                    v-bind="resolveRoomSlotsVariant(reservation.room.available_slots, reservation.room.capacity).chip"
                    size="small"
                  >
                    {{ resolveRoomSlotsVariant(reservation.room.available_slots, reservation.room.capacity).status }}
                  </VChip>
                </td>

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
                        <VListItem :to="{ name: 'reservation-view-id', params: { id: reservation.id } }">
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
            <tfoot v-show="!reservations.length">
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
