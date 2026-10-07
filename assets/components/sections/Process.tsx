// déroulé d'un projet, page création de site : ce qui rassure avant de prendre contact
const steps = [
    {
        title: "Premier échange",
        description:
            "On parle de votre activité et de vos besoins. C’est gratuit et sans engagement.",
    },
    {
        title: "Devis détaillé",
        description:
            "Vous savez exactement ce qui est prévu, et pour quel prix.",
    },
    {
        title: "Maquette validée",
        description:
            "Vous voyez à quoi ressemblera votre site avant le développement.",
    },
    {
        title: "Développement",
        description: "Je construis le site et l’ajuste au fil de vos retours.",
    },
    {
        title: "Mise en ligne",
        description:
            "Je m’occupe du nom de domaine, de l’hébergement et de la publication.",
    },
    {
        title: "Prise en main",
        description:
            "Je vous montre comment modifier vos contenus depuis le back-office.",
    },
];

export default function Process() {
    return (
        <div className="section-container process-container">
            <div className="content">
                <h2 className="section-title">Comment ça se passe</h2>
                <ol className="process-steps">
                    {steps.map((step, index) => (
                        <li key={step.title} className="step">
                            <span className="step-number" aria-hidden="true">
                                {String(index + 1).padStart(2, "0")}
                            </span>
                            <h3>{step.title}</h3>
                            <p>{step.description}</p>
                        </li>
                    ))}
                </ol>
            </div>
        </div>
    );
}
