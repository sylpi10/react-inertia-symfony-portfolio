import { Link } from "@inertiajs/react";
import { plans } from "../../../lib/pricing";

export default function Pricing() {
    return (
        <div className="section-container pricing-container">
            <div className="content">
                <h2 className="section-title">Tarifs</h2>
                <p className="pricing-intro">
                    Prix indicatifs, chaque projet fait l’objet d’un devis
                    détaillé, gratuit et sans engagement.{" "}
                    <Link href="/creation-site-web/prix-site-vitrine">
                        Ce qui fait varier le prix d’un site vitrine
                    </Link>
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
