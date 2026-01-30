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
        title: 'Product',
        href: product.index().url,
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
    router.get(product.index().url,
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

 
const showModal = ref(false)
const ruleForm = reactive({ 
  title: '',
  stock: '',
  description: '',
  foto:    null as File | null,
  harga: '',
})

const rules = reactive<FormRules<typeof ruleForm>>({ 
  title: [{ required:true, trigger: 'blur' }],
  stock: [{ required:true, trigger: 'blur' }],
  harga: [{ required:true, trigger: 'blur' }],
})
const ruleFormRef = ref<FormInstance>()
 const submitForm = (formEl: FormInstance | undefined) => {
  if (!formEl) return
  formEl.validate((valid) => {
    if (valid) {
       router.post(product.store(), ruleForm, {
            forceFormData: true, 
            onSuccess: () => {
                formEl?.resetFields()
                showModal.value = false
            },
        })
    } else {
      console.log('error submit!')
    }
  })
}

const resetForm = (formEl: FormInstance | undefined) => {  
  if (!formEl) return
  showModal.value = false
  formEl.resetFields()
}

const handleUpload: UploadProps['onChange'] = (file) => {
  ruleForm.foto = file.raw!
}
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
                            <Button class="rounded-2xl bg-blue-800 hover:bg-blue-900" @click="showModal = true">Tambah</Button> 
                        </div>
                    </div>
                   <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default rounded-2xl">
                     <tb.Table>

                        <template #head>
                            <tr>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    .::.
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Title
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Stock
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Description
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Foto
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Harga
                                </th>
                                <th scope="col" class="px-6 py-3 font-medium">
                                    Dibuat Tanggal
                                </th>
                            </tr>
                        </template>
                        <template #body>
                            <template v-if="data.data.length >0">
                                <tr class="bg-neutral-primary border-b border-default" v-for="(item, index) in data.data" :key="index">
                                        
                                        <td class="px-6 py-4">
                                         
                                            {{ (data.current_page - 1) * per_page + (index + 1) }}
                                        </td> 
                                        <td class="px-6 py-4">
                                            {{ item.title }}
                                        </td> 
                                        <td class="px-6 py-4">
                                            {{ item.stock }}
                                        </td> 
                                        <td class="px-6 py-4">
                                            {{ item.description }}
                                        </td> 
                                        <td class="px-6 py-4"> 
                                              <div class="bg-gray-50 h-28 w-28 rounded-2xl">
                                                    <div class=" w-full h-28   overflow-hidden" v-if="item.foto !== null">
                                                        <img :src="item.foto" alt=""  class="w-full h-full object-cover object-center"> 
                                                    </div>
                                                    <div v-else class="w-full h-28  flex items-center justify-center">
                                                        <Icon icon="material-symbols:image-outline" class="w-20 h-20 text-gray-100"/>
                                                    </div>
                                                </div>
                                        </td> 
                                        <td class="px-6 py-4"> Rp. {{ toRupiah(item.harga) }} </td>
                                        <td class="px-6 py-4">
                                            {{ formatDate(item.created_at) }}
                                        </td> 
                                </tr>
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
               
            <el-dialog
                v-model="showModal"
                title="Add Product"
                width="500"   
            >
                <div class="p-4">
                     <el-form
                            ref="ruleFormRef"
                            style="max-width: 600px"
                            :model="ruleForm"
                            status-icon
                            :rules="rules"
                            label-width="auto"
                            class="demo-ruleForm"
                        >
                            
                           
                            <el-form-item label="Title" prop="title" >
                                <el-input v-model.number="ruleForm.title" />
                            </el-form-item>
                            
                            <el-form-item label="Stock" prop="stock" >
                                <el-input v-model.number="ruleForm.stock"  type="number"/>
                            </el-form-item>
                            
                            <el-form-item label="Harga" prop="harga" >
                                <el-input v-model.number="ruleForm.harga" type="number"/>
                            </el-form-item>
                            <el-form-item label="Deskripsi" prop="description" >
                                <el-input v-model.number="ruleForm.description" />
                            </el-form-item>
                            <el-form-item label="Foto" prop="photo">
                                <el-upload
                                    class="w-full"
                                    drag
                                    :auto-upload="false"
                                    :limit="1"
                                    accept="image/*"
                                    :on-change="handleUpload"
                                >
                                    <el-icon><UploadFilled /></el-icon>
                                    <div class="el-upload__text">
                                    Drop foto di sini atau <em>klik upload</em>
                                    </div>
                                </el-upload>
                                </el-form-item>
                            
 
                        </el-form>
                </div>
                <template #footer>
                <div class="dialog-footer">
                    <el-button @click="resetForm(ruleFormRef)">Cancel</el-button>
                    <el-button type="primary"  @click="submitForm(ruleFormRef)">
                        Simpan
                    </el-button>
                </div>
                </template>
            </el-dialog>
            </section>
    </AppLayout>
</template>
