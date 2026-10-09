import { Link } from "@inertiajs/react";
import { ProjectDetailsProps, ProjectLink } from "../types/projects";
import { projectImageUrl } from "../lib/images";
import { projectPath } from "../lib/paths";
import { Audience } from "../types/audience";
import NavArrow from "../components/ui/NavArrow";
import Contact from "../components/sections/contact/Contact";
import ProjectTabs from "../components/sections/projects/ProjectTabs";

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
        <div className="section-container project-container">
            <div className="content">
                <h1>{project.name}</h1>

                {/* key : nouveau projet, onglets recréés, retour sur « Description » */}
                <ProjectTabs
                    key={project.slug}
                    audience={audience}
                    project={project}
                />

                {(previous || next) && (
                    <nav className="projects-nav" aria-label="Autres projets">
                        {previous && (
                            <Link
                                href={projectPath(previous.slug, audience)}
                                className="projects-nav-link previous"
                            >
                                <img
                                    src={projectImageUrl(previous.background)}
                                    alt=""
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
                                    alt=""
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
