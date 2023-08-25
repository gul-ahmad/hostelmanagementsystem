<script setup>
import { loadStripe } from '@stripe/stripe-js'

//import { onMounted, ref } from 'vue'
import axios from '@axios'


const props = defineProps({
  currentStep: {
    type: Number,
    required: false,
   
  },
  checkoutData: {
    type: null,
    required: true,
  },
  totalCost:{ type:Number ,default:0 },
})

const emit = defineEmits([
  'update:currentStep',
  'update:checkout-data',
])

//console.log(props.checkoutData)

console.log(props.currentStep)


const prop = __props
const checkoutPaymentDataLocal = ref(prop.checkoutData)
const totalCost = ref(checkoutPaymentDataLocal.value.totalCost)

const confirmationDataEmit = ref([''])

//console.log(checkoutPaymentDataLocal.value.checkoutData.checkoutData.id)



//console.log(checkoutPaymentDataLocal.value.totalCost)

const selectedPaymentMethod = ref('card')

//Gul here using below computed function to find the reservationType
const resolveReservationTypeMethod = computed(() => {
  if (checkoutPaymentDataLocal.value.checkoutData.checkoutData.capacity === 1)
    return {
      method: 1,
    }
  else if (checkoutPaymentDataLocal.value.checkoutData.checkoutData.capacity === 2)
    return {
      method: 2,
    }
  else
    return {
      method: 3,
    }
})

const cardFormData = {
 
  startDate:'',
  endDate:'',
  reservationType:resolveReservationTypeMethod.value,
  roomId:checkoutPaymentDataLocal.value.checkoutData.checkoutData.id,
  firstName:'',
  lastName:'',
  name:'',

  // email:'',
  // password:'',
  // confirmPassword:'',
  city:'',
  address:'',
  phoneNumber:'',
  amount:totalCost.value,
  paymentMethod:'',

}

const codFormData = {
 
  startDate:'',
  endDate:'',
  reservationType:resolveReservationTypeMethod.value,
  roomId:checkoutPaymentDataLocal.value.checkoutData.checkoutData.id,
  firstName:'',
  lastName:'',
  name:'',

  // email:'',
  // password:'',
  // confirmPassword:'',
  city:'',
  address:'',
  phoneNumber:'',
  amount:totalCost.value,
  paymentMethod:'',

}

const resetStripeCardElement = () => {
  if (cardElement.value) {
    cardElement.value.clear()
  }
}

const resetFormData = () => {
  cardFormData.startDate = ''
  cardFormData.endDate = ''
  cardFormData.firstName = ''
  cardFormData.lastName = ''
  cardFormData.email = ''
  cardFormData.city = ''
  cardFormData.phoneNumber = '',
  cardFormData.confirmPassword = ''

  // ... reset other fields as needed

  resetStripeCardElement() // Reset Stripe card element
}

console.log(cardFormData.reservationType)

const stripe = ref(null)
const cardElement = ref(null)
const paymentProcessing = ref(false)

// stripe:{}
// cardElement: {}

onMounted(async() => {

  stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY)

  const elements = stripe.value.elements()


  cardElement.value = elements.create('card', {
    classes: {
      base: '',
    },
  })

  cardElement.value.mount('#card-element')
})


const giftCardFormData = ref({
  giftCardNumber: null,
  giftCardPin: null,
})

const selectedDeliveryAddress = computed(() => {
  // return checkoutPaymentDataLocal.value.addresses.filter(address => {
  //   return address.value === checkoutPaymentDataLocal.value.deliveryAddress
  // })
})

const handleStripePayment = async() => {

  //console.log(cardElement)

  paymentProcessing.value =true

  const { paymentMethod,error } = await stripe.value.createPaymentMethod(

    'card',cardElement.value, {
      billing_details: {
        name: cardFormData.firstName + ' ' + cardFormData.lastName,
        email: cardFormData.email,
        address: {
          line1: cardFormData.address,
          city: cardFormData.city,
          
        },
      },

      //currency: 'PKR',
    })

  if(error){
    paymentProcessing.value = false
  }
  else{

    console.log(paymentMethod)

    //  console.log(cardFormData.value.phoneNumber)
    cardFormData.paymentMethod = paymentMethod.id

    axios.post('/reservations',cardFormData)
      .then(response => {

        paymentProcessing.value = false

        //  console.log(response)

        confirmationDataEmit.value = response.data.data

        // console.log(response.data)
        resetFormData() // Clear form data

        nextStep()

      })
      .catch(error =>{
        paymentProcessing.value = false,
        console.log(error)


      })



  }

  //nextStep() // Call the function to move to the next step
}

const handleCashOnDelivery = () => {

  paymentProcessing.value =true

  codFormData.paymentMethod = selectedPaymentMethod.value

  axios.post('api/reservations',codFormData)
    .then(response => {

      paymentProcessing.value = false
      console.log(response)

      confirmationDataEmit.value = response.data.data

      // console.log(response.data)
      //resetFormData() // Clear form data

      nextStep()

    })
    .catch(error =>{
      paymentProcessing.value = false,
      console.log(error)


    })

  //nextStep() // Call the function to move to the next step
}

const updateCartData = () => {
  emit('update:checkout-data', confirmationDataEmit.value)
}

const nextStep = () => {
  updateCartData()
  emit('update:currentStep', prop.currentStep ? prop.currentStep + 1 : 1)
}

watch(() => prop.currentStep, updateCartData)
</script>

<template>
  <VRow>
    <VCol
      cols="12"
      md="8"
    >
      <!-- 👉 Offers alert -->
      <VAlert
        color="success"
        variant="tonal"
        class="mb-4"
      >
        <template #prepend>
          <VIcon
            icon="tabler-bookmarks"
            size="34"
          />
        </template>
        <VAlertTitle class="text-success mb-3">
          Bank Offers
        </VAlertTitle>

        <p class="mb-1">
          - 10% Instant Discount on Bank of America Corp Bank Debit and Credit cards
        </p>
      </VAlert>

      <VTabs
        v-model="selectedPaymentMethod"
        class="v-tabs-pill"
        density="comfortable"
      >
        <VTab value="card">
          Card
        </VTab>
        <VTab value="cash-on-delivery">
          Cash on Delivery
        </VTab>
      </VTabs>

      <VWindow
        v-model="selectedPaymentMethod"
        class="mt-5"
        style="max-width: 600px;"
      >
        <VWindowItem value="card">
          <VForm class="mt-3">
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="cardFormData.startDate"
                  type="date"
                  label="Start Date"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="cardFormData.endDate"
                  type="date"
                  label="EndDate"
                  :disabled="paymentProcessing"
                />
              </VCol>
              
              <VCol cols="12">
                <AppTextField
                  v-model="cardFormData.firstName"
                  type="text"
                  label="First Name"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="cardFormData.lastName"
                  type="text"
                  label="Last Name"
                  :disabled="paymentProcessing"
                />
              </VCol>
         
             
             
              <VCol cols="12">
                <AppTextField
                  v-model="cardFormData.city"
                  type="text"
                  label="City"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="cardFormData.phoneNumber"
                  type="number"
                  label="Phone Number"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <div id="card-element" />
              </VCol>
              <VCol cols="12">
                <div id="card-error" />
              </VCol>

              
         

              <VCol cols="12">
                <div class="mt-4">
                  <VBtn
                    class="me-3"
                    :disabled="paymentProcessing"
                    @click="handleStripePayment"
                  >
                    {{ paymentProcessing ? 'Processing' : 'Pay Now' }}
                  </VBtn>
                  <VBtn
                    variant="tonal"
                    color="secondary"
                  >
                    Reset
                  </VBtn>
                </div>
              </VCol>
            </VRow>
          </VForm>
        </VWindowItem>

        <VWindowItem value="cash-on-delivery">
          <p class="text-base text-high-emphasis">
            Cash on Delivery is a type of payment method where the recipient make payment for the order at the time of delivery rather than in advance.
          </p>
          <VForm class="mt-3">
            <VRow>
              <VCol cols="12">
                <AppTextField
                  v-model="codFormData.startDate"
                  type="date"
                  label="Start Date"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="codFormData.endDate"
                  type="date"
                  label="EndDate"
                  :disabled="paymentProcessing"
                />
              </VCol>
              
              <VCol cols="12">
                <AppTextField
                  v-model="codFormData.firstName"
                  type="text"
                  label="First Name"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="codFormData.lastName"
                  type="text"
                  label="Last Name"
                  :disabled="paymentProcessing"
                />
              </VCol>
      
              <VCol cols="12">
                <AppTextField
                  v-model="codFormData.city"
                  type="text"
                  label="City"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <AppTextField
                  v-model="codFormData.phoneNumber"
                  type="number"
                  label="Phone Number"
                  :disabled="paymentProcessing"
                />
              </VCol>
              <VCol cols="12">
                <div class="mt-4">
                  <VBtn
                    class="me-3"
                    :disabled="paymentProcessing"
                    @click="handleCashOnDelivery"
                  >
                    {{ paymentProcessing ? 'Processing' : 'Pay On Arrival' }}
                  </VBtn> 
                  <VBtn
                    variant="tonal"
                    color="secondary"
                  >
                    Reset
                  </VBtn>
                </div>
              </VCol>
            </VRow>
          </VForm>
        </VWindowItem>
      </VWindow>
    </VCol>

    <VCol
      cols="12"
      md="4"
    >
      <VCard
        flat
        variant="outlined"
      >
        <VCardText>
          <h6 class="text-base font-weight-medium mb-4">
            Price Details
          </h6>

          <div class="d-flex justify-space-between text-base mb-2">
            <span class="text-high-emphasis">Order Total</span>
            <span>PKR{{ totalCost }}</span>
          </div>

          <div class="d-flex justify-space-between text-base">
            <span class="text-high-emphasis">Delivery Charges</span>
            <div v-if="checkoutPaymentDataLocal.deliverySpeed === 'free'">
              <span class="text-decoration-line-through text-disabled me-2">$5.00</span>
              <VChip
                color="success"
                label
              >
                Free
              </VChip>
            </div>
            <div v-else>
              <span>${{ checkoutPaymentDataLocal.deliveryCharges }}</span>
            </div>
          </div>
        </VCardText>

        <VDivider />
      </VCard>
    </VCol>
  </VRow>
</template>

<style>
/* Customize the appearance of the Stripe card element */
.stripe-card-element {
  padding: 10px;

  /* Apply your custom styles here */
  border: 1px solid #ccc;
  border-radius: 4px;

  /* ... other styles ... */
}
</style>
