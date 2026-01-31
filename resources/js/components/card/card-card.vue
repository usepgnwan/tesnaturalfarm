<script lang="ts" setup> 
import { useGlobal } from '@/lib/global';
import { datacart } from '@/lib/interface';
import Storage from '@/lib/storage';
import { Icon } from '@iconify/vue'; 
import { PropType, ref } from 'vue';
import { toast } from 'vue-sonner';

const emit = defineEmits(['add-cart']);

const props = defineProps({ 
    data: { type: Object as PropType<datacart>, default: () => ({}) }, 
});
const {toRupiah} = useGlobal()

     
const qty = ref<Number>(props.data.total) 

const increment = () => {
    qty.value = Number(qty.value) + 1 
    addCart(  false)
    
};

const decrement = () => {
    qty.value= Number(qty.value) - 1 
    addCart( true)
    
}; 
const addCart = (isdecrement : boolean) => {
    const qtyToAdd = qty.value;

    let cart = Storage.get("cart-detail", []);

    const index = cart.findIndex((item: any) => item.id === props.data.id);
   
    if (index !== -1) {
        if(isdecrement){
            cart[index].total -= 1;
            if(cart[index].total === 0){
                const newCart = cart.filter((item: any) => item.total > 0);  
                Storage.set("cart-detail", newCart);
                emit('add-cart'); 
                return;
            }
        }else{
            cart[index].total  = qtyToAdd;
            if(cart[index].total >= props.data?.data.stock){
                return toast.warning('Melebihi stok')
            }
        }
    }  
    
    Storage.set("cart-detail", cart);
    console.log(cart);
    toast.success('Sukses update cart pesanan')
    
    emit('add-cart');
    
};
 
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
                        <button class="border rounded-md px-2 bg-blue-500 text-white cursor-pointer" @click="decrement">-</button>
                        <div class="border rounded-md px-2 bg-gray-50">{{ data.total }}</div>
                        <button class="border rounded-md px-2 bg-blue-500 text-white cursor-pointer" @click="increment">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>