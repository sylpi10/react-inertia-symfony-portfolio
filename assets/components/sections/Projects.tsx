import ProjectCard from "../projects/ProjectCard";
import { ProjectProps } from "../../types/projects";
import { Audience } from "../../types/audience";
import useMediaQuery from "../../hooks/useMediaQuery";
import { useState } from "react";
import PlusIcon from "../ui/PlusIcon";

export default function Projects({
    projects,
    audience,
}: {
    projects: ProjectProps[];
    audience: Audience;
}) {
    const isMobile = useMediaQuery("(max-width: 768px)");
    const [showAll, setShowAll] = useState(false);
    const limit: number = 4;
    const isCollapsed = isMobile && !showAll;
    const projectList = isCollapsed ? projects.slice(0, limit) : projects;
    const remainingProjects: number = projects.length - limit;

    return (
        <div className="section-container projects-container">
            <div className="content">
                <h2 className={"section-title"}>Projets réalisés</h2>
                <>
                    {projects.length >= 1 ? (
                        <div className="projects-list-container">
                            <ul className="projects-list">
                                {projectList.map((project) => {
                                    return (
                                        <li
                                            key={project.id}
                                            className="project-item"
                                        >
                                            <ProjectCard
                                                project={project}
                                                audience={audience}
                                            />
                                        </li>
                                    );
                                })}
                                {isCollapsed && projects.length > 4 && (
                                    <button
                                        className="btn more-btn"
                                        type="button"
                                        onClick={() => setShowAll(true)}
                                    >
                                        <PlusIcon />
                                        Voir {remainingProjects} projets
                                        supplémentaires
                                    </button>
                                )}
                            </ul>
                        </div>
                    ) : (
                        <div className={"loading-error"}>
                            <p>Une erreur est survenue lors du chargement...</p>
                        </div>
                    )}
                </>
            </div>
        </div>
    );
}
