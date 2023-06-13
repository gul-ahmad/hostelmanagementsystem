<script setup>
const props = defineProps({
  selectedRoomId: {
    type: Number,
    required: true,
  },
  isDeleteDialogueVisible: {
    type: Boolean,
    required: true,
  },
})

const emit = defineEmits([
  'update:modelValue',
  'deleteRoomRecord',
  'update:isDeleteDialogueVisible',
])

const roomData = ref(structuredClone(toRef(props.selectedRoomId)))

//const isUseAsBillingAddress = ref(false)

watch(props, () => {
  roomData.value = structuredClone(toRef(props.selectedRoomId))
})

const onFormSubmit = () => {
  emit('update:modelValue', false)
  emit('update:isDeleteDialogueVisible',false)
  emit('deleteRoomRecord', props.selectedRoomId)
}

const onFormReset = () => {
  roomData.value = structuredClone(toRaw(props.selectedRoomId))
  emit('update:isDeleteDialogueVisible', false)
}

const dialogModelValueUpdate = val => {
  emit('update:isDeleteDialogueVisible', val)
}
</script>

<template>
  <VDialog
    :width="$vuetify.display.smAndDown ? 'auto' : 400"
    :model-value="props.isDeleteDialogueVisible"
    @update:model-value="dialogModelValueUpdate"
  >
    <!-- Dialog close btn -->
    <DialogCloseBtn @click="dialogModelValueUpdate(false)" />

    <VCard class="pa-sm-14 pa-5">
      <VCardItem class="text-center">
        <VCardTitle class="text-h5 mb-3">
          Delete Room Record 
        </VCardTitle>
        <p class="mb-0">
          Once deleted cannot be reverted back.
        </p>
      </VCardItem>

      <VCardText>
        <!-- 👉 Form -->
        <VForm
          class="mt-6"
          @submit.prevent="onFormSubmit"
        >
          <VRow>
            <!-- 👉 Submit and Cancel -->
            <VCol
              cols="12"
              class="d-flex flex-wrap justify-center gap-4"
            >
              <VBtn
                type="submit"
                color="red"
                variant="tonal"
              >
                Delete
              </VBtn>

              <VBtn
                color="secondary"
                variant="tonal"
                @click="onFormReset"
              >
                Cancel
              </VBtn>
            </VCol>
          </VRow>
        </VForm>
      </VCardText>
    </VCard>
  </VDialog>
</template>
