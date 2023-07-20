<script setup>
import laptopGirl from '@images/illustrations/laptop-girl.png'

import AddNewRoomDrawer from '@/views/apps/room/list/AddnewRoomDrawer.vue'
import RoomDeleteDialogue from '@/views/apps/room/list/RoomDeleteDialogue.vue'


import { useRoomListStore } from '@/views/apps/frontend/rooms/useRoomListStore'
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
    perPage: rowPerPage.value,
    currentPage: currentPage.value,
  }).then(response => {
 
    rooms.value = response.data.rooms

    console.log(rooms.value)
   
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

const features = [
  {
    feature: '14-days free trial',
    basic: true,
    standard: true,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: false,
      enterprise: false,
    },
  },
  {
    feature: 'No user limit',
    basic: false,
    standard: false,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: false,
      enterprise: false,
    },
  },
  {
    feature: 'Product Support',
    basic: false,
    standard: true,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: false,
      enterprise: false,
    },
  },
  {
    feature: 'Email Support',
    basic: false,
    standard: false,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: true,
      enterprise: false,
    },
  },
  {
    feature: 'Integrations',
    basic: false,
    standard: true,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: false,
      enterprise: false,
    },
  },
  {
    feature: 'Removal of Front branding',
    basic: false,
    standard: false,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: true,
      enterprise: false,
    },
  },
  {
    feature: 'Active maintenance & support',
    basic: false,
    standard: false,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: false,
      enterprise: false,
    },
  },
  {
    feature: 'Data storage for 365 days',
    basic: false,
    standard: false,
    enterprise: true,
    addOnAvailable: {
      starter: false,
      pro: false,
      enterprise: false,
    },
  },
]

const faqs = [
  {
    question: 'What counts towards the 100 responses limit?',
    answer: 'Donec placerat, lectus sed mattis semper, neque lectus feugiat lectus, varius pulvinar diam eros in elit. Pellentesque convallis laoreet laoreet.Donec placerat, lectus sed mattis semper, neque lectus feugiat lectus, varius pulvinar diam eros in elit. Pellentesque convallis laoreet laoreet.',
  },
  {
    question: 'How do you process payments?',
    answer: 'We accept Visa®, MasterCard®, American Express®, and PayPal®. So you can be confident that your credit card information will be kept safe and secure.',
  },
  {
    question: 'What payment methods do you accept?',
    answer: '2Checkout accepts all types of credit and debit cards.',
  },
  {
    question: 'Do you have a money-back guarantee?',
    answer: 'Yes. You may request a refund within 30 days of your purchase without any additional explanations.',
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
        </VCard>
        <VCard class="pt-6">
          <VCardText class="pt-12 mb-16 pb-16">
            <!-- 👉 App Pricing components -->
            <VRow>
              <VCol
                cols="12"
                sm="8"
                md="12"
                lg="10"
                class="mx-auto"
              >
                <AppPricing
                  :rooms="rooms"
                  md="4"
                />
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 Free trial Banner -->
          <VRow class="page-pricing-free-trial-banner-bg">
            <VCol
              cols="12"
              md="10"
              class="d-flex align-center flex-md-row flex-column position-relative mx-auto"
            >
              <div class="text-center text-md-start py-10 px-10 px-sm-0">
                <h3 class="text-h5 text-primary mb-2">
                  Still not convinced? Start with a 14-day FREE trial!
                </h3>
                <p class="text-sm">
                  You will get full access to all the features for 14 days.
                </p>

                <VBtn class="mt-4">
                  Start-14-day FREE trial
                </VBtn>
              </div>

              <div class="free-trial-illustrator">
                <VImg
                  :src="laptopGirl"
                  :width="230"
                />
              </div>
            </VCol>
          </VRow>

          <!-- 👉 Plans -->
          <VCardText class="text-center mt-16">
            <h4 class="text-h4 mb-2">
              Pick a plan that works best for you
            </h4>
            <p>Stay cool, we have a 48-hour money back guarantee!</p>
          </VCardText>

          <!-- 👉 Features & Tables -->
          <VCardText class="mb-16 mt-2">
            <VRow>
              <VCol
                cols="12"
                md="10"
                class="mx-auto"
              >
                <VTable class="text-no-wrap border rounded">
                  <!-- 👉 Table head -->
                  <thead>
                    <tr>
                      <th
                        scope="col"
                        class="py-4"
                      >
                        <h6 class="text-sm font-weight-semibold mb-1">
                          FEATURES
                        </h6>
                        <span class="font-weight-regular text-sm text-disabled">
                          Native Front Features
                        </span>
                      </th>

                      <th
                        scope="col"
                        class="text-center py-4"
                      >
                        <h6 class="text-sm font-weight-semibold mb-1">
                          BASIC
                        </h6>
                        <span class="text-disabled font-weight-regular text-sm">
                          FREE
                        </span>
                      </th>

                      <th
                        scope="col"
                        class="text-center py-4"
                      >
                        <h6 class="text-sm font-weight-semibold mb-1">
                          STANDARD
                          <VAvatar
                            size="22"
                            color="primary"
                            class="mt-n2"
                          >
                            <VIcon
                              size="16"
                              icon="tabler-star"
                            />
                          </VAvatar>
                        </h6>

                        <span class="text-disabled font-weight-regular text-sm">
                          $7.5/MONTH
                        </span>
                      </th>

                      <th
                        scope="col"
                        class="text-center py-4"
                      >
                        <h6 class="text-sm font-weight-semibold mb-1">
                          ENTERPRISE
                        </h6>
                        <span class="text-disabled font-weight-regular text-sm">
                          $16/MONTH
                        </span>
                      </th>
                    </tr>
                  </thead>

                  <!-- 👉 Table Body -->
                  <tbody>
                    <tr
                      v-for="feature in features"
                      :key="feature.feature"
                    >
                      <td>{{ feature.feature }}</td>
                      <td class="text-center">
                        <VChip
                          v-if="!feature.addOnAvailable.starter"
                          pill
                          size="30"
                          class="pa-1"
                          :color="feature.basic ? 'primary' : 'secondary'"
                        >
                          <VIcon
                            size="15"
                            :icon="feature.basic ? 'tabler-check' : 'tabler-x'"
                          />
                        </VChip>

                        <VChip
                          v-if="feature.addOnAvailable.starter"
                          color="primary"
                          size="small"
                        >
                          ADD-ON AVAILABLE
                        </VChip>
                      </td>

                      <td class="text-center">
                        <VChip
                          v-if="!feature.addOnAvailable.pro"
                          pill
                          size="30"
                          class="pa-1"
                          :color="feature.standard ? 'primary' : 'secondary'"
                        >
                          <VIcon
                            size="15"
                            :icon="feature.standard ? 'tabler-check' : 'tabler-x'"
                          />
                        </VChip>

                        <VChip
                          v-if="feature.addOnAvailable.pro"
                          color="primary"
                          size="small"
                          label
                        >
                          ADD-ON AVAILABLE
                        </VChip>
                      </td>

                      <td class="text-center">
                        <VChip
                          v-if="!feature.addOnAvailable.enterprise"
                          pill
                          size="30"
                          class="pa-1"
                          :color="feature.enterprise ? 'primary' : 'secondary'"
                        >
                          <VIcon
                            size="15"
                            :icon="feature.enterprise ? 'tabler-check' : 'tabler-x'"
                          />
                        </VChip>

                        <VChip
                          v-if="feature.addOnAvailable.enterprise"
                          color="primary"
                          size="small"
                        >
                          ADD-ON AVAILABLE
                        </VChip>
                      </td>
                    </tr>
                  </tbody>

                  <!-- 👉 Table footer -->
                  <tfoot>
                    <tr>
                      <td class="py-4">
                        Data storage for 365 days
                      </td>

                      <td class="text-center py-4">
                        <VBtn variant="tonal">
                          Choose Plan
                        </VBtn>
                      </td>
                      <td class="text-center py-4">
                        <VBtn>
                          Choose Plan
                        </VBtn>
                      </td>
                      <td class="text-center py-4">
                        <VBtn variant="tonal">
                          Choose Plan
                        </VBtn>
                      </td>
                    </tr>
                  </tfoot>
                </VTable>
              </VCol>
            </VRow>
          </VCardText>

          <!-- 👉 FAQ -->
          <div>
            <VCardText class="bg-var-theme-background py-16">
              <div class="text-center">
                <h4 class="text-h4 mb-2">
                  FAQ's
                </h4>
                <p>
                  Let us help answer the most common questions.
                </p>
              </div>

              <VExpansionPanels class="py-6 px-16">
                <VExpansionPanel
                  v-for="faq in faqs"
                  :key="faq.question"
                  :title="faq.question"
                  :text="faq.answer"
                />
              </VExpansionPanels>
            </VCardText>
          </div>
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
