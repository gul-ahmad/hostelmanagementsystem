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
      <!-- 👉 Activity timeline -->
      <VCard title="User Activity Timeline">
        <VCardText>
          <VTimeline
            density="compact"
            align="start"
            truncate-line="both"
            class="v-timeline-density-compact"
          >
            <VTimelineItem
              dot-color="error"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center flex-wrap">
                <h4 class="text-base font-weight-semibold me-1">
                  12 Invoices have been paid
                </h4>
                <span class="text-sm text-disabled text-no-wrap">12 min ago</span>
              </div>
              <p class="mb-2">
                Invoices have been paid to the company
              </p>
              <div class="d-flex align-center mt-2">
                <VIcon
                  color="error"
                  icon="tabler-file"
                  size="18"
                  class="me-2"
                />
                <h6 class="font-weight-semibold text-sm">
                  Invoices.pdf
                </h6>
              </div>
            </VTimelineItem>

            <VTimelineItem
              dot-color="primary"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center flex-wrap">
                <h4 class="text-base font-weight-semibold me-1">
                  Meeting with john
                </h4>
                <span class="text-sm text-disabled text-no-wrap">45 min ago</span>
              </div>

              <p class="mb-1">
                React Project meeting with john @10:15am
              </p>

              <div class="d-flex align-center mt-3">
                <VAvatar
                  size="34"
                  class="me-2"
                  :image="avatar2"
                />
                <div>
                  <h6 class="text-sm font-weight-semibold mb-0">
                    John Doe (Client)
                  </h6>
                  <span>CEO of Kelly Group</span>
                </div>
              </div>
            </VTimelineItem>

            <VTimelineItem
              dot-color="info"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center flex-wrap">
                <h4 class="text-base font-weight-semibold me-1">
                  Create a new react project for client
                </h4>
                <span class="text-sm text-disabled text-no-wrap">2 day ago</span>
              </div>
              <p class="mb-0">
                Add files to new design folder
              </p>
            </VTimelineItem>

            <VTimelineItem
              dot-color="success"
              size="x-small"
            >
              <div class="d-flex justify-space-between align-center flex-wrap">
                <h4 class="text-base font-weight-semibold me-1">
                  12 Create invoices for client
                </h4>
                <span class="text-sm text-disabled text-no-wrap">5 day ago</span>
              </div>
              <p class="mb-0">
                Weekly review of freshly prepared design for our new app.
              </p>
            </VTimelineItem>
          </VTimeline>
        </VCardText>
      </VCard>
    </VCol>

    <VCol cols="12">
      <UserInvoiceTable />
    </VCol>
  </VRow>
</template>
