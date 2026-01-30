<script lang="ts" setup>
import CardProduct from '@/components/card/card-product.vue';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounceValue } from '@/lib/global';
import post from '@/routes/post';
import { BreadcrumbItem } from '@/types'; 
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';

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
</script>
<template>
    <Head title="Post" /> 
    <AppLayout :breadcrumbs="breadcrumbs"> 
             <section class="flex space-x-3 px-4 bg-gray-50">
                <div class="w-3/4  p-4 space-y-6">
                        <div class="relative">
                            <input class="w-full p-2 border  rounded-2xl bg-white px-10" placeholder="Cari..." v-model="search"/>
                            <Icon icon="wordpress:search" class="absolute left-4 -translate-y-1/2 top-1/2 w-5 h-5"/>
                        </div>
                        <template v-if="data?.data.length>0">
                            <div class="grid grid-cols-4 gap-4">
                                <CardProduct  v-for="(value,key) in data?.data" :key="key" :data="value" /> 
                            </div>
                        </template>
                        <template v-else>
                            <div class="flex items-center min-h-36 justify-center w-full">
                                Product tidak tersedia
                            </div>
                        </template> 

                        <Pagination :total="data?.total"  :perPage="per_page" :currentPage="data?.current_page"/>
                </div>
                <div class="w-1/4 py-4 px-2">
                    <div class=" bg-white rounded-2xl border p-4 space-y-6  relative min-h-64">
                        <p class="font-semibold">Total Produk (5)</p>
                        <div class="h-64 overflow-y-auto">
                            <div class="flex space-x-1.5 space-y-2.5"  v-for="value in Array(5)">
                                <div class="bg-gray-50 rounded-2xl w-12 h-12">

                                </div>
                                <div class="w-9/12">
                                    <p class="line-clamp-1">Lorem ipsum dolor, sit amet consectetur adipisicing elit.</p>
                                    <div class="flex justify-between">
                                        <p> Rp . 500.000</p>
                                        <div>
                                            <div class="flex gap-1">
                                                <button class="border rounded-md px-2 bg-blue-500 text-white cursor-pointer">-</button>
                                                <div class="border rounded-md px-2 bg-gray-50">1</div>
                                                <button class="border rounded-md px-2 bg-blue-500 text-white cursor-pointer">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <p>Potongan harga</p>
                            <input  class="border rounded-2xl p-1 w-full"/>
                        </div>
                        <div class="bg-gray-50 space-y-2.5 p-2">
                            <div class="flex justify-between">
                                <p>Subtotal :</p>
                                <p>Rp. 1500000</p>
                            </div>
                            <div class="flex justify-between  py-2 border-b-2 border-dashed">
                                <p>Diskon :</p>
                                <p>Rp. 30000</p>
                            </div>
                            <div class="flex justify-between">
                                <p>Grand Total :</p>
                                <p>Rp. 1200000</p>
                            </div>
                        </div>
                         <button class="bg-blue-400 p-2 text-white rounded-2xl w-full">Pesan Sekarang</button>
                       
                        
                    </div>
                </div>
             </section>
    </AppLayout>  
</template>