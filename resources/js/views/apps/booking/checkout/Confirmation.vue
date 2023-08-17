<script setup>
import { useRouter } from 'vue-router'

import { useCheckoutStore } from '@/views/apps/booking/checkout/useCheckoutStore'

//import { onMounted } from 'vue'

const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
    default:3,
  },
  checkoutData: {
    type: null,
    required: true,
  },
})

const emit = defineEmits([
  'update:currentStep',
  'update:checkout-data',
])

const router = useRouter()

const checkoutStore = useCheckoutStore()

onMounted(() => {
  // emit('update:currentStep', 0)
  // emit('update:checkout-data', null)
  checkoutStore.clearCheckoutData()

  // Redirect to the home page after a delay (e.g., 3 seconds)
  setTimeout(() => {
    router.push({ name: 'frontend-rooms-list' }) // Replace 'home' with your actual route name
  }, 10000) // 3000 milliseconds (3 seconds)

})

//const prop = __props
//const checkoutConfirmationData = ref(props.checkoutData)

//console.log(checkoutConfirmationData.value)

// const selectedDeliveryAddress = computed(() => {
//   return props.checkoutData.addresses.filter(address => {
//     return address.value === props.checkoutData.deliveryAddress
//   })
// })

// const resolveDeliveryMethod = computed(() => {
//   if (props.checkoutData.deliverySpeed === 'overnight')
//     return {
//       method: 'Overnight Delivery',
//       desc: 'In 1 business day.',
//     }
//   else if (props.checkoutData.deliverySpeed === 'express')
//     return {
//       method: 'Express Delivery',
//       desc: 'Normally in 3-4 business days',
//     }
//   else
//     return {
//       method: 'Standard Delivery',
//       desc: 'Normally in 1 Week',
//     }
// })
</script>

<template>
  <section class="text-base">
    <div class="text-center">
      <h5 class="text-h5 mb-3">
        Thank You! 😇
      </h5>
      <p>
        Your Booking is Done with the ID of<span class="text-primary">{{ props.checkoutData.id }}</span> and your transction id is {{ props.checkoutData.transaction_id }}
      </p>
      <p class="mb-0">
        Your Room number is  <span class="text-primary">{{ props.checkoutData.room.room_number }}.
        </span>
      </p>
      <p class="d-flex align-center gap-2 justify-center">
        <VIcon
          size="20"
          icon="tabler-clock"
        />
        <span>Time placed: {{ props.checkoutData.created_at }}</span>
      </p>
      <p class="mb-0">
        This page will be redirected back to room Page after <span class="bg-indigo-500">
          10 seconds
        </span>
      </p>
    </div>
    

  

    <VRow>
      <VCol
        cols="12"
        md="9"
      >
        <!-- 👉 cart items -->
        <div class="border rounded">
          <template>
            <div
              class="d-flex align-start gap-3 pa-5 position-relative flex-column flex-sm-row"
              :class="index ? 'border-t' : ''"
            >
              <div>
                <VImg
                  width="80"
                />
              </div>

              <div
                class="d-flex w-100 pt-3"
                :class="$vuetify.display.width <= 700 ? 'flex-column' : 'flex-row'"
              >
                <div>
                  <h6 class="text-base font-weight-regular mb-4">
                    asdf
                  </h6>
                  <div class="d-flex align-center text-no-wrap gap-2 text-base">
                    <span class="text-disabled">Sold by:</span>
                    <span class="text-primary">asdf</span>
                    <VChip
                    
                      label
                    >
                      <span>
                        asdf
                      </span>
                    </VChip>
                  </div>
                </div>

                <VSpacer />

                <div
                  class="d-flex flex-column justify-space-between mt-3"
                  :class="$vuetify.display.width <= 700 ? 'text-start' : 'text-end'"
                >
                  <p class="text-base mb-0">
                    <span class="text-primary">$dfdfdf</span>
                    <span>/</span>
                    <span class="text-decoration-line-through text-disabled">$dfdf</span>
                  </p>
                </div>
              </div>
            </div>
          </template>
        </div>
      </VCol>

      <VCol
        cols="12"
        md="3"
      >
        <div class="border rounded">
          <div class="border-b pa-5">
            <h6 class="text-base font-weight-medium mb-3">
              Price Details
            </h6>
          </div>
          <div class="d-flex align-center justify-space-between text-high-emphasis font-weight-medium px-5 py-3">
            <span>Total</span>
            <span>{{ props.checkoutData.price }}PKR</span>
          </div>
        </div>
      </VCol>
    </VRow>
  </section>
</template>
