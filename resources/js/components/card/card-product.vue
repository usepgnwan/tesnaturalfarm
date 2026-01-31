<script lang="ts" setup> 
import { product } from '@/lib/interface';
import Storage from '@/lib/storage';
import { Icon } from '@iconify/vue';
import { PropType } from 'vue';
import { toast } from 'vue-sonner';
    const emit = defineEmits(['add-cart']);

    const props = defineProps({
        
        data: { type: Object as PropType<product>, default: () => ({}) },
            
    });

    const addProduct = () => {
        let cart = Storage.get("cart-detail", [])
        
        const index = cart.findIndex((item: any) => item.id === props.data.id);

        if (index !== -1) { 
                if(cart[index].total >= props.data.stock){
                    return toast.warning('Melebihi stok')
                }
                cart[index].total += 1; 
                cart[index].harga = cart[index].total * props.data?.harga
                console.log(cart)
        } else {
            // add new item
            cart.push({
                id : props.data?.id,
                total: 1,
                harga: props.data?.harga,
                data: props.data,
            });
        }

    
        Storage.set("cart-detail",cart)
        emit('add-cart');
    }
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
  
            <button class="bg-blue-400 p-2 text-white rounded-2xl w-full cursor-pointer" v-if="data.stock > 0" @click="addProduct">Tambah</button>
            <button class="bg-gray-200 p-2 text-gray-500 rounded-2xl w-full"  v-else>Habis</button>
        

    </div>
</template>