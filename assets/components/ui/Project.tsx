// import webIcon from "../../../public/assets/images/website-icon.svg";
import { Link } from "@inertiajs/react";
import { ProjectProps } from "../../types/projects";
import { projectImageUrl } from "../../lib/images";

export default function Project({
    project,
}: {
    project: ProjectProps;
}): React.ReactNode {
    const technosItems: string = project.technos
        .split(",")
        .slice(0, 2)
        .join(",");

    return (
        <div className="item-content">
            <div
                className="item-bg"
                style={{
                    backgroundImage: `url(${projectImageUrl(project.background)})`,
                }}
            />
            <h3>{project.name}</h3>
            <div className="infos">
                <span className="date">{project.date}</span>
                <span className="techno">{technosItems}</span>
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
                                <line x1="32" y1="6.99" x2="32" y2="56.7" />
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
                                <g data-name="Layer 2">
                                    <g data-name="invisible box">
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
                                    <g data-name="icons Q2" fill={"#E4D00A"}>
                                        <path d="M24,1.9a21.6,21.6,0,0,0-6.8,42.2c1,.2,1.8-.9,1.8-1.8V39.4c-6,1.3-7.9-2.9-7.9-2.9a6.5,6.5,0,0,0-2.2-3.2C6.9,31.9,9,32,9,32a4.3,4.3,0,0,1,3.3,2c1.7,2.9,5.5,2.6,6.7,2.1a5.4,5.4,0,0,1,.5-2.9C12.7,32,9,28,9,22.6A10.7,10.7,0,0,1,11.9,15a6.2,6.2,0,0,1,.3-6.4,8.9,8.9,0,0,1,6.4,2.9,15.1,15.1,0,0,1,5.4-.8,17.1,17.1,0,0,1,5.4.7,9,9,0,0,1,6.4-2.8,6.5,6.5,0,0,1,.4,6.4A10.7,10.7,0,0,1,39,22.6C39,28,35.3,32,28.5,33.2a5.4,5.4,0,0,1,.5,2.9v6.2a1.8,1.8,0,0,0,1.9,1.8A21.7,21.7,0,0,0,24,1.9Z" />
                                    </g>
                                </g>
                            </svg>
                            <span>Github</span>
                        </a>
                    </div>
                )}
                <div className="button-link">
                    <Link
                        href={`/projets/${project.slug}`}
                        className="see-more"
                        title="Voir les détails du projet"
                    >
                        <svg
                            className={"plus-icon"}
                            fill="#E4D00A"
                            height="20px"
                            width="20px"
                            version="1.1"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 512 512"
                        >
                            <g>
                                <g>
                                    <path
                                        d="M491.841,156.427c-19.471-45.946-51.936-85.013-92.786-112.637C358.217,16.166,308.893-0.007,256,0
                                    c-35.254-0.002-68.946,7.18-99.571,20.158C110.484,39.63,71.416,72.093,43.791,112.943C16.167,153.779-0.007,203.104,0,256
                                    c-0.002,35.255,7.181,68.948,20.159,99.573c19.471,45.946,51.937,85.013,92.786,112.637C153.783,495.834,203.107,512.007,256,512
                                    c35.253,0.002,68.946-7.18,99.571-20.158c45.945-19.471,85.013-51.935,112.638-92.785C495.834,358.22,512.007,308.894,512,256
                                    C512.002,220.744,504.819,187.052,491.841,156.427z M460.413,342.257c-16.851,39.781-45.045,73.723-80.476,97.676
                                    c-35.443,23.953-78.02,37.926-123.936,37.933c-30.619-0.002-59.729-6.218-86.255-17.454
                                    c-39.781-16.851-73.724-45.044-97.677-80.475C48.114,344.495,34.14,301.917,34.133,256c0.002-30.62,6.219-59.731,17.454-86.257
                                    c16.851-39.781,45.045-73.724,80.476-97.676C167.506,48.113,210.084,34.14,256,34.133c30.619,0.002,59.729,6.218,86.255,17.454
                                    c39.781,16.85,73.724,45.044,97.677,80.475c23.953,35.443,37.927,78.02,37.934,123.939
                                    C477.864,286.62,471.648,315.731,460.413,342.257z"
                                    />
                                </g>
                            </g>
                            <g>
                                <g>
                                    <path
                                        d="M389.594,239.301H272.699V122.406c0-9.222-7.477-16.699-16.699-16.699c-9.222,0-16.699,7.477-16.699,16.699v116.895
                                H122.406c-9.222,0-16.699,7.477-16.699,16.699s7.477,16.699,16.699,16.699h116.895v116.895c0,9.222,7.477,16.699,16.699,16.699
                                c9.222,0,16.699-7.477,16.699-16.699V272.699h116.895c9.222,0,16.699-7.477,16.699-16.699S398.817,239.301,389.594,239.301z"
                                    />
                                </g>
                            </g>
                        </svg>
                        <span>Détails</span>
                    </Link>
                </div>
            </div>
        </div>
    );
}
