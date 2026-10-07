import { Link } from "@inertiajs/react";
import { ExperienceProps } from "../../../types/experiences";
import { Audience } from "../../../types/audience";
import { projectPath } from "../../../lib/paths";

export default function TimelineItem({
    experience,
    audience,
}: {
    experience: ExperienceProps;
    audience: Audience;
}) {
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
                        {audience === "team" &&
                            technos &&
                            technos.length > 0 && (
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
                                    href={projectPath(project.slug, audience)}
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
