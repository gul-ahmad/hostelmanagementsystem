<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router' // Import the useRouter function

import laptopGirl from '@images/illustrations/laptop-girl.png'

import AddNewRoomDrawer from '@/views/apps/room/list/AddnewRoomDrawer.vue'
import RoomDeleteDialogue from '@/views/apps/room/list/RoomDeleteDialogue.vue'


import { useRoomListStore } from '@/views/apps/frontend/rooms/useRoomListStore'
import { useCheckoutStore } from '@/views/apps/booking/checkout/useCheckoutStore'

import { avatarText } from '@core/utils/formatters'

const roomListStore = useRoomListStore()
const checkoutStore = useCheckoutStore()

const searchQuery = ref('')
const selectedRole = ref()
const selectedPlan = ref()
const selectedStatus = ref()
const rowPerPage = ref(10)
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

//const checkoutData = ref()

const addToCart = room => { // Use the correct property name
  
  console.log(room)

  //const checkoutData   = room

  // console.log(checkoutData)

  //checkoutData.value.push(...items)
  //console.log(checkoutData.value)

  checkoutStore.setCheckoutData(room)
  navigateToCheckout()
}

const router = useRouter() // Initialize the router object

const navigateToCheckout = () => {
  //const roomDataString = JSON.stringify(checkoutData)
  //checkoutStore.setCheckoutData(checkoutData)

  router.push({ name: 'booking-checkout' }) // Use the named route for navigation
}
</script>

<template>
  <section>
    <VRow>
      <VCol cols="12">
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
                  @update:checkout-data="addToCart"
                >
                  />
                </apppricing>
              </VCol>
            </VRow>
          </VCardText>

   
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
    <!-- Pass cartItems to Cart component -->
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
