<script setup>
import { useUserListStore } from '@/views/apps/frontend/user/useUserListStore'

import { useAuthStore } from '@/views/apps/frontend/rooms/useAuthStore'

import UserBioPanel from '@/views/apps/frontend/user/view/UserBioPanel.vue'
import UserTabBillingsPlans from '@/views/apps/frontend/user/view/UserTabBillingsPlans.vue'
import UserTabConnections from '@/views/apps/frontend/user/view/UserTabConnections.vue'
import UserTabNotifications from '@/views/apps/frontend/user/view/UserTabNotifications.vue'
import UserTabOverview from '@/views/apps/frontend/user/view/UserTabOverview.vue'
import UserTabSecurity from '@/views/apps/frontend/user/view/UserTabSecurity.vue'

const userListStore = useUserListStore()
const route = useRoute()
const userData = ref()
const userTab = ref(null)

const id = ref('')


const authStore = useAuthStore() // Access the Pinia store instance
const isAuthenticated = computed(() => authStore.isAuthenticated)

const userId = computed(() => authStore.id)

id.value  = userId.value

console.log(id.value)



const tabs = [
  {
    icon: 'tabler-user-check',
    title: 'Reservations',
  },
  {
    icon: 'tabler-lock',
    title: 'Security',
  },
  {
    icon: 'tabler-currency-dollar',
    title: 'Billing & Plan',
  },
  {
    icon: 'tabler-bell',
    title: 'Notifications',
  },
  {
    icon: 'tabler-link',
    title: 'Connections',
  },
]

userListStore.fetchUser(Number(route.params.id)).then(response => {
  userData.value = response.data

 
})
</script>

<template>
  <VRow v-if="userData">
    <VCol
      cols="12"
      md="5"
      lg="4"
    >
      <UserBioPanel
        :user-data="userData"
        @room-image="addNewRoom"
      />
    </VCol>

    <VCol
      cols="12"
      md="7"
      lg="8"
    >
      <VTabs
        v-model="userTab"
        class="v-tabs-pill"
      >
        <VTab
          v-for="tab in tabs"
          :key="tab.icon"
        >
          <VIcon
            :size="18"
            :icon="tab.icon"
            class="me-1"
          />
          <span>{{ tab.title }}</span>
        </VTab>
      </VTabs>

      <VWindow
        v-model="userTab"
        class="mt-6 disable-tab-transition"
        :touch="false"
      >
        <VWindowItem>
          <UserTabOverview :user-data="userData" />
        </VWindowItem>

        <VWindowItem>
          <UserTabSecurity />
        </VWindowItem>

        <VWindowItem>
          <UserTabBillingsPlans />
        </VWindowItem>

        <VWindowItem>
          <UserTabNotifications />
        </VWindowItem>

        <VWindowItem>
          <UserTabConnections />
        </VWindowItem>
      </VWindow>
    </VCol>
  </VRow>
</template>
