<script setup>
import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faUser, faUsers, faSackDollar, faGaugeSimpleHigh, faBuildingColumns, faMapLocation, faChevronDown, faEllipsis  } from '@fortawesome/free-solid-svg-icons'
library.add(faUser, faUsers, faSackDollar, faGaugeSimpleHigh, faBuildingColumns, faMapLocation, faChevronDown, faEllipsis)

const props = defineProps({
    name: String,
    active: Boolean
})
</script>

<template>
    <font-awesome-icon
        :class="[
             active ? 'text-white' : 'text-teal-700', 'mr-2'
        ]"
        :icon="`fa-solid fa-${name}`"
        />
</template>
