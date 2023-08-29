<script setup>
import { ref, defineProps, defineEmits } from 'vue'

import safeBoxWithGoldenCoin from '@images/misc/3d-safe-box-with-golden-dollar-coins.png'
import spaceRocket from '@images/misc/3d-space-rocket-with-smoke.png'
import dollarCoinPiggyBank from '@images/misc/dollar-coins-flying-pink-piggy-bank.png'
import { useAuthStore } from '@/views/apps/frontend/rooms/useAuthStore'


const props = defineProps({

  rooms: {
    type: Object,
    required: true,
  },
  
  
 
})

const emit = defineEmits(
  [
    'update:checkout-data',
  ]) // Use correct event names



const isBookingLoginDialogueVisible = ref(false)

const annualMonthlyPlanPriceToggler = ref(true)

const pricingPlans = [
  {
    name: 'Basic',
    tagLine: 'A simple start for everyone',
    logo: dollarCoinPiggyBank,
    monthlyPrice: 0,
    yearlyPrice: 0,
    isPopular: false,
    current: true,
    features: [
      '100 responses a month',
      'Unlimited forms and surveys',
      'Unlimited fields',
      'Basic form creation tools',
      'Up to 2 subdomains',
    ],
  },
  {
    name: 'Standard',
    tagLine: 'For small to medium businesses',
    logo: safeBoxWithGoldenCoin,
    monthlyPrice: 42,
    yearlyPrice: 460,
    isPopular: true,
    current: false,
    features: [
      'Unlimited responses',
      'Unlimited forms and surveys',
      'Instagram profile page',
      'Google Docs integration',
      'Custom “Thank you” page',
    ],
  },
  {
    name: 'Enterprise',
    tagLine: 'Solution for big organizations',
    logo: spaceRocket,
    monthlyPrice: 84,
    yearlyPrice: 690,
    isPopular: false,
    current: false,
    features: [
      'PayPal payments',
      'Logic Jumps',
      'File upload with 5GB storage',
      'Custom domain support',
      'Stripe integration',
    ],
  },
]

const authStore = useAuthStore() // Access the Pinia store instance
const isAuthenticated = computed(() => authStore.isAuthenticated)
const user = computed(() => authStore.user)

const handleAuthentication = room => {

  // console.log('>>>>>>>>>>>>>')
  isBookingLoginDialogueVisible.value = false // Close the dialogue
  // isAuthenticated.value = true

  
  emit('update:checkout-data', { checkoutData: room })
}

const navigateToCart =room =>{
 
  if (!isAuthenticated.value) {
    isBookingLoginDialogueVisible.value = true
  } else {
    // Proceed with navigating to the cart or any other action you want
    // For example, emit an event to update the checkout data
    emit('update:checkout-data', { checkoutData: room })
  }
  

  
}
</script>

<template>
  <!-- 👉 Title and subtitle -->
  <div class="text-center">
    <h4 class="text-h4 pricing-title mb-4">
      Rooms
    </h4>
  </div>

  <!-- 👉 Annual and monthly price toggler -->

  
  <!-- SECTION pricing plans -->
  <VRow>
    <VCol
      v-for="room in rooms"
      :key="room.id"
      v-bind="props"
      cols="12"
    >
      <!-- 👉  Card -->
      <VCard
        flat
        border
      >
        <VCardText class="text-center pt-15">
          <!-- 👉 Avatar -->
          <VContainer fluid>
            <VRow>
              <VCol
                v-for="image in room.images"
                :key="image.id"
                cols="12"
                md="6"
                lg="4"
              >
                <VCard class="mb-4">
                  <VTooltip bottom>
                    <template #activator="{ on, attrs }">
                      <div class="image-container">
                        <VImg
                          v-if="image.path"
                          :src="'/storage/roomsfinal/tmp/' + image.path"
                          :alt="image.path"
                          v-bind="attrs"
                          v-on="on"
                        />
                      </div>
                    </template>
                  </VTooltip>
                </VCard>
              </VCol>
            </VRow>
          </VContainer>
        </VCardText>
        <VCardText class="text-center">
          <!-- 👉 Plan name -->
          <h5 class="text-h5 mb-2">
            Room Number:  {{ room.room_number }}
          </h5>
        </VCardText>

        <!-- 👉 Plan price  -->
        <VCardText class="position-relative text-center">
          <div class="d-flex justify-center align-center">
            <sup class="text-sm font-weight-medium me-1">PKR</sup>
            <h1
              v-if="room.prices.price_for_three_person_booking"
              class="text-5xl font-weight-medium text-primary"
            >
              {{ room.prices.price_for_three_person_booking }}
            </h1>
            <sub
              v-if="room.prices.price_for_three_person_booking"
              class="text-sm font-weight-medium ms-1 mt-4"
            >/month/3 Person</sub>
            <h1
              v-if="room.prices.price_for_two_person_booking"
              class="text-5xl font-weight-medium text-primary"
            >
              {{ room.prices.price_for_two_person_booking }}
            </h1>
            <sub
              v-if="room.prices.price_for_two_person_booking"
              class="text-sm font-weight-medium ms-1 mt-4"
            >/month/2 Person</sub>
            <h1
              v-if="room.prices.price_for_one_person_booking"
              class="text-5xl font-weight-medium text-primary"
            >
              {{ room.prices.price_for_one_person_booking }}
            </h1>
            <sub
              v-if="room.prices.price_for_one_person_booking"
              class="text-sm font-weight-medium ms-1 mt-4"
            >/month/1 Person</sub>
          </div>

          <!-- 👉 Annual Price -->
          <span
            v-show="annualMonthlyPlanPriceToggler"
            class="position-absolute text-caption font-weight-medium mt-1"
            style="inset-inline: 0;"
          >
            Room Capacity:{{ room.capacity }} && AvailableSlot:{{ room.available_slots }}  </span>
        </VCardText>

        <!-- 👉 Plan features -->
        <VCardText class="mt-5">
          <VList class="card-list">
            <VListItem>
              <template #prepend>
                <VIcon
                  :size="14"
                  icon="tabler-circle"
                  class="me-3"
                />
              </template>

              <!-- 👉 Room Tags   -->
              <VList class="card-list mt-2">
                <VListItem>
                  <VListItemTitle>
                    <h6 class="text-base font-weight-semibold">
                      Facilities:
                      <span 
                        v-for="(tag,index) in room.tags"
                        :key="tag.id"
                        class="text-body-2" 
                      >
                        {{ tag.name }}
                        <span v-if="index !== room.tags.length - 1">, </span>
                      </span>
                    </h6>
                  </VListItemTitle>
                </VListItem>
              </VList>
            </VListItem>
          </VList>
        </VCardText>
      </VCard>
      <!-- 👉 Edit and Suspend button -->
      <VCardText class="d-flex justify-center">
        <VBtn
          variant="elevated"
          class="me-3"
          @click="navigateToCart(room)"
        >
          Book Me
        </VBtn>
      </VCardText>
      <div>
        isAuthenticated: {{ isAuthenticated }}
        isBookingLoginDialogueVisible: {{ isBookingLoginDialogueVisible }}
        user : {{ user }}
      </div>
      <!-- 👉 User Login Dialogue -->
      <UserLoginDialogue
        v-if="!isAuthenticated && isBookingLoginDialogueVisible"
        v-model:isDialogVisible="isBookingLoginDialogueVisible"
        :room-id="room"
        @authenticated="handleAuthentication(room)"
      />
    </VCol>
  </VRow>
 
  <!-- !SECTION  -->
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.75rem;
}

.save-upto-chip {
  inset-block-start: -1.5rem;
  inset-inline-end: -7rem;
}
</style>
