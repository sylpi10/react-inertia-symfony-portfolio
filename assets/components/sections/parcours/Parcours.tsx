import { ExperienceProps } from "../../../types/experiences";
import { Audience } from "../../../types/audience";
import TimelineItem from "../about/TimelineItem";

export default function Parcours({
    experiences,
    audience,
}: {
    experiences: ExperienceProps[];
    audience: Audience;
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
                                audience={audience}
                            />
                        ))}
                    </ul>
                </div>
            </div>
        </div>
    );
}
