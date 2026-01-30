
<script lang="ts" setup>
import { Icon } from '@iconify/vue';
import { PropType } from 'vue';

        interface product {
            title : string
            description : string
            stock : number
            harga: number
            foto : string
        }
        const props = defineProps({
            
            data: { type: Object as PropType<product>, default: () => ({}) },
             
        });
</script>
<template>
    <div class="border p-4 space-y-2.5 bg-white rounded-2xl group"   >
             
        <div class="bg-gray-50 h-48 rounded-2xl">
            <div class=" w-full h-48  overflow-hidden" v-if="data.foto !== null">
                <img :src="data.foto" alt=""  class="w-full h-full object-cover object-center"> 
            </div>
            <div v-else class="w-full h-48 flex items-center justify-center">
                <Icon icon="material-symbols:image-outline" class="w-20 h-20 text-gray-100"/>
            </div>
        </div>
        <p class="line-clamp-1">{{ data.title }}  </p>
        <small>Stock : {{ data.stock }}</small>
        <h5 class="font-semibold">Rp.{{ data.harga }}</h5>
        <button class="bg-blue-400 p-2 text-white rounded-2xl w-full hidden group-hover:block">Tambah</button>

    </div>
</template>