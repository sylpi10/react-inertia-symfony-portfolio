import Project from "../ui/Project";
import { ProjectProps } from "../../types/projects";

export default function Projects({ projects }: { projects: ProjectProps[] }) {
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
                                            <Project project={project} />
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
