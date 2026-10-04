import { Link } from "@inertiajs/react";
import { ExperienceProps } from "../../types/experiences";

export default function Parcours({
    experiences,
}: {
    experiences: ExperienceProps[];
}) {
    return (
        <div className="section-container parcours-container">
            <div className="parcours-wrapper">
                <div className="title">
                    <h2 className={"section-title"}>Mon parcours</h2>
                </div>

                <div className="timeline">
                    <ul>
                        {experiences.map((experience) => (
                            <TimelineItem
                                key={experience.id}
                                experience={experience}
                            />
                        ))}
                    </ul>
                </div>
            </div>
            {/*<div className="toolkit-wrapper">*/}
            {/*    <div className="toolkit">*/}
            {/*        <span>Toolkit</span>*/}
            {/*    </div>*/}
            {/*    <div className="tag-list" id="tagList">*/}
            {/*        <div className="fade"></div>*/}
            {/*    </div>*/}
            {/*</div>*/}
        </div>
    );
}

function TimelineItem({ experience }: { experience: ExperienceProps }) {
    const technos = experience.technos
        ?.split(",")
        .map((techno) => techno.trim())
        .filter(Boolean);

    return (
        <li className="timeline-item">
            <div className="timeline-content">
                <h2>{experience.title}</h2>
                <h3>{experience.organization}</h3>
                {experience.description && (
                    <div className="description">
                        <div
                            className="tasks"
                            dangerouslySetInnerHTML={{
                                __html: experience.description,
                            }}
                        />
                        {technos && technos.length > 0 && (
                            <ul className="tools">
                                {technos.map((techno) => (
                                    <li key={techno}>{techno}</li>
                                ))}
                            </ul>
                        )}
                    </div>
                )}

                {experience.projects.length > 0 && (
                    <p className="projects">
                        {experience.projects.map((project) => (
                            <span key={project.slug}>
                                <Link
                                    title={`Voir ${project.name} en détails`}
                                    href={`/projets/${project.slug}`}
                                    target="_blank"
                                >
                                    {project.name}
                                </Link>
                            </span>
                        ))}
                    </p>
                )}
                <span className="date">{experience.period}</span>
            </div>
        </li>
    );
}
