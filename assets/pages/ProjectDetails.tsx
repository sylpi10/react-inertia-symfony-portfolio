import { Link } from "@inertiajs/react";
import { ProjectDetailsProps, ProjectLink } from "../types/projects";
import { projectImageUrl } from "../lib/images";

export default function ProjectDetails({
    project,
    previous,
    next,
}: {
    project: ProjectDetailsProps;
    previous: ProjectLink | null;
    next: ProjectLink | null;
}) {
    const technosItems = project.technos
        .split(",")
        .map((word: string) => word.trim());

    return (
        <main className="section-container projects-container">
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

                    <div className="infos">
                        <div className="tecnhos">
                            <p className={"title"}>Boite à outils du projet:</p>
                            <ul>
                                {technosItems.map((techno, index) => (
                                    <li key={index}>{techno}</li>
                                ))}
                            </ul>
                        </div>
                        <div className={"buttons-link-wrapper"}>
                            {project.weblink && (
                                <div className={"button-link"}>
                                    <a
                                        href={project.weblink}
                                        target="_blank"
                                        className="see-more"
                                        title="Aller sur le site"
                                    >
                                        <svg
                                            className={"weblink-icon"}
                                            width="20px"
                                            height="20px"
                                            viewBox="0 0 64 64"
                                            xmlns="http://www.w3.org/2000/svg"
                                            stroke-width="3"
                                            stroke="#E4D00A"
                                            fill="none"
                                        >
                                            <path d="M39.93,55.72A24.86,24.86,0,1,1,56.86,32.15a37.24,37.24,0,0,1-.73,6" />
                                            <path d="M37.86,51.1A47,47,0,0,1,32,56.7" />
                                            <path d="M32,7A34.14,34.14,0,0,1,43.57,30a34.07,34.07,0,0,1,.09,4.85" />
                                            <path d="M32,7A34.09,34.09,0,0,0,20.31,32.46c0,16.2,7.28,21,11.66,24.24" />
                                            <line
                                                x1="10.37"
                                                y1="19.9"
                                                x2="53.75"
                                                y2="19.9"
                                            />
                                            <line
                                                x1="32"
                                                y1="6.99"
                                                x2="32"
                                                y2="56.7"
                                            />
                                            <line
                                                x1="11.05"
                                                y1="45.48"
                                                x2="37.04"
                                                y2="45.48"
                                            />
                                            <line
                                                x1="7.14"
                                                y1="32.46"
                                                x2="56.86"
                                                y2="31.85"
                                            />
                                            <path d="M53.57,57,58,52.56l-8-8,4.55-2.91a.38.38,0,0,0-.12-.7L39.14,37.37a.39.39,0,0,0-.46.46L42,53.41a.39.39,0,0,0,.71.13L45.57,49Z" />
                                        </svg>
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
                                        <svg
                                            className={"github-icon"}
                                            width="20px"
                                            height="20px"
                                            viewBox="0 0 48 48"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <title>github</title>
                                            <g id="Layer_2" data-name="Layer 2">
                                                <g
                                                    id="invisible_box"
                                                    data-name="invisible box"
                                                >
                                                    <rect
                                                        width="48"
                                                        height="48"
                                                        fill="none"
                                                    />
                                                    <rect
                                                        width="48"
                                                        height="48"
                                                        fill="none"
                                                    />
                                                </g>
                                                <g
                                                    id="icons_Q2"
                                                    data-name="icons Q2"
                                                    fill={"#E4D00A"}
                                                >
                                                    <path d="M24,1.9a21.6,21.6,0,0,0-6.8,42.2c1,.2,1.8-.9,1.8-1.8V39.4c-6,1.3-7.9-2.9-7.9-2.9a6.5,6.5,0,0,0-2.2-3.2C6.9,31.9,9,32,9,32a4.3,4.3,0,0,1,3.3,2c1.7,2.9,5.5,2.6,6.7,2.1a5.4,5.4,0,0,1,.5-2.9C12.7,32,9,28,9,22.6A10.7,10.7,0,0,1,11.9,15a6.2,6.2,0,0,1,.3-6.4,8.9,8.9,0,0,1,6.4,2.9,15.1,15.1,0,0,1,5.4-.8,17.1,17.1,0,0,1,5.4.7,9,9,0,0,1,6.4-2.8,6.5,6.5,0,0,1,.4,6.4A10.7,10.7,0,0,1,39,22.6C39,28,35.3,32,28.5,33.2a5.4,5.4,0,0,1,.5,2.9v6.2a1.8,1.8,0,0,0,1.9,1.8A21.7,21.7,0,0,0,24,1.9Z" />
                                                </g>
                                            </g>
                                        </svg>
                                        <span>Github</span>
                                    </a>
                                </div>
                            )}
                        </div>
                    </div>
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
                                href={`/projets/${previous.slug}`}
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
                                href={`/projets/${next.slug}`}
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
        </main>
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

function NavArrow({ direction }: { direction: "previous" | "next" }) {
    return (
        <svg
            className={`nav-arrow ${direction}`}
            width="28"
            height="28"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            strokeWidth="2"
            strokeLinecap="round"
            strokeLinejoin="round"
            aria-hidden="true"
        >
            {direction === "previous" ? (
                <path d="M19 12H5M11 18l-6-6 6-6" />
            ) : (
                <path d="M5 12h14M13 6l6 6-6 6" />
            )}
        </svg>
    );
}
