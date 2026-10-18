import { ExperienceProps } from "../../../types/experiences";
import { Audience } from "../../../types/audience";
import TimelineItem from "../about/TimelineItem";
import MiniTimeline from "./MiniTimeline";
import { useRef, useState } from "react";

export default function Parcours({
    experiences,
    audience,
}: {
    experiences: ExperienceProps[];
    audience: Audience;
}) {
    const [showFullTimline, setShowFullTimline] = useState(false);
    const sectionRef = useRef<HTMLDivElement>(null);

    const toggle = () => {
        const opening = !showFullTimline;
        setShowFullTimline(opening);
        // sur mobile, le bouton est loin du début du détail : on remonte en haut
        // de la section (défilement doux, sauf si « réduire les animations » est actif)
        if (opening && window.matchMedia("(max-width: 768px)").matches) {
            sectionRef.current?.scrollIntoView({ block: "start" });
        }
    };

    return (
        <div className="section-container parcours-container" ref={sectionRef}>
            <div className="parcours-wrapper">
                <div className="title">
                    <h2 className={"section-title"}>Mon parcours</h2>
                </div>
                {/* les deux vues restent dans la page pour animer leur hauteur ;
                    celle qui est repliée est inerte (ni focus, ni lecteur d'écran) */}
                <div
                    className={`collapsible${showFullTimline ? "" : " is-open"}`}
                    inert={showFullTimline}
                >
                    <div className="collapsible-inner">
                        <MiniTimeline experiences={experiences} />
                    </div>
                </div>
                <button
                    type="button"
                    className="button toggle-timeline button-link "
                    aria-expanded={showFullTimline}
                    aria-controls="parcours-details"
                    onClick={toggle}
                >
                    {showFullTimline ? "Masquer" : "Voir en détails"}
                </button>
                <div
                    className={`collapsible${showFullTimline ? " is-open" : ""}`}
                    id="parcours-details"
                    inert={!showFullTimline}
                >
                    <div className="collapsible-inner">
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
            </div>
        </div>
    );
}
