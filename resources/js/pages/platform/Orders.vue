<script setup lang="ts">
import Button from '@/components/ui/button/Button.vue';
import Pagination from '@/components/ui/pagination/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { debounceValue, useGlobal } from '@/lib/global';
import product from '@/routes/product';
import { BreadcrumbItem } from '@/types';
import { Icon } from '@iconify/vue';
import { Head, router } from '@inertiajs/vue3';
import { reactive, ref, watch } from 'vue';
import  * as tb from '@/components/ui/table'
import { FormInstance, FormRules, UploadProps } from 'element-plus';
import { orders } from '@/routes';

const {toRupiah,formatDate} = useGlobal()
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
        title: 'History Orders',
        href: orders().url,
    },
];

let search = ref<String>('')
let per_page = ref<Number>(10)
const getValue = debounceValue( async (val: string, callback: (result: any) => void) => { 
    return callback(val);
}, 300);

watch(search,async (val) => { 
    const callbackBounce = (v:any)=> searchTo()
    getValue(val,callbackBounce)  
   
});

const searchTo = () =>{
    router.get(orders().url,
        { search:search.value, per_page: per_page.value },
        { preserveState: true, replace: true }
    );
}
const options = [
  {
    value: '10',
    label: '10',
  },
  {
    value: '20',
    label: '20',
  },
  {
    value: '50',
    label: '50',
  },
  {
    value: '100',
    label: '100',
  },
  
]

watch(per_page,async (val) => { 
     searchTo() 
});

  
</script>

<template>
    <Head title="Product" /> 
    <AppLayout :breadcrumbs="breadcrumbs"> 
            <section class="flex space-x-3 p-4  h-full "> 
               <div class="  p-4 w-full space-y-4">
                
                    <div class="flex justify-between">
                        <div>
                            <el-select v-model="per_page" placeholder="Select" class=" !w-28">
                                <el-option
                                v-for="item in options"
                                :key="item.value"
                                :label="item.label"
                                :value="item.value"
                                />
                            </el-select>
                        </div>
                        <div class="flex space-x-1.5">
                            <div class="relative">
                                    <input class="w-full p-2 border  rounded-2xl bg-white px-10" placeholder="Cari..." v-model="search"/>
                                    <Icon icon="wordpress:search" class="absolute left-4 -translate-y-1/2 top-1/2 w-5 h-5"/>
                            </div>
                             
                        </div>
                    </div>
                   <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default rounded-2xl">
                     <tb.Table>

                        <template #head>
                            <tr> 
                                <th scope="col" class="px-6 py-3 font-medium">
                                    ID Orders
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Sub Total
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Discount
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Grand Total
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Total Prodak
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    QTY
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Dibuat Tanggal
                                </th>
                            </tr>
                        </template>
                        <template #body>
                            <template v-if="data.data.length >0">
                                <template v-for="(item, index) in data.data" :key="index">

                                    <tr class="bg-neutral-primary border-default" >  
                                            <td class="px-6 py-4" >
                                                {{ item.no_order }}
                                            </td> 
                                            <td class="px-6 py-4">
                                                Rp. {{ toRupiah(item.subtotal) }}
                                            </td> 
                                            <td class="px-6 py-4">
                                                Rp. {{ toRupiah(item.discount) }}
                                            </td> 
                                            
                                            <td class="px-6 py-4"> Rp. {{ toRupiah(item.grandtotal) }} </td>
                                            
                                            <td class="px-6 py-4">{{ item.details.length }}</td>
                                            <td class="px-6 py-4">{{ item.details.reduce((sum, v)=> sum+v.qty ,0) }} </td>
                                            <td class="px-6 py-4">{{formatDate(item.created_at)}}</td>
                                    </tr>
                                    <tr class=" border-b ">
                                        <td class="px-6 py-4 " colspan="7">
                                            <div class="space-y-1.5 border rounded-2xl p-3">
                                                <p>Details Order</p> 
                                                <tb.Table> 
                                                    <template #head>
                                                        <tr> 
                                                            <th scope="col" class="px-6 py-3 font-medium">
                                                            Title
                                                            </th>
                                                            <th scope="col" class="px-6 py-3 font-medium">
                                                                Qty
                                                            </th>
                                                            <th scope="col" class="px-6 py-3 font-medium">
                                                                Subtotal
                                                            </th>
                                                            <th scope="col" class="px-6 py-3 font-medium">
                                                                SKu
                                                            </th> 
                                                        </tr>
                                                    </template>
                                                    <template #body> 
                                                        <tr class="bg-neutral-primary border-b border-default" v-for="(value, i) in item?.details" :key="i">  
                                                            <td class="px-6 py-4">
                                                                <div class="flex items-center space-x-2.5">
                                                                    <div class="bg-gray-50 h-20 w-20 rounded-2xl">
                                                                        <div class=" w-full h-20   overflow-hidden" v-if="value.product.foto !== null">
                                                                            <img :src="value.product.foto" alt=""  class="w-full h-full object-cover object-center"> 
                                                                        </div>
                                                                        <div v-else class="w-full h-20  flex items-center justify-center">
                                                                            <Icon icon="material-symbols:image-outline" class="w-20 h-20 text-gray-100"/>
                                                                        </div>
                                                                    </div>
                                                                    <p>{{ value.product.title }}</p>
                                                                </div>
                                                                
                                                            </td>
                                                            <td class="px-6 py-4">{{ value.qty }}</td>
                                                            <td class="px-6 py-4">{{ toRupiah(value.subtotal) }}</td>
                                                            <td class="px-6 py-4">{{ value.sku }}</td>
                                                        </tr>
                                                    </template>
                                                    </tb.Table>
                                            </div>
                                        </td> 
                                    </tr>
                                </template>
                                 
                            </template>
                            <template v-else>
                                <tr>
                                    <td colspan="5" class="p-4 text-center">Data not found</td>
                                </tr>
                            </template>
                            
                        </template>
                     </tb.Table>
                        
                    </div>

                    <div class="justify-between w-full flex items-center">
                        <p>Displaying <b>{{ per_page }}</b> Total <b>{{ data?.total }}</b> Entries</p>
                         <Pagination :total="data?.total"  :perPage="per_page" :currentPage="data?.current_page"/>
                    </div>
               </div> 
            </section>
    </AppLayout>
</template>
