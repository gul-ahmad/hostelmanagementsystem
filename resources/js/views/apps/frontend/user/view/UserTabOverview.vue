<script setup>
import UserInvoiceTable from './UserInvoiceTable.vue'

// Images
import avatar2 from '@images/avatars/avatar-2.png'
import figma from '@images/icons/project-icons/figma.png'
import html5 from '@images/icons/project-icons/html5.png'
import python from '@images/icons/project-icons/python.png'
import react from '@images/icons/project-icons/react.png'
import sketch from '@images/icons/project-icons/sketch.png'
import vue from '@images/icons/project-icons/vue.png'
import xamarin from '@images/icons/project-icons/xamarin.png'

const props = defineProps({
  userData: {
    type: Object,
    required: true,
  },
})

const formatDate = dateString => {
  const date = new Date(dateString)
  const options = { year: 'numeric', month: 'long', day: 'numeric' }
  
  return date.toLocaleDateString(undefined, options)
}

const resolveUserProgressVariant = progress => {
  if (progress <= 25)
    return 'error'
  if (progress > 25 && progress <= 50)
    return 'warning'
  if (progress > 50 && progress <= 75)
    return 'primary'
  if (progress > 75 && progress <= 100)
    return 'success'
  
  return 'secondary'
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard title="Your Reservations">
        <VDivider />
        <VTable class="text-no-wrap">
          <thead>
            <tr>
              <th scope="col">
                PRICE
              </th>
              <th scope="col">
                START DATE
              </th>
              <th scope="col">
                END DATE
              </th>
              <th scope="col">
                WIFI PASSWORD
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="reservation in props.userData.reservations"
              :key="reservation.id"
              style="height: 3.75rem;"
            >
              <td>
                <div class="d-flex align-center">
                  <div>
                    <p class="text-base mb-0">
                      {{ reservation.price }}
                    </p>
                  </div>
                </div>
              </td>
            
              
              <td class="text-medium-emphasis">
                {{ formatDate(reservation.start_date) }}
              </td>
              <td class="text-medium-emphasis">
                {{ formatDate(reservation.end_date) }}
              </td>
              <td class="text-medium-emphasis">
                {{ reservation.wifi_password }}
              </td>
            </tr>
          </tbody>
        </VTable>
      </VCard>
    </VCol>


    <VCol cols="12">
      <UserInvoiceTable />
    </VCol>
  </VRow>
</template>
