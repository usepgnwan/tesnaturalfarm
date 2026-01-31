<script lang="ts" setup>
import CardProduct from '@/components/card/card-product.vue';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounceValue, useGlobal } from '@/lib/global';
import post from '@/routes/post';
import { BreadcrumbItem } from '@/types'; 
import { onMounted, reactive, ref, toRaw, watch } from 'vue';
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import Storage from '@/lib/storage';
import { datacart } from '@/lib/interface';
import CardCard from '@/components/card/card-card.vue';
import { sum } from 'element-plus/es/components/table-v2/src/utils.mjs';
import { toast } from 'vue-sonner';

const {toRupiah} = useGlobal()
const props = withDefaults(
  defineProps<{
    data: any[]
  }>(),
  {
    data: () => [],
  }
)
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: post.index().url,
    },
];

let search = ref<String>('')
let per_page = ref<Number>(12)
const getValue = debounceValue( async (val: string, callback: (result: any) => void) => { 
    return callback(val);
}, 300);

watch(search,async (val) => { 
    const callbackBounce = (v:any)=>{ 
        router.get(post.index().url,
            { search:search.value, per_page: per_page.value },
            { preserveState: true, replace: true }
        );
    }
    getValue(val,callbackBounce)  
}); 
const datarow = {
    data : [], 
    diskon : 0,
    subtotal : 0,
    grandtotal : 0,
}
const dataForm = ref(datarow)

const datacart = ref<datacart[]>(Storage.get("cart-detail",[]))
const updatecart = () => {
        const cart = Storage.get("cart-detail",[])
        console.log(cart)
        datacart.value = cart;
        updateTotal()
}

const diskon = ref<String>("")
const updateTotal = () =>{
    let subtotal = datacart.value.reduce((sum,v)=> sum + Number(v.harga),0); 
    return dataForm.value = {...dataForm, data:toRaw(datacart.value), subtotal:subtotal, grandtotal : subtotal - Number(diskon.value ==""? 0 :diskon.value), diskon:diskon.value  }
}

onMounted(()=>{
    updateTotal()
})


watch(()=>diskon, ()=>{
    console.log('dd')
    updateTotal()
}, {
     deep: true
})

const loading = ref<Boolean>(false)
const sendData = () =>{ 
    loading.value = true;
    const cleanData = {
        data: JSON.parse(JSON.stringify(dataForm.value.data)), 
        discount: dataForm.value.diskon,
        subtotal: dataForm.value.subtotal,
        grandtotal: dataForm.value.grandtotal,
    }
     
    router.post(post.store(), cleanData, {
        onSuccess: () => {
            loading.value = false;
            console.log('Data berhasil dikirim')
            dataForm.value = {...dataForm.value,...datarow}
            Storage.clear("cart-detail")
            toast.success("transaksi success")
            datacart.value = []
        },
        onError: (errors) => {
            loading.value = false;
            console.error('Error:', errors)
        }
    })
}
</script>
<template>
    <Head title="Post" /> 
    <AppLayout :breadcrumbs="breadcrumbs"> 
             <section class="flex space-x-3 px-4 bg-gray-50">
                <div class="w-2/3  p-4 space-y-6">
                        <div class="relative">
                            <input  class="w-full p-2 border  rounded-2xl bg-white px-10" placeholder="Cari..." v-model="search"/>
                            <Icon icon="wordpress:search" class="absolute left-4 -translate-y-1/2 top-1/2 w-5 h-5"/>
                        </div>
                        <template v-if="data?.data.length>0">
                            <div class="grid grid-cols-3 gap-4">
                                <CardProduct  v-for="(value,key) in data?.data" :key="key" :data="value" @add-cart="updatecart" /> 
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex items-center min-h-36 justify-center w-full">
                                Product tidak tersedia
                            </div>
                        </template> 

                        <Pagination :total="data?.total"  :perPage="per_page" :currentPage="data?.current_page"/>
                </div>
                <div class="w-1/3 py-4 px-2">
                    <div class=" bg-white rounded-2xl border p-4 space-y-6  relative min-h-64">
                        
                        <p class="font-semibold">Total Produk ({{ datacart.length }})</p>
                        <div class="h-64 overflow-y-auto">
                            <CardCard v-for="(value,k) in datacart" :key="k" :data="value"/>
                        </div>
                        <div>
                            <p>Potongan harga</p>
                            <input type="number" class="border rounded-xl mt-2 p-1 w-full px-4" placeholder="diskon 10.000" v-model="diskon"/>
                        </div>
                        <div class="bg-gray-50 space-y-2.5 p-2">
                            <div class="flex justify-between">
                                <p>Subtotal :</p>
                                <p>Rp. {{  toRupiah(dataForm.subtotal) }}</p>
                            </div>
                            
                            <div class="flex justify-between  py-2 border-b-2 border-dashed">
                                <p>Diskon :</p>
                                <p>Rp. {{ toRupiah(dataForm.diskon) }} </p>
                            </div>
                            <div class="flex justify-between">
                                <p>Grand Total :</p>
                                <p>Rp. {{ toRupiah(dataForm.grandtotal) }}</p>
                            </div>
                        </div>
                         <button class="bg-blue-400 p-2 text-white rounded-2xl w-full" @click="sendData" :disabled="loading">
                            <span v-if="!loading"> Pesan Sekarang</span>
                            <span v-else>Procees</span>
                           </button>
                       
                        
                    </div>
                </div>
             </section>
    </AppLayout>  
</template>