// onglets du projet (modèle WAI-ARIA « tabs ») : un seul onglet atteignable
// avec Tab, les flèches / Début / Fin passent d'un onglet à l'autre. Tous les

import { useRef, useState } from "react";
import { Audience } from "../../../types/audience";
import { ProjectDetailsProps } from "../../../types/projects";
import ProjectInfosDetails from "./ProjectInfosDetails";
import TabPanel from "./TabPanel";
import ProjectPreview from "./ProjectPreview";
import ProjectPerf from "./ProjectPerf";

const TABS = [
    { id: "description", label: "Description" },
    { id: "preview", label: "Aperçu" },
    { id: "audit", label: "Audit" },
] as const;
type TabId = (typeof TABS)[number]["id"];

// panneaux restent dans le HTML (masqués par hidden) : indexés par Google
export default function ProjectTabs({
    audience,
    project,
}: {
    audience: Audience;
    project: ProjectDetailsProps;
}) {
    // l'onglet audit seulement si un audit a été fait
    const tabs = TABS.filter((tab) => tab.id !== "audit" || project.auditMade);
    const [activeTab, setActiveTab] = useState<TabId>("description");
    const tabRefs = useRef<Partial<Record<TabId, HTMLButtonElement | null>>>(
        {},
    );

    const onKeyDown = (e: React.KeyboardEvent<HTMLDivElement>) => {
        const i = tabs.findIndex((tab) => tab.id === activeTab);
        let nextIndex: number | null = null;
        if (e.key === "ArrowRight") nextIndex = (i + 1) % tabs.length;
        if (e.key === "ArrowLeft")
            nextIndex = (i - 1 + tabs.length) % tabs.length;
        if (e.key === "Home") nextIndex = 0;
        if (e.key === "End") nextIndex = tabs.length - 1;
        if (nextIndex === null) return;

        e.preventDefault();
        const id = tabs[nextIndex].id;
        setActiveTab(id);
        tabRefs.current[id]?.focus();
    };

    return (
        <>
            <div
                className="project-pills"
                role="tablist"
                aria-label="Sections du projet"
                onKeyDown={onKeyDown}
            >
                {tabs.map((tab) => (
                    <button
                        key={tab.id}
                        ref={(el) => {
                            tabRefs.current[tab.id] = el;
                        }}
                        className={`btn ${activeTab === tab.id ? "active" : "pill"}`}
                        type="button"
                        role="tab"
                        id={`tab-${tab.id}`}
                        aria-selected={activeTab === tab.id}
                        aria-controls={`panel-${tab.id}`}
                        tabIndex={activeTab === tab.id ? 0 : -1}
                        onClick={() => setActiveTab(tab.id)}
                    >
                        {tab.label}
                    </button>
                ))}
            </div>

            <div className="round"></div>

            <TabPanel
                id="description"
                activeTab={activeTab}
                className="project-description"
            >
                {project.description && (
                    <div className="description">
                        <div
                            dangerouslySetInnerHTML={{
                                __html: project.description,
                            }}
                        />
                    </div>
                )}

                <ProjectInfosDetails audience={audience} project={project} />
            </TabPanel>
            <TabPanel id="preview" activeTab={activeTab}>
                <ProjectPreview project={project} audience={audience} />
            </TabPanel>
            {project.auditMade && (
                <TabPanel id="audit" activeTab={activeTab}>
                    <ProjectPerf project={project} />
                </TabPanel>
            )}
        </>
    );
}
