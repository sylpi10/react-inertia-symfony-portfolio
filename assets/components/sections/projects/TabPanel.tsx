import { ReactNode } from "react";

const TABS = [
    { id: "description", label: "Description" },
    { id: "preview", label: "Aperçu" },
    { id: "audit", label: "Audit" },
] as const;
type TabId = (typeof TABS)[number]["id"];

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
