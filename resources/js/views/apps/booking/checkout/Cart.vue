<script setup>
const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
   
  },
  checkoutData: {
    type: null,
    required: true,
  },

//cartItems: { type:Array,default:null }, 
})

const emit = defineEmits([
  'update:currentStep',
  'update:checkout-data',
])

//console.log(props.checkoutData)

const checkoutCartDataLocal = ref(props.checkoutData)

console.log(props.currentStep)


//console.log(checkoutCartDataLocal.value)



const removeItem = item => {
  checkoutCartDataLocal.value.cartItems = checkoutCartDataLocal.value.cartItems.filter(i => i.id !== item.id)

  // console.log(checkoutCartDataLocal.value.cartItems)
}

const cartData = checkoutCartDataLocal.value

console.log(cartData)
console.log(cartData.checkoutData.prices)


// const checkoutDataNew = cartData.checkoutData

// console.log(checkoutDataNew)

// const prices = checkoutDataNew.prices

// console.log(prices)

//Gul here using below computed function to find the reservationType
const resolveReservationTypeMethod = computed(() => {
  if (cartData.checkoutData.capacity === 1)
    return {
      method: 1,
    }
  else if (cartData.checkoutData.capacity === 2)
    return {
      
    }
  else
    return {
      method: 3,
    }
})



//  cart total
const totalCost = computed(() => {
  const checkoutDataNew = cartData.checkoutData
  const prices = checkoutDataNew.prices
  const method = resolveReservationTypeMethod.value.method
  if (prices && prices.discount_on_full_allocation > 0) {
    if (method === 1) {
      return prices.price_for_one_person_booking - prices.discount_on_full_allocation
    } else if (method === 2) {
      return prices.price_for_two_person_booking - prices.discount_on_full_allocation
    } else {
      return prices.price_for_three_person_booking - prices.discount_on_full_allocation
    }
  } else {
    if (method === 1) {
      return prices.price_for_one_person_booking
    } else if (method === 2) {
      return prices.price_for_two_person_booking
    } else {
      return prices.price_for_three_person_booking
    }
  }

  //   // if (cartData && cartData.checkoutData.prices && cartData.checkoutData.prices.discount_on_full_allocation > 0) {
  //   //   return cartData.checkoutData.prices.price_for_one_person_booking - cartData.checkoutData.prices.discount_on_full_allocation
  //   // } else if (cartData && cartData.checkoutData.prices) {
  //   //   return cartData.checkoutData.prices.price_for_one_person_booking
  //   // }

//   // return 0 // Default value if data is not yet available
})


const updateCartData = () => {
  // emit('update:checkout-data', checkoutCartDataLocal.value)
  const checkoutData = checkoutCartDataLocal.value

  emit('update:checkout-data',{ checkoutData,totalCost:totalCost.value })
}

const nextStep = () => {
  updateCartData()
  emit('update:currentStep', props.currentStep ? props.currentStep + 1 : 1,totalCost.value)
}

watch(() => props.currentStep, updateCartData)
</script>

<template>
  <VRow v-if="checkoutCartDataLocal">
    <VCol
      cols="12"
      md="8"
    >
      <!-- 👉 Offers alert -->
      <VAlert
        color="success"
        variant="tonal"
      >
        <template #prepend>
          <VIcon
            size="34"
            icon="tabler-bookmarks"
          />
        </template>

        <VAlertTitle class="text-success mb-3">
          Available Offers
        </VAlertTitle>

        <p class="mb-1">
          - 10% Instant Discount on Bank of America Corp Bank Debit and Credit cards
        </p>
        <p class="mb-0">
          - 25% Cashback Voucher of up to $60 on first ever PayPal transaction. TCA
        </p>
      </VAlert>

      <h6 class="text-h6 my-4">
        My Shopping Bag ({{ checkoutCartDataLocal.length }} Items)
      </h6>

      <!-- 👉 Cart items -->
      <div class="border rounded">
        <template
          v-for="(item, index) in checkoutCartDataLocal"
          :key="item.id"
        >
          <div
            class="d-flex align-center gap-3 pa-5 position-relative flex-column flex-sm-row"
            :class="index ? 'border-t' : ''"
          >
            <IconBtn
              class="checkout-item-remove-btn"
              @click="removeItem(item)"
            >
              <VIcon
                size="20"
                icon="tabler-x"
              />
            </IconBtn>

            <div>
              <VImg
                width="140"
                :src="item.image"
              />
            </div>

            <div
              class="d-flex w-100"
              :class="$vuetify.display.width <= 700 ? 'flex-column' : 'flex-row'"
            >
              <div>
                <h6 class="text-base font-weight-regular mb-4">
                  Room Number {{ item.room_number }}
                </h6>
                <div class="d-flex align-center text-no-wrap gap-2 text-base">
                  <span class="text-disabled">Capacity:</span>
                  <span class="text-primary">{{ item.capacity }}</span>
                  <VChip
                    :color="item.room_status ? 'success' : 'error'"
                    label
                  >
                    <span class="text-xs font-weight-medium">
                      Available Slots  {{ item.available_slots }}
                    </span>
                  </VChip>
                </div>

                <div class="mt-1">
                  <VRating
                    density="compact"
                    :model-value="item.id"
                    readonly
                  />
                </div>

                <AppTextField
                  v-model="item.room_number"
                  type="number"
                  style="width: 7.5rem;"
                  density="compact"
                />
              </div>

              <VSpacer />

              <div
                class="d-flex flex-column justify-space-between mt-5"
                :class="$vuetify.display.width <= 700 ? 'text-start' : 'text-end'"
              >
                <p
                  v-if="item.prices.price_for_one_person_booking"
                  class="text-base mt-4"
                >
                  <span class="text-primary">${{ item.prices.price_for_one_person_booking }}</span>
                  <span>/</span>
                  <span class=" text-disabled">For Full Booking</span>
                </p>
                <p
                  v-if="item.prices.price_for_two_person_booking"
                  class="text-base mt-4"
                >
                  <span class="text-primary">${{ item.prices.price_for_two_person_booking }}</span>
                  <span>/</span>
                  <span class=" text-disabled">For 2 Persons</span>
                </p>
                <p
                  v-if="item.prices.price_for_three_person_booking"
                  class="text-base mt-4"
                >
                  <span class="text-primary">${{ item.prices.price_for_three_person_booking }}</span>
                  <span>/</span>
                  <span class=" text-disabled">For 3 Persons</span>
                </p>

                <div>
                  <VBtn variant="tonal">
                    move to wishlist
                  </VBtn>
                </div>
              </div>
            </div>
          </div>
        </template>
      </div>

      <!-- 👉 Add more from wishlist -->
      <div class="d-flex align-center justify-space-between border rounded py-2 px-5 text-base mt-4">
        <a href="#">Add more products from wishlist</a>
        <VIcon
          icon="tabler-chevron-right"
          class="flip-in-rtl"
        />
      </div>
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <VCard
        flat
        variant="outlined"
      >
        <!-- 👉 payment offer -->
        <VCardText>
          <h6 class="text-base font-weight-medium mb-3">
            Offer
          </h6>

          <div class="d-flex align-center gap-4">
            <AppTextField
            
              density="compact"
              placeholder="Enter Promo Code"
            />

            <VBtn
              variant="tonal"
              @click="updateCartData"
            >
              Apply
            </VBtn>
          </div>

          <!-- 👉 Gift wrap banner -->
          <div class="bg-var-theme-background rounded pa-5 mt-4">
            <h6 class="text-base font-weight-medium mb-1">
              Buying gift for a loved one?
            </h6>
            <p>
              Gift wrap and personalized message on card, Only for $2.
            </p>

            <a
              href="#"
              class="font-weight-medium"
            >Add a gift wrap</a>
          </div>
        </VCardText>

        <VDivider />

        <!-- 👉 Price details -->
        <VCardText>
          <h6 class="text-base font-weight-medium mb-3">
            Price Details
          </h6>

          <div class="text-high-emphasis">
            <div class="d-flex justify-space-between mb-2">
              <span>Room Price</span>
              <span>PKR{{ cartData.checkoutData.prices.price_for_one_person_booking }}</span>
            </div>


            <div class="d-flex justify-space-between mb-2">
              <span>Discount </span>
              <span>PKR{{ cartData.checkoutData.prices.discount_on_full_allocation }}</span>
            </div>
          </div>
        </VCardText>

        <VDivider />

        <VCardText class="d-flex justify-space-between py-4">
          <h6 class="text-base font-weight-medium">
            Total
          </h6>
          <h6 class="text-base font-weight-medium">
            PKR{{ totalCost }}
          </h6>
        </VCardText>
      </VCard>

      <VBtn
        block
        class="mt-4"
        @click="nextStep(totalCost)"
      >
        Place Order
      </VBtn>
    </VCol>
  </VRow>
</template>

<style lang="scss" scoped>
.checkout-item-remove-btn {
  position: absolute;
  inset-block-start: 10px;
  inset-inline-end: 10px;
}
</style>
