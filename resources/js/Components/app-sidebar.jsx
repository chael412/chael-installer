import * as React from "react";
import {
    AudioWaveform,
    BookOpen,
    Bot,
    Command,
    Frame,
    GalleryVerticalEnd,
    LayoutDashboard,
    Map,
    PieChart,
    Settings2,
    SquareTerminal,
} from "lucide-react";

import { NavMain } from "@/components/nav-main";
import { NavProjects } from "@/components/nav-projects";
import { NavUser } from "@/components/nav-user";
import { TeamSwitcher } from "@/components/team-switcher";
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarRail,
} from "@/components/ui/sidebar";
import { usePage } from "@inertiajs/react";

export function AppSidebar({ ...props }) {
    const user = usePage().props.auth.user;
    const { url } = usePage();

    const data = {
        teams: [
            {
                name: "Yui",
                logo: GalleryVerticalEnd,
                plan: "Enterprise",
            },
        ],
        navMain: [
            {
                title: "Dashboard",
                url: route("dashboard"),
                icon: LayoutDashboard,
                isActive: url === route("dashboard", {}, false),
            },
        ],
    };

    return (
        <Sidebar collapsible="icon" {...props}>
            <SidebarHeader>
                <TeamSwitcher teams={data.teams} />
            </SidebarHeader>
            <SidebarContent>
                <NavMain items={data.navMain} />
            </SidebarContent>
            <SidebarFooter>
                <NavUser user={user} />
            </SidebarFooter>
            <SidebarRail />
        </Sidebar>
    );
}
