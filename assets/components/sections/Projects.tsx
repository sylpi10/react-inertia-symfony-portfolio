import ProjectCard from "../projects/ProjectCard";
import { ProjectProps } from "../../types/projects";
import { Audience } from "../../types/audience";

export default function Projects({
    projects,
    audience,
}: {
    projects: ProjectProps[];
    audience: Audience;
}) {
    return (
        <div className="section-container projects-container">
            <div className="content">
                <h2 className={"section-title"}>Projets réalisés</h2>
                <>
                    {projects.length >= 1 ? (
                        <div className="projects-list-container">
                            <ul className="projects-list">
                                {projects.map((project) => {
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
