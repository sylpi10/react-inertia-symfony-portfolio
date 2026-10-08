import { Link } from "@inertiajs/react";
import { ProjectProps } from "../../../types/projects";
import { projectImageUrl } from "../../../lib/images";
import { projectPath } from "../../../lib/paths";
import { Audience } from "../../../types/audience";
import PlusIcon from "../../ui/PlusIcon";
import WebIcon from "../../ui/WebIcon";

// carte d'un projet dans la liste des pages d'accueil (équipe et création de site)
export default function ProjectCard({
    project,
    audience,
}: {
    project: ProjectProps;
    audience: Audience;
}): React.ReactNode {
    const technosItems: string = project.technos
        .split(",")
        .slice(0, 2)
        .join(",");

    const projectImage =
        project.thumbnail ?? projectImageUrl(project.background);
    const detailsUrl = projectPath(project.slug, audience);

    return (
        <div className="item-content card-item">
            {/* couvre toute la carte (projects.scss) : la carte entière mène aux
                détails ; la rangée de liens Site / Github passe au-dessus */}
            <Link
                href={detailsUrl}
                className="card-link"
                aria-label={`Voir le projet ${project.name}`}
            />
            <div className="item-header">
                {/* ratio des vignettes générées : réserve la place (pas de CLS) */}
                <h3>{project.name}</h3>
                <img
                    src={projectImage}
                    alt={`Aperçu du site ${project.name}`}
                    width="1200"
                    height="630"
                    loading="lazy"
                    decoding="async"
                />
                <div className="infos">
                    <span className="date">{project.date}</span>
                    {audience === "team" && (
                        <span className="techno">{technosItems}</span>
                    )}
                </div>
            </div>
            <div className="item-footer">
                {project.miniDescription && (
                    <div className="description">
                        <div
                            dangerouslySetInnerHTML={{
                                __html: project.miniDescription,
                            }}
                        />
                    </div>
                )}

                <div className="links-wrapper">
                    {project.weblink && (
                        <div className={"button-link"}>
                            <a
                                href={project.weblink}
                                target="_blank"
                                className="see-more"
                                title="Aller sur le site"
                            >
                                <WebIcon />
                                <span> Site</span>
                            </a>
                        </div>
                    )}
                    {audience === "team" && project.githublink && (
                        <div className="button-link">
                            <a
                                href={project.githublink}
                                target="_blank"
                                className="see-more"
                                title="Répo github"
                            >
                                <WebIcon />
                                <span>Github</span>
                            </a>
                        </div>
                    )}
                    {/* repère visuel : même cible que card-link, ignoré au clavier
                        et par les lecteurs d'écran pour ne pas doubler le lien */}
                    <div className="button-link">
                        <Link
                            href={detailsUrl}
                            className="see-more"
                            tabIndex={-1}
                            aria-hidden="true"
                        >
                            <PlusIcon />
                            <span>Détails</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
