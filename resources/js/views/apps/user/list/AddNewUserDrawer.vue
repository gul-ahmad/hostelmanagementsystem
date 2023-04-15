<script setup>
import {
  emailValidator,
  requiredValidator,
} from '@validators'
import { PerfectScrollbar } from 'vue3-perfect-scrollbar'

const props = defineProps({
  isDrawerOpen: {
    type: Boolean,

    required: true,
  },
  selectedUser :{
    type:Object,
    default:null,
  },
  isEditMode:{
    type:Boolean,

    //required:true,
  },
 

})


const emit = defineEmits([
  'update:isDrawerOpen',
  'userData',
  'userEdit',
])

const isFormValid = ref(false)
const refForm = ref()
const name = ref('')
const email = ref('')
const password = ref('')
const confirm_password = ref('')

// const contact = ref('')
// const role = ref()
// const plan = ref()
// const status = ref()

// 👉 drawer close
const closeNavigationDrawer = () => {
  emit('update:isDrawerOpen', false)
  nextTick(() => {
    refForm.value?.reset()
    refForm.value?.resetValidation()
  })
}

const onSubmit = () => {
  refForm.value?.validate().then(({ valid }) => {
    if (valid) {
      const userData = {
        name: name.value,
        email: email.value,
        password: password.value,
        confirm_password: confirm_password.value,
      }

      if (props.isEditMode) {
        // Editing an existing user
        userData.id = props.selectedUser.id
        emit('userEdit', userData)
      } else {
        // Adding a new user
        emit('userData', userData)
      }
      emit('update:isDrawerOpen', false)
      nextTick(() => {
        refForm.value?.reset()
        refForm.value?.resetValidation()
      })
    }
  })
}

const handleDrawerModelValueUpdate = val => {
  emit('update:isDrawerOpen', val)
}

///console.log(props.selectedUser)
watch(() => props.selectedUser, selectedUser => {
  //console.log(props.selectedUser)
  if (selectedUser) {
    name.value = selectedUser.name

    // email.value = selectedUser.email
    // password.value = selectedUser.password
    // confirm_password.value = selectedUser.confirm_password
  } else {
    name.value = ''
    email.value = ''
    password.value = ''
    confirm_password.value = ''
  }
})
</script>

<template>
  <VNavigationDrawer
    temporary
    :width="400"
    location="end"
    class="scrollable-content"
    :model-value="props.isDrawerOpen"
    @update:model-value="handleDrawerModelValueUpdate"
  >
    <!-- 👉 Title -->
    <div class="d-flex align-center pa-6 pb-1">
      <h6 class="text-h6">
        <!-- Add User -->
        {{ props.isEditMode ? 'Edit User' : 'Add New User' }}
      </h6>

      <VSpacer />

      <!-- 👉 Close btn -->
      <VBtn
        variant="tonal"
        color="default"
        icon
        size="32"
        class="rounded"
        @click="closeNavigationDrawer"
      >
        <VIcon
          size="18"
          icon="tabler-x"
        />
      </VBTn>
    </div>

    <PerfectScrollbar :options="{ wheelPropagation: false }">
      <VCard flat>
        <VCardText>
          <!-- 👉 Form -->
          <VForm
            ref="refForm"
            v-model="isFormValid"
            @submit.prevent="onSubmit"
          >
            <VRow>
              <!-- 👉 Full name -->
              <VCol cols="12">
                <VTextField
                  v-model="name"
                  :rules="[requiredValidator]"
                  label="Full Name"
                />
                <input
                  type="hidden"
                  name="_method"
                  :value="props.isEditMode ? 'PATCH' : 'POST'"
                >
              </VCol>

              <!-- 👉 Email -->
              <VCol cols="12">
                <VTextField
                  v-if="!props.isEditMode"
                  v-model="email"
                  :rules="[requiredValidator, emailValidator]"
                  label="Email"
                />
              </VCol>

              <!-- 👉 password -->
              <VCol cols="12">
                <VTextField
                  v-if="!props.isEditMode"
                  v-model="password"
                  :rules="[requiredValidator]"
                  label="password"
                />
              </VCol>

              <!-- 👉 confirm_password -->
              <VCol cols="12">
                <VTextField
                  v-if="!props.isEditMode"
                  v-model="confirm_password"
                  :rules="[requiredValidator]"
                  label="confirm_password"
                />
              </VCol>
              <!-- 👉 Submit and Cancel -->
              <VCol cols="12">
                <VBtn
                  type="submit"
                  class="me-3"
                >
                  {{ props.isEditMode? ' Update' : 'Submit' }}
                </VBtn>
                <VBtn
                  type="reset"
                  variant="tonal"
                  color="secondary"
                  @click="closeNavigationDrawer"
                >
                  Cancel
                </VBtn>
              </VCol>
            </VRow>
          </VForm>
        </VCardText>
      </VCard>
    </PerfectScrollbar>
  </VNavigationDrawer>
</template>
