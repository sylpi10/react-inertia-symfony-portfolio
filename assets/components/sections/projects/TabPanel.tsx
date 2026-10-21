import { ReactNode } from "react";
import type { TabId } from "./ProjectTabs";

export default function TabPanel({
    id,
    activeTab,
    className,
    children,
}: {
    id: TabId;
    activeTab: TabId;
    className?: string;
    children: ReactNode;
}) {
    return (
        <div
            role="tabpanel"
            id={`panel-${id}`}
            aria-labelledby={`tab-${id}`}
            hidden={activeTab !== id}
            tabIndex={0}
            className={`panel-container ${className ?? ""}`}
        >
            {children}
        </div>
    );
}
