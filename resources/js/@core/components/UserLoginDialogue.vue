<script setup>
import { VForm } from 'vuetify/components'
import { useAppAbility } from '@/plugins/casl/useAppAbility'
import AuthProvider from '@/views/pages/authentication/AuthProvider.vue'
import axios from '@axios'
import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
import authV2MaskDark from '@images/pages/misc-mask-dark.png'
import authV2MaskLight from '@images/pages/misc-mask-light.png'
import { useRoomListStore } from '@/views/apps/frontend/rooms/useRoomListStore'
import { useAuthStore } from '@/views/apps/frontend/rooms/useAuthStore'
import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
import { themeConfig } from '@themeConfig'
import {
  emailValidator,
  requiredValidator,
} from '@validators'
import { ref } from 'vue'
import UserRegistrationDialogue from './UserRegistrationDialogue.vue'

const props = defineProps({
  isDialogVisible: {
    type: Boolean,
    required: true,
  },
  roomId :{
    type: Object,
    required:true,
  },
})

const emit = defineEmits([
  'update:modelValue',
  'update:isDialogVisible',
  'authenticated',

])

const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

const authStore = useAuthStore() // Access the Pinia store instance

// const isAuthenticated = computed(() => {
//   return authStore.isAuthenticated // Get the authentication status from the store
// })



const isPasswordVisible = ref(false)
const email = ref('')
const password = ref('')
const rememberMe = ref(false)

const route =useRoute()
const router =useRouter()
const ability = useAppAbility()


const errors =ref({
  email:undefined,
  password:undefined,

})

const refVForm =ref()


const roomListStore = useRoomListStore()




const login = () => {
  axios.post('api/login', {
    email: email.value,
    password: password.value,
  }).then(r => {
    const { accessToken, userData,userAbilities,userDetails } = r.data

    console.log(r.data)

    console.log(userData)

    console.log(userAbilities)

    // localStorage.setItem('userAbilities', JSON.stringify(userAbilities))
    //  ability.update(userAbilities)
    localStorage.setItem('userData', JSON.stringify(userData))
    localStorage.setItem('userAbilities', JSON.stringify(userAbilities))

    // locaStorage.setItem('userDetails',JSON.stringify(userDetails))
    ability.update(userAbilities)
    localStorage.setItem('accessToken', JSON.stringify(accessToken))

    // Redirect to `to` query if exist or redirect to index route
    // router.replace(route.query.to ? String(route.query.to) : '/')


    emit('authenticated', true)
    
    // authStore.setAuthentication(true)
    // const isAuthenticated = authStore.setAuthentication()
    authStore.setAuthentication(userDetails)

    console.log(localStorage.getItem('accessToken'))
  }).catch(e => {
   

    const { errors: formErrors } = e.response.data

    errors.value = formErrors
    console.error(e.response.data)
  })
}

const onSubmit = () => {

  
  
 
  refVForm.value?.validate().then(({ valid:isValid }) => {
   
    if(isValid)
      login()
  })
}

const onFormReset = () => {
  // userData.value = structuredClone(toRaw(props.userData))
  emit('update:isDialogVisible', false)
}

const dialogModelValueUpdate = val => {
  emit('update:isDialogVisible', val)
}

const isUserRegistrationDialogueVisible = ref(false)

const toggleRegistrationDialogue = () => {
  //alert('llll')
  isUserRegistrationDialogueVisible.value =true
  console.log(isUserRegistrationDialogueVisible.value)
  dialogModelValueUpdate(true)

}
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 500"
    :model-value="props.isDialogVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VRow>
      <VCol
        class="d-flex align-center justify-center"
      >
        <VCard
          flat
          :max-width="500"
          class="mt-12 mt-sm-0 pa-4"
        >
          <VCardText>
            <h5 class="text-h5 font-weight-semibold mb-1">
              Sign In
            </h5>
          </VCardText>
    
          <VCardText>
            <VForm
              ref="refVForm"
              @submit.prevent="onSubmit"
            >
              <VRow>
                <!-- email -->
                <VCol cols="12">
                  <VTextField
                    v-model="email"
                    label="Email"
                    type="email"
                    :rules="[requiredValidator, emailValidator]"
                    :error-messages="errors.email"
                  />
                </VCol>

                <!-- password -->
                <VCol cols="12">
                  <VTextField
                    v-model="password"
                    label="Password"
                    :rules="[requiredValidator]"
                    :error-messages="errors.password"
                    :type="isPasswordVisible ? 'text' : 'password'"
                    :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                  />

                  <div class="d-flex align-center flex-wrap justify-space-between mt-2 mb-4">
                    <VCheckbox
                      v-model="rememberMe"
                      label="Remember me"
                    />
                    <a
                      class="text-primary ms-2 mb-1"
                      href="#"
                    >
                      Forgot Password?
                    </a>
                  </div>

                  <VBtn
                    block
                    type="submit"
                  >
                    Login
                  </VBtn>
                </VCol>

                <!-- create account -->
                <VCol
                  cols="12"
                  class="text-center"
                >
                  <span>New on our platform?</span>
                  <VBtn
                    variant="elevated"
                    class="me-3"
                    @click="toggleRegistrationDialogue"
                  >
                    Create An Account
                  </VBtn>
                </VCol>
                <VCol
                  cols="12"
                  class="d-flex align-center"
                >
                  <VDivider />
                  <span class="mx-4">or</span>
                  <VDivider />
                </VCol>

                <!-- auth providers -->
                <VCol
                  cols="12"
                  class="text-center"
                >
                  <AuthProvider />
                </VCol>
              </VRow>
            </VForm>
          </VCardText>
        </VCard>
      </VCol>
    </VRow>
  </VDialog>
  <!-- 👉 Edit user info dialog -->
  <UserRegistrationDialogue
    v-model:isDialogVisible="isUserRegistrationDialogueVisible"
    :room-id="props.roomId"
  />
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth.scss";
</style>

<route lang="yaml">
meta:
  layout: blank
  action: read
  subject: Auth
  redirectIfLoggedIn: true
</route>
