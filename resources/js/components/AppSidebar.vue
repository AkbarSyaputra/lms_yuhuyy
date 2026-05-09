<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Users, LayoutGrid, GraduationCap, Tags } from 'lucide-vue-next';
import AppLogo from '@/components/AppLogo.vue';
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
import { dashboard } from '@/routes';
import { index as coursesIndex } from '@/routes/courses/index';
import { index as categoriesIndex } from '@/routes/categories/index';
import { index as usersIndex } from '@/routes/users/index';
import type { NavItem } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user as any);

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard.url(),
            icon: LayoutGrid,
        },
        {
            title: 'Courses',
            href: coursesIndex.url(),
            icon: GraduationCap,
        }
    ];

    const roles = user.value?.roles || [];
    const isAdmin = roles.some((r: any) => r.name === 'admin');

    if (isAdmin) {
        items.push({
            title: 'Categories',
            href: categoriesIndex.url(),
            icon: Tags,
        });
        items.push({
            title: 'Users',
            href: usersIndex.url(),
            icon: Users,
        });
    }

    return items;
});

const footerNavItems: NavItem[] = [
    // Removed external repo links for a cleaner LMS look
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard.url()">
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
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
