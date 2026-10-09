import { Audience } from "../../../types/audience";
import { ProjectProps } from "../../../types/projects";
import GithubIcon from "../../ui/GithubIcon";
import WebIcon from "../../ui/WebIcon";

// encart de la page détail : technos et liens pour une équipe ; fiche, lien vers le site
// et appel au contact pour un client
export default function ProjectInfosDetails({
    audience,
    project,
}: {
    audience: Audience;
    // seuls champs utiles : marche avec ProjectProps comme avec ProjectDetailsProps
    project: Pick<
        ProjectProps,
        "name" | "date" | "technos" | "weblink" | "githublink"
    >;
}) {
    if (audience === "client") {
        return (
            <aside className="details-site-link">
                <h2>Le projet en bref</h2>
                <dl>
                    <div>
                        <dt>Année</dt>
                        <dd>{project.date}</dd>
                    </div>
                    {project.weblink && (
                        <div>
                            <dt>En ligne</dt>
                            <dd>{displayHost(project.weblink)}</dd>
                        </div>
                    )}
                </dl>
                {project.weblink && (
                    <a
                        href={project.weblink}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="visit-button"
                        aria-label={`Visiter le site ${project.name} (nouvel onglet)`}
                    >
                        <WebIcon />
                        Visiter le site
                    </a>
                )}
                {/* l'objectif de la page : passer du projet vu au projet du visiteur */}
                <p className="contact-cta">
                    Un projet similaire ? <a href="#contact">Parlons-en</a>
                </p>
            </aside>
        );
    }

    const technosItems = project.technos.split(",").map((word) => word.trim());

    return (
        <div className="infos">
            <div className="tecnhos">
                <p className={"title"}>Boite à outils du projet:</p>
                <ul>
                    {technosItems.map((techno) => (
                        <li key={techno}>{techno}</li>
                    ))}
                </ul>
            </div>
            <div className={"buttons-link-wrapper"}>
                {project.weblink && (
                    <div className={"button-link"}>
                        <a
                            href={project.weblink}
                            target="_blank"
                            rel="noopener noreferrer"
                            className="see-more"
                            aria-label={`Site de ${project.name} (nouvel onglet)`}
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
                            rel="noopener noreferrer"
                            className="see-more"
                            aria-label={`GitHub de ${project.name} (nouvel onglet)`}
                        >
                            <GithubIcon />
                            <span>Github</span>
                        </a>
                    </div>
                )}
            </div>
        </div>
    );
}

// « www.exemple.fr » plutôt que l'URL complète ; l'URL telle quelle si elle est mal formée
function displayHost(url: string): string {
    try {
        return new URL(url).host;
    } catch {
        return url;
    }
}
