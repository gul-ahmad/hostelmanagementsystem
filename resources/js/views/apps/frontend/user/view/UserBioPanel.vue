<script setup>
import {
  avatarText,
  kFormatter,
} from '@core/utils/formatters'

import { useUserListStore } from '@/views/apps/frontend/user/useUserListStore'

const props = defineProps({
  userData: {
    type: Object,
    required: true,
  },
})


const emit = defineEmits([
  'updateUserInfo',
])

const successMessage = ref('')
const userListStore = useUserListStore()

const updateUser =userDataUpdated =>{

  userListStore.updateFrontEndUser(userDataUpdated)
    .then(success =>{

      //emit the event to parent to display the latest user info

      emit('updateUserInfo')

      successMessage.value = success.text

      console.log(successMessage.value)

      setTimeout(()=>{

        successMessage.value =''

      },5000)

    
    }).catch( error =>{

      console.log(error)

    })

 

}

const standardPlan = {
  plan: 'Standard',
  price: 99,
  benefits: [
    '10 Users',
    'Up to 10GB storage',
    'Basic Support',
  ],
}

const isUserInfoEditDialogVisible = ref(false)
const isUpgradePlanDialogVisible = ref(false)

const resolveUserStatusVariant = stat => {
  if (stat === 'pending')
    return 'warning'
  if (stat === 'active')
    return 'success'
  if (stat === 'inactive')
    return 'secondary'
  
  return 'primary'
}

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
      icon: 'tabler-server-2',
    }
  
  return {
    color: 'primary',
    icon: 'tabler-user',
  }
}
</script>

<template>
  <VRow>
    <!-- SECTION User Details -->
    <VCol cols="12">
      <VAlert
        v-if="successMessage"
        transition="fade"
        :value="true"
        type="success"
        dismissible
      >
        {{ successMessage }}
      </VAlert>
      <VCard v-if="props.userData">
        <!-- 👉 Details -->
        <VCardText>
          <p class="text-sm text-uppercase text-disabled">
            Account Details
          </p>

          <!-- 👉 User Details list -->
          <VList class="card-list mt-2">
            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Name:
                  <span class="text-body-2">
                    {{ props.userData.name }}
                  </span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Email
                  <span class="text-body-2">{{ props.userData.email }}</span>
                </h6>
              </VListItemTitle>
            </VListItem>

            <VListItem>
              <VListItemTitle>
                <h6 class="text-base font-weight-semibold">
                  Address:

                  <VChip
                    label
                    size="small"
                    
                    class="text-capitalize"
                  >
                    {{ props.userData.address }}
                  </VChip>
                </h6>
              </VListItemTitle>
            </VListItem>
          </VList>
        </VCardText>

        <!-- 👉 Edit and Suspend button -->
        <VCardText class="d-flex justify-center">
          <VBtn
            variant="elevated"
            class="me-3"
            @click="isUserInfoEditDialogVisible = true"
          >
            Edit
          </VBtn>
          <VBtn
            variant="tonal"
            color="error"
          >
            Suspend
          </VBtn>
        </VCardText>
      </VCard>
    </VCol>
    <!-- !SECTION -->
  </VRow>

  <!-- 👉 Edit user info dialog -->
  <FrontEndUserInfoEditDialogue
    v-model:isDialogVisible="isUserInfoEditDialogVisible"
    :user-data="props.userData"
    @update-user="updateUser"
  />

  <!-- 👉 Upgrade plan dialog -->
</template>

<style lang="scss" scoped>
.card-list {
  --v-card-list-gap: 0.7rem;
}

.text-capitalize {
  text-transform: capitalize !important;
}
</style>
