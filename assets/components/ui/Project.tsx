import { Link } from "@inertiajs/react";
import { ProjectProps } from "../../types/projects";
import { projectImageUrl } from "../../lib/images";
import { projectPath } from "../../lib/paths";
import { Audience } from "../../types/audience";
import PLusIcon from "./PlusIcon";
import WebIcon from "./WebIcon";

export default function Project({
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

    return (
        <div className="item-content card-item">
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
                <div className="description">
                    <div
                        dangerouslySetInnerHTML={{
                            __html: project.miniDescription,
                        }}
                    />
                </div>

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
                    {project.githublink && (
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
                    <div className="button-link">
                        <Link
                            href={projectPath(project.slug, audience)}
                            className="see-more"
                            title="Voir les détails du projet"
                        >
                            <PLusIcon />
                            <span>Détails</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    );
}
