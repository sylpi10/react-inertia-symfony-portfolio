// tarifs de la page création de site ; repères du marché (Codeur) : vitrine
// simple ~1 000 €, vitrine marketing 1 200 à 3 000 €, sur mesure 1 500 à 4 000 €
const plans = [
    {
        title: "Site vitrine essentiel",
        description:
            "Quelques pages pour présenter votre activité, et un espace d’administration simple pour modifier vos textes et vos photos.",
        price: "à partir de 1 500 €",
    },
    {
        title: "Site vitrine sur mesure",
        description:
            "Un design personnalisé, un back-office complet pour gérer tous vos contenus et un référencement soigné.",
        price: "à partir de 2 500 €",
    },
    {
        title: "Refonte ou application sur mesure",
        description:
            "Reprise d’un site existant pour le moderniser, ou outil adapté à votre façon de travailler.",
        price: "sur devis",
    },
];

export default function Pricing() {
    return (
        <div className="section-container pricing-container">
            <div className="content">
                <h2 className="section-title">Tarifs</h2>
                <p className="pricing-intro">
                    Prix indicatifs, chaque projet fait l’objet d’un devis
                    détaillé, gratuit et sans engagement.
                </p>
                <ul className="pricing-list">
                    {plans.map((plan) => (
                        <li key={plan.title} className="plan">
                            <h3>{plan.title}</h3>
                            <div className="plan-body">
                                <p className="plan-description">
                                    {plan.description}
                                </p>
                                <p className="plan-price">{plan.price}</p>
                            </div>
                        </li>
                    ))}
                </ul>
                {/* revenu récurrent : mis en avant, sur toute la largeur */}
                <div className="plan plan-maintenance">
                    <div className="plan-main">
                        <h3>Hébergement et maintenance</h3>
                        <p className="plan-description">
                            Votre site reste en ligne, sécurisé et à jour sans
                            que vous ayez à vous en occuper : hébergement, mises
                            à jour et petites modifications sont inclus.
                        </p>
                    </div>
                    <div className="plan-side">
                        <p className="plan-price">forfait mensuel</p>
                        <a href="#contact" className="plan-cta">
                            Demander un devis
                        </a>
                    </div>
                </div>
            </div>
        </div>
    );
}
