import { ExperienceProps } from "../../../types/experiences";

// vue d'ensemble du parcours : une étape = période, poste, entreprise.
// Le détail (missions, technos, projets) reste dans la timeline complète
export default function MiniTimeline({
    experiences,
}: {
    experiences: ExperienceProps[];
}) {
    return (
        <ol className="mini-timeline">
            {experiences.map((experience) => (
                <li key={experience.id} className="mini-timeline-item">
                    <span className="mini-timeline-date">
                        {experience.period}
                    </span>
                    <p className="mini-timeline-title">{experience.title}</p>
                    <p className="mini-timeline-organization">
                        {experience.organization}
                    </p>
                </li>
            ))}
        </ol>
    );
}
