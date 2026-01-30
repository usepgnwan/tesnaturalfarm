<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { BookOpen, Box, Folder, HistoryIcon, LayoutGrid, ShoppingCart } from 'lucide-vue-next';
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard, orders } from '@/routes';
import { type NavItem } from '@/types';
import AppLogo from './AppLogo.vue';
import post from '@/routes/post';
import product from '@/routes/product';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
        icon: LayoutGrid,
    },
    {
        title: 'Product',
        href: product.index(),
        icon: Box,
    },
    {
        title: 'Post',
        href: post.index(),
        icon: ShoppingCart,
    },
    {
        title: 'History Orders',
        href: orders(),
        icon: HistoryIcon,
    },
];

 
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter> 
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
