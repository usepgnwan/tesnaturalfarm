<script lang="ts" setup> 
import { useGlobal } from '@/lib/global';
import { datacart } from '@/lib/interface';
import Storage from '@/lib/storage';
import { Icon } from '@iconify/vue';
import { PropType } from 'vue';
import { toast } from 'vue-sonner';

const emit = defineEmits(['add-cart']);

const props = defineProps({ 
    data: { type: Object as PropType<datacart>, default: () => ({}) }, 
});
const {toRupiah} = useGlobal()
</script>
<template>
    <div class="flex space-x-1.5 space-y-2.5" >
       
        <div class="bg-gray-50 h-12 w-12 rounded-2xl">
            <div class=" w-full h-12  overflow-hidden" v-if="data.data.foto !== null">
                <img :src="data.data.foto" alt=""  class="w-full h-full object-cover object-center"> 
            </div>
            <div v-else class="w-full h-48 flex items-center justify-center">
                <Icon icon="material-symbols:image-outline" class="w-5 h-5 text-gray-100"/>
            </div>
        </div>
        <div class="w-9/12">
            <p class="line-clamp-1">{{ data.data.title }}</p>
            <div class="flex justify-between">
                <p> Rp . {{ toRupiah(data.harga) }}</p>
                <div>
                    <div class="flex gap-1">
                        <button class="border rounded-md px-2 bg-blue-500 text-white cursor-pointer">-</button>
                        <div class="border rounded-md px-2 bg-gray-50">{{ data.total }}</div>
                        <button class="border rounded-md px-2 bg-blue-500 text-white cursor-pointer">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>