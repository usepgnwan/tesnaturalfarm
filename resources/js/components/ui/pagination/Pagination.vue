<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import {
  PaginationRoot,
  PaginationList,
  PaginationListItem,
  PaginationPrev,
  PaginationNext,
  PaginationFirst,
  PaginationLast,
  PaginationEllipsis,
} from 'reka-ui'

const props = defineProps<{
  total: number
  perPage: number
  currentPage: number
}>()

const goToPage = (page: number) => {
  router.get(
    window.location.pathname,
    { page },
    { preserveScroll: true, preserveState: true }
  )
}
</script>

<template>
  <PaginationRoot
    :total="total"
    :items-per-page="perPage"
    :default-page="currentPage"
    :sibling-count="1"
    show-edges
  >
    <PaginationList v-slot="{ items }" class="flex items-center gap-1">
      <PaginationFirst
        class="btn"
        @click="goToPage(1)"
      >
        <Icon icon="radix-icons:double-arrow-left" />
      </PaginationFirst>

      <PaginationPrev
        class="btn"
        @click="goToPage(Math.max(currentPage - 1, 1))"
      >
        <Icon icon="radix-icons:chevron-left" />
      </PaginationPrev>

      <template v-for="(page, i) in items" :key="i">
        <PaginationListItem
          v-if="page.type === 'page'"
          :value="page.value"
          class="w-9 h-9 rounded-lg border text-sm flex items-center justify-center
                     data-[selected]:bg-primary
                    data-[selected]:text-white
                    data-[selected]:border-primary
                    data-[selected]:shadow
                 hover:bg-white transition"
          @click="goToPage(page.value)"
        >
          {{ page.value }}
        </PaginationListItem>

        <PaginationEllipsis
          v-else
          class="w-9 h-9 flex items-center justify-center"
        >
          …
        </PaginationEllipsis>
      </template>

      <PaginationNext
        class="btn"
        @click="goToPage(Math.min(currentPage + 1, Math.ceil(total / perPage)))"
      >
        <Icon icon="radix-icons:chevron-right" />
      </PaginationNext>

      <PaginationLast
        class="btn"
        @click="goToPage(Math.ceil(total / perPage))"
      >
        <Icon icon="radix-icons:double-arrow-right" />
      </PaginationLast>
    </PaginationList>
  </PaginationRoot>
</template>
