import { Link } from "@inertiajs/react";
import { ProjectDetailsProps, ProjectLink } from "../types/projects";
import { projectImageUrl } from "../lib/images";
import { projectPath } from "../lib/paths";
import { Audience } from "../types/audience";
import NavArrow from "../components/ui/NavArrow";
import ProjectInfosDetails from "../components/sections/projects/ProjectInfosDetails";
import Contact from "../components/sections/contact/Contact";
import ProjectPreview from "../components/sections/projects/ProjectPreview";
import ProjectPerf from "../components/sections/projects/ProjectPerf";
import { useState } from "react";

const TABS = [
    { id: "description", label: "Description" },
    { id: "preview", label: "Aperçu" },
    { id: "audit", label: "Audit" },
] as const;
type TabId = (typeof TABS)[number]["id"];

export default function ProjectDetails({
    audience,
    project,
    previous,
    next,
}: {
    // mode de la page : texte, navigation et précédent/suivant
    audience: Audience;
    project: ProjectDetailsProps;
    previous: ProjectLink | null;
    next: ProjectLink | null;
}) {
    const activeTabs = TABS.filter(
        (tab) => tab.id !== "audit" || project.auditMade,
    );
    const [activeTab, setActiveTab] = useState<TabId>("description");

    return (
        <div className="section-container project-container">
            <div className="content">
                <h1>{project.name}</h1>

                <div
                    className="project-pills"
                    role="tablist"
                    aria-label="Sections du projet"
                >
                    {activeTabs.map((tab) => (
                        <button
                            className={`btn ${activeTab === tab.id ? "active" : "pill"}`}
                            key={tab.id}
                            type="button"
                            role="tab"
                            id={`tab-${tab.id}`}
                            aria-selected={activeTab === tab.id}
                            aria-controls={`panel-${tab.id}`}
                            onClick={() => setActiveTab(tab.id)}
                        >
                            {tab.label}
                        </button>
                    ))}
                </div>

                <div className="round"></div>

                {activeTab === "description" && (
                    <div
                        role="tabpanel"
                        id="panel-description"
                        aria-labelledby="tab-description"
                        className="project-description panel-container"
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

                        <ProjectInfosDetails
                            audience={audience}
                            project={project}
                        />
                    </div>
                )}
                {activeTab === "preview" && (
                    <div
                        role="tabpanel"
                        id="panel-preview"
                        aria-labelledby="tab-preview"
                        className="panel-container"
                    >
                        <ProjectPreview project={project} />
                    </div>
                )}
                {activeTab === "audit" && (
                    <div
                        role="tabpanel"
                        id="panel-audit"
                        aria-labelledby="tab-audit"
                        className="panel-container"
                    >
                        <ProjectPerf project={project} />
                    </div>
                )}

                {(previous || next) && (
                    <nav className="projects-nav" aria-label="Autres projets">
                        {previous && (
                            <Link
                                href={projectPath(previous.slug, audience)}
                                className="projects-nav-link previous"
                            >
                                <img
                                    src={projectImageUrl(previous.background)}
                                    alt={`${previous.name}`}
                                />
                                <div className="labels-wrapper">
                                    <NavArrow direction="previous" />
                                    <ProjectLinkText
                                        label="Projet précédent"
                                        project={previous}
                                    />
                                </div>
                            </Link>
                        )}
                        {next && (
                            <Link
                                href={projectPath(next.slug, audience)}
                                className="projects-nav-link next"
                            >
                                <img
                                    src={projectImageUrl(next.background)}
                                    alt={`${next.name}`}
                                />
                                <div className="labels-wrapper">
                                    <ProjectLinkText
                                        label="Projet suivant"
                                        project={next}
                                    />
                                    <NavArrow direction="next" />
                                </div>
                            </Link>
                        )}
                    </nav>
                )}
            </div>
            <section id="contact">
                <Contact audience={audience} />
            </section>
        </div>
    );
}

// le libellé reste lu par les lecteurs d'écran, les flèches le remplacent à l'écran
function ProjectLinkText({
    label,
    project,
}: {
    label: string;
    project: ProjectLink;
}) {
    return (
        <span className="text">
            <span className="visually-hidden">{label} : </span>
            <span className="name">{project.name}</span>{" "}
            <span className="teaser">{project.teaser}</span>
        </span>
    );
}
