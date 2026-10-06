import { Link } from "@inertiajs/react";
import { OfferProps } from "../../types/offers";
import { projectPath } from "../../lib/paths";

/**
 * MODE : AGENCE WEB
 */
export default function Services({ offers }: { offers: OfferProps[] }) {
    return (
        <div className="section-container services-container">
            <h2 className="section-title">Ce que je peux faire pour vous</h2>
            <p className="services-intro">
                Développeur freelance à Toulouse, j’accompagne les indépendants,
                TPE et PME, de la création de site vitrine à l’application web
                sur mesure, partout en France.
            </p>
            <ul className="services-list">
                {offers.map((offer, index) => (
                    <li key={offer.id} className="service">
                        <span className="service-number" aria-hidden="true">
                            {String(index + 1).padStart(2, "0")}
                        </span>
                        <h3>{offer.title}</h3>
                        <div
                            className="service-description"
                            dangerouslySetInnerHTML={{
                                __html: offer.description,
                            }}
                        />
                        {offer.projects.length > 0 && (
                            <p className="service-examples">
                                <span className="label">Exemples :</span>
                                {offer.projects.map((project) => (
                                    <Link
                                        key={project.slug}
                                        href={projectPath(
                                            project.slug,
                                            "client",
                                        )}
                                    >
                                        {project.name}
                                    </Link>
                                ))}
                            </p>
                        )}
                    </li>
                ))}
            </ul>
        </div>
    );
}
