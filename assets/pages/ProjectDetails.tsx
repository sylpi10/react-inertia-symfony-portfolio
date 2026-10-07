import { Link } from "@inertiajs/react";
import { ProjectDetailsProps, ProjectLink } from "../types/projects";
import { projectImageUrl } from "../lib/images";
import { projectPath } from "../lib/paths";
import { Audience } from "../types/audience";
import NavArrow from "../components/ui/NavArrow";
import ProjectInfosDetails from "../components/projects/ProjectInfosDetails";
import Contact from "../components/sections/Contact";

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
    return (
        <div className="section-container projects-container">
            <div className="content">
                <h1>{project.name}</h1>

                <div className="project-container">
                    <div className="round"></div>
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
                <div className="preview-images-wrapper">
                    <div className="computer-images-wrapper">
                        <div className="computer-container">
                            <div className="computer-img-container">
                                <img
                                    src={projectImageUrl(project.detailPic)}
                                    className="project-image"
                                    alt={`Aperçu du site ${project.name} sur ordinateur`}
                                    width="800"
                                    height="1000"
                                    decoding="async"
                                />
                            </div>
                        </div>
                    </div>
                    <div className="mobile-images-wrapper">
                        <div className="mobile-container">
                            <div className="mobile-img-container">
                                <img
                                    src={projectImageUrl(
                                        project.detail_pic_mobile ??
                                            project.detailPic,
                                    )}
                                    className="project-image"
                                    alt={`Aperçu du site ${project.name} sur mobile`}
                                    width="300"
                                    height="600"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </div>
                        </div>
                    </div>
                </div>
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
